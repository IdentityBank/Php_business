(() => {
    let metadata;
    let completeData = {};

    $.get(getModelURL).done((data) => {
        metadata = data;
        if (typeof metadata === 'string') {
            metadata = JSON.parse(metadata);
        }

        initCompleteData();
        rerenderForm();

        $('.loading').hide();

    });

    $(document).on('change', '.data-input', (e) => {
        let uuid = e.target.dataset.uuid;

        completeData[uuid] = e.target.value;

        if ($('input:invalid').length < 1) {
            $('.btn-create').removeAttr('disabled');
        }
    });

    $('.btn-create').click(e => {

        if ($('input:invalid').length > 0) {
            $('#idb-data-create').addClass('show-invalid');
            $('.btn-create').attr('disabled', 'disabled');
            $('.danger-message').text(emptyRequiredMessage);

            $('html').animate({scrollTop: 0}, 400);
            $('.alert-danger').slideDown(400);
        } else {
            $('.loading').show();
            $.ajax({
                type: 'POST',
                url: updateURL,
                data: completeData,

            }).done(data => {
                data = JSON.parse(data);

                if (data.success !== undefined && data.success) {
                    window.location.replace(showAllURL);
                } else {
                    $('.loading').hide();
                    if (data.message !== undefined) {
                        $('.danger-message').text(data.message);
                    } else {
                        $('.danger-message').text(errorMessage);
                    }
                    $('html').animate({scrollTop: 0}, 400);
                    $('.alert-danger').slideDown(400);
                }
            });
        }
    });

    function initCompleteData() {
        for (let i = 0; i < metadata.database.length; i++) {
            completeData[metadata.database[i].uuid] = model[i + 1];
        }
    }

    let counter = 1;

    function renderSet(set) {
        let html = '<div class="append-data-set">';
        html += '<h4>' + (set.display_name ? set.display_name : '') + ':</h4>';

        for (let data of set.data) {
            if (data.object_type === 'type') {
                let requiredSpan = '';
                if (data.required === 'true') {
                    requiredSpan = '<span class="required">*</span>'
                }

                html += '<label>' + data.display_name + requiredSpan + '</label>';
                html += '<input value="' + (model[counter] ? model[counter] : '') + '" class="data-input form-control append-data-input" ' + (data.required === 'true' ? 'required ' : '') + 'data-uuid="' + data.uuid + '" type="text" />';

                counter++;
            } else if (data.object_type === 'set') {
                html += renderSet(data);
            }
        }

        html += '</div>';

        return html;
    }

    function rerenderForm() {
        $('#idb-data-create').html(renderSet(metadata));
    }
})();
