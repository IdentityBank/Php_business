let table;
let usedIds = [];
let currentPage = 1;

if (model.database.length > 10) {
    $('#columns-pager').pagination({
        dataSource: model.database,
        className: 'paginationjs-small',
        callback: (data, pagination) => {
            $('#show-column-group-' + currentPage).addClass('hidden');
            currentPage = pagination.pageNumber;
            $('#show-column-group-' + currentPage).removeClass('hidden');
        }
    });
}

$(document).ready(() => {
    if (afterSearch !== false) {
        for (let s of afterSearch) {
            $('input[name="' + s.uuid + '"').val(s.value);
        }
    }

    let order = getStartOrder();
    table = $('#table_id_user_manager').DataTable({
        'paging': false,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        scrollY: '70vh',
        scrollCollapse: true,
        'info': false,
        'autoWidth': false,
        'scrollX': true,
        'order': order
    });
    let firstSent = false;
    $('#used-for-send').click(() => {
        if ($("#used-for-legal").val() !== "" && $('#used-for-legal').val() !== null) {
            let data = {
                message: $("#used-for-area").val(),
                ids: usedIds,
                legal: $('#used-for-legal').val(),
                send_sms: $('#used-for-sms').val(),
                send_mail: $('#used-for-mail').val(),
                mobile: $('#col_mobile').val(),
                mail: $('#col_mail').val()
            };

            $('.loading').removeClass('hidden');

            $.ajax({
                type: 'POST',
                url: usedForURL,
                data: data
            }).done(data => {
                window.location.reload(true);
            });
        } else {
            $('#used-for-legal').addClass('invalid');
            $('#used-for-send').attr('disabled', 'disabled');
        }
    });

    $('#send-delete-multiple').click(() => {
        $('.loading').removeClass('hidden');

        usedIds = getIdsByRows($('#grid_id_user_manager').yiiGridView('getSelectedRows'));
        $.ajax({
            type: 'POST',
            url: deleteMultipleURL,
            data: {ids: usedIds}
        }).done(data => {
            window.location.reload(true);
        });
    });

    $('#used-for-legal').change(() => {
        $('#used-for-legal').removeClass('invalid');
        $('#used-for-send').removeAttr('disabled');
    });

    $('#used-for-select').change((e) => {
        if (e.target.value !== 'other') {
            $('#used-for-area').val(e.target.value);
        } else {
            $('#used-for-area').val('');
        }
    });

    $('input[type="checkbox"]').change((e) => {
        if ($('.used-checkbox:checked').length > 0) {
            $('#audit-multiple').removeAttr('disabled');
            $('#delete-multiple').removeAttr('disabled');
        } else {
            $('#audit-multiple').attr('disabled', 'disabled');
            $('#delete-multiple').attr('disabled', 'disabled');
        }
    });

    table.on('order.dt', (e, settings) => {
        let colNumber = settings.aLastSort[0].col;
        if (colNumber > 0) {
            $('.loading').removeClass('hidden');
            let dir = settings.aLastSort[0].dir;
            let columnName = $($('#col-name-' + colNumber)).html();
            let url = updateURLParameter(window.location.href, 'sort-by', columnName);
            url = updateURLParameter(url, 'sort-dir', dir);
            window.location.replace(url);
        }
    });

    $('#audit-multiple').click(() => {
        usedIds = getIdsByRows($('#grid_id_user_manager').yiiGridView('getSelectedRows'));
    });

    $('.used-for').click((e) => {
        let id;
        if (e.target.dataset.uuid === undefined) {
            id = e.target.parentElement.dataset.uuid;
        } else {
            id = e.target.dataset.uuid;
        }

        usedIds = [id];
    });

    $('.search').click((e) => {
        e.stopPropagation();
    });
    $(".search").on('keypress', function (e) {
        e.stopPropagation();
        if (e.keyCode === 13) {
            search();
        }
    });

    $('.btn-search').click((e) => {
        e.stopPropagation();
        search();
    });

    function search() {
        let searches = [];
        for (let search of $('.search')) {
            let val = $(search).val();
            if (val !== '') {
                searches.push({
                    uuid: $(search).attr('name'),
                    value: val
                });
            }
        }
        if (searches.length > 0) {
            $('#search-json').val(JSON.stringify(searches));
            $('#search-form').submit();
        }
    }

    function getIdsByRows(rows) {
        let ids = [];
        for (let row of rows) {
            ids.push($('#grid_id_user_manager tr[data-key="' + row + '"] input[type="checkbox"]').val());
        }

        return ids;
    }

    $('#perPage').change((e) => {
        $('.loading').removeClass('hidden');
        window.location.replace(updateURLParameter(window.location.href, 'per-page', e.target.value));
    });

    $('#search-button').click(() => {
        $('.loading').removeClass('hidden');
        window.location.replace(updateURLParameter(window.location.href, 'search', $('#search-input').val()));
    });

    function getStartOrder() {
        if (getColNumber() !== -1) {
            let url = new URL(window.location.href);

            return [[getColNumber(), url.searchParams.get('sort-dir')]]
        }

        return [];
    }

    function getUuid(displayName, metadata) {
        for (let data of metadata.data) {
            if (data.object_type === 'type'
                && data.display_name === (displayName[1] !== undefined ? displayName[1] : displayName[0])) {
                if (displayName[1] !== undefined) {
                    if (metadata.display_name === displayName[0]) {
                        return data.uuid;
                    }
                } else {
                    return data.uuid;
                }
            } else if (data.object_type === 'set') {
                if (getUuid(displayName, data) !== false) {

                    return getUuid(displayName, data);
                }
            }
        }

        return false
    }

    function getColNumber() {
        let url = new URL(window.location.href);
        let sorted = url.searchParams.get('sort-by');
        let sortDir = url.searchParams.get('sort-dir');
        if (sorted !== null && sortDir !== null) {
            let sortedUuid = getUuid(sorted.split('-'), model);
            if (model.settings !== undefined) {

                return getNumberInObject(sortedUuid, model.settings) + 1;
            }

            return model.database.findIndex(object => object.uuid === sortedUuid) + 1;
        } else {

            return -1;
        }
    }

    function getNumberInObject(uuid, data) {
        let count = 0;
        for (let key in data) {
            if (key === uuid) {
                return count;
            }

            count++;
        }

        return -1;
    }

    function updateURLParameter(url, param, paramVal) {
        let TheAnchor = null;
        let newAdditionalURL = "";
        let tempArray = url.split("?");
        let baseURL = tempArray[0];
        let additionalURL = tempArray[1];
        let temp = "";

        if (additionalURL) {
            let tmpAnchor = additionalURL.split("#");
            let TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];
            if (TheAnchor)
                additionalURL = TheParams;

            tempArray = additionalURL.split("&");

            for (let i = 0; i < tempArray.length; i++) {
                if (tempArray[i].split('=')[0] != param) {
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        } else {
            let tmpAnchor = baseURL.split("#");
            let TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];

            if (TheParams)
                baseURL = TheParams;
        }

        if (TheAnchor)
            paramVal += "#" + TheAnchor;

        let rows_txt = temp + "" + param + "=" + paramVal;
        if (paramVal === '') {
            return baseURL + "?" + newAdditionalURL;
        } else {
            return baseURL + "?" + newAdditionalURL + rows_txt;
        }
    }

    $(".col_name").each(function (index, element) {
        let textSpan = $(element).text();
        $(".column_checkbox").each(function (index, element) {
            if (this.id == textSpan) {
                $(element).prop('checked', true);
            }
        });
    });

    $(".column_checkbox").each(function () {
        if ($('.column_checkbox:checked').length == $('.column_checkbox').length) {
            $('#checkAll').prop('checked', true);
        }
    });

    $("#checkAll").click(function () {
        $('input:checkbox.column_checkbox').not(this).prop('checked', this.checked);
    });

    $(".column_checkbox").change(function () {
        if ($('.column_checkbox:checked').length == $('.column_checkbox').length) {
            $('#checkAll').prop('checked', true);
        } else {
            $('#checkAll').prop('checked', false);
        }
    });

    setTimeout(() => {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    }, 100);
});
