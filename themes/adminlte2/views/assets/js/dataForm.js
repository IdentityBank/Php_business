(() => {
    let firstSend = false;
    let model;
    let completeData = {};

    $(document).on('change', '.data-input', (e) => {
        let uuid = e.target.dataset.uuid;
        completeData[uuid] = e.target.value;

        if ($('input:invalid').length < 1) {
            $('.btn-create').removeAttr('disabled');
        }
    });

    $.get(getModelURL).done((data) => {
        model = data;
        if (typeof model === 'string') {
            model = JSON.parse(model);
        }

        rerenderForm();

        $('.loading').addClass('hidden');

    });

    $('.btn-create').click(e => {
        firstSend = true;
        if ($('input:invalid').length > 0) {
            $('#idb-data-create').addClass('show-invalid');
            $('.btn-create').attr('disabled', 'disabled');
            $('.danger-message').text(emptyRequiredMessage);

            $('html').animate({scrollTop: 0}, 400);
            $('.alert-danger').slideDown(400);
        } else {
            $('.loading').removeClass('hidden');
            $.ajax({
                type: 'POST',
                url: createURL,
                data: completeData,
            }).done(data => {
                data = JSON.parse(data);

                if (data.success !== undefined && data.success) {
                    window.location.replace(showAllURL);
                } else {
                    $('.loading').addClass('hidden');
                    if (data.message !== undefined) {
                        $('#dangerMessage').text(data.message);
                    } else {
                        $('#dangerMessage').text(errorMessage);
                    }
                    $('html').animate({scrollTop: 0}, 400);
                    $('.alert-danger').slideDown(400);
                }
            });
        }
    });

    function renderSet(set) {
        let html = '<div class="append-data-set">';
        html += '<h4>' + set.display_name + ':</h4>';

        for (let data of set.data) {
            if (data.object_type === 'type') {
                let requiredSpan = '';
                if (data.required === 'true') {
                    requiredSpan = '<span class="required">*</span>'
                }

                html += '<label>' + data.display_name + requiredSpan + '</label>';
                html += '<input class="data-input form-control append-data-input" ' + (data.required === 'true' ? 'required ' : '') + 'data-uuid="' + data.uuid + '" type="text" />';

            } else if (data.object_type === 'set') {
                html += renderSet(data);
            }
        }

        html += '</div>';

        return html;
    }

    function rerenderForm() {
        $('#idb-data-create').html(renderSet(model));
    }
})();
