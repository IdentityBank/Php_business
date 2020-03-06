let table;
$(document).ready(() => {

    if (afterSearch !== false) {
        for (let s of afterSearch) {
            $('input[name="' + s.uuid + '"').val(s.value);
        }
    }

    table = $('#table_id_user_manager').DataTable({
        'paging': false,
        'lengthChange': false,
        'searching': false,
        'ordering': false,
        scrollY: '70vh',
        scrollCollapse: true,
        'info': false,
        'autoWidth': false,
        'scrollX': true,
    });

    $('.search').click((e) => {
        e.stopPropagation();
    });
    $(".search").on('keypress', function (e) {
        e.stopPropagation();
        if (e.keyCode === 13) {
            console.log('here');
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
            console.log(searches);
            if (searches.length > 0) {
                $('#search-json').val(JSON.stringify(searches));
                $('#search-form').submit();
            }
        }
    });

    $('#perPage').change((e) => {
        $('.loading').removeClass('hidden');
        window.location.replace(updateURLParameter(window.location.href, 'per-page', e.target.value));
    });

    $('#search-button').click(() => {
        $('.loading').removeClass('hidden');
        window.location.replace(updateURLParameter(window.location.href, 'search', $('#search-input').val()));
    });

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
});