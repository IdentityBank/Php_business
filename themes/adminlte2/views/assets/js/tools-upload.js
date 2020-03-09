Dropzone.options.upload =
    {
        maxFiles: 1,
        maxFilesize: 100, // Max 100M
        timeout: 5000,
        chunkSize: (512 * 1024),
        chunking: true,
        forceChunking: true,
        uploadChunkingStatus: true,
        parallelChunkUploads: false,
        autoProcessQueue: false,

        init: function () {
            this.on("addedfile", function (file) {
                $("#upload_dropzone_overlay").show();
            });
            this.on("enqueuedFile", function (file) {
                startMd5();
                md5File(file);
            });
            this.on("uploadprogress", function (file, progress, bytesSent) {
                progress = Math.round(progress);
                if ((progress < 100) || (file.size <= bytesSent)) {
                    var width = Math.round(progress) + '%';
                    $("#pb").html(width);
                    $("#pb").width(width);
                }
            });
            this.on("success", function (file, response) {
                if (response) {
                    try {
                        response = JSON.parse(response);
                        var server_md5 = response.file_md5;
                        var client_md5 = response.client_md5;
                        var redirect = response.redirect;

                        if (client_md5 == server_md5) {
                            successUpload(redirect);
                        } else {
                            errorUpload();
                        }
                    } catch (e) {
                        errorUpload();
                    }
                } else {
                    errorUpload();
                }
            });
            this.on("error", function (file, errorMessage) {
                errorUpload(errorMessage);
            });
            this.on("canceled", function (file) {
                errorUpload('canceled');
            });
            this.on("sending", function (file, xhr, formData) {
                formData.append("filesize", file.size);
                formData.append("fileuuid", file.upload.uuid);
                formData.append("client_md5", file.upload.md5);
            });
        },
        params: {"_csrf": yii.getCsrfToken()}
    };

Dropzone.options.upload.maxFilesize = dropzone_options_upload.maxFilesize;
Dropzone.options.upload.timeout = dropzone_options_upload.timeout;
Dropzone.options.upload.chunkSize = dropzone_options_upload.chunkSize;
Dropzone.options.upload.acceptedFiles = dropzone_options_upload.acceptedFiles;

function uploadFile(md5) {
    console.log('finished loading');
    console.info('computed hash', md5);
    initialUpload();
    $("#upload")[0].dropzone.processQueue();
}

function md5File(file) {
    var blobSlice = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice,
        chunkSize = (512 * 1024), // Read in chunks of 512kB
        chunks = Math.ceil(file.size / chunkSize),
        currentChunk = 0,
        spark = new SparkMD5.ArrayBuffer(),
        fileReader = new FileReader();

    fileReader.onload = function (e) {
        var md5Progress = ((chunks > 0) ? ((currentChunk + 1) * 100 / chunks) : "100");
        var width = Math.round(md5Progress) + '%';
        $("#pb").html(width);
        $("#pb").width(width);

        spark.append(e.target.result);
        currentChunk++;

        if (currentChunk < chunks) {
            loadNext();
        } else {
            var md5 = spark.end();
            file.upload.md5 = md5;
            uploadFile(md5);
        }
    };

    fileReader.onerror = function () {
        console.warn('error calculating checksum ...');
    };

    function loadNext() {
        var start = currentChunk * chunkSize,
            end = ((start + chunkSize) >= file.size) ? file.size : start + chunkSize;
        var blob = blobSlice.call(file, start, end);
        fileReader.readAsArrayBuffer(blob);
    }

    loadNext();
}

function startMd5() {
    $("#progress_message").html(translation.progress_message_checksum);
    $("#progress_block").show();
}

function initialUpload() {
    var width = '0%';
    $("#pb").html(width);
    $("#pb").width(width);
    $("#progress_message").html(translation.progress_message_upload);
}

function successUpload(redirect = null, message = null) {
    if (!!redirect) {
        $(location).attr('href', redirect);
    } else {
        $("#progress_block").hide();
        $("#upload_dropzone_overlay").hide();
        $("#upload_dropzone").hide();
        $("#status_success").show();
        if (message !== null) $("#success_msg").html(message);
    }
}

function errorUpload(errorMessage = null) {
    $("#progress_block").hide();
    $("#upload_dropzone_overlay").hide();
    $("#upload_dropzone").hide();
    $("#staus_error").show();
    $("#error_msg").html(translation.error_msg + ' <br>');
    if (errorMessage !== null) {
        console.log(errorMessage);
        if (errorMessage.startsWith("File is too big")) {
            var regexString = "File is too big \\(([+-]?[0-9]*[.]?[0-9]+)MiB\\). Max filesize: ([+-]?([0-9]*[.])?[0-9]+)MiB.";
            var found = errorMessage.match(regexString);
            if (found !== undefined && 3 <= found.length) {
                fileSize = found[1];
                fileMaxsize = found[2];
                error_msg_file_to_big = translation.error_msg_file_to_big.replace("{{filesize}}", fileSize).replace("{{maxFilesize}}", fileMaxsize);
                $("#error_msg").html(translation.error_msg_file_to_big + ' <br>');
            }
        } else if (errorMessage === "You can't upload files of this type.") {
            $("#error_msg").html(translation.error_msg_accepted_files + ' <br>');
        } else if (errorMessage === "You cannot upload empty file.") {
            $("#error_msg").html(translation.error_msg_empty_file + ' <br>');
        }
    }
}

if ((typeof uploadDisabled !== 'undefined') && (uploadDisabled)) {
    errorUpload(translation.upload_disabled_msg);
    $("#staus_error").html(translation.upload_disabled_msg);
    $("#retry_button").hide();
}
