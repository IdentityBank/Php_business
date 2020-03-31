/**
 * Self invoke function because of security. We don't have access through browser console now.
 */
(() => {
    let model;
    let oldDatabase;
    let setUuid = '';
    let oldData = '';
    let mainUuid = '';
    let dpoType;
    let retentionType;
    let lawfulType;
    /**
     * Ajax get model, security reason.
     */
    $.get(getModelURL).done((data) => {
        model = data;
        if (typeof model === 'string') {
            model = JSON.parse(model);
        }

        if (model.uuid !== undefined) {
            mainUuid = model.uuid;
        }
        oldData = _.cloneDeep(model.data);
        if (oldData === undefined) {
            oldData = [];
        }
        oldDatabase = _.cloneDeep(model.database);
        if (oldDatabase === undefined) {
            oldDatabase = [];
        }
        if (model.data === undefined) {
            model.data = [];
            model.display_name = '';
            model.uuid = generateUuid();
        }
        if (model.used_for === undefined) {
            model.used_for = '';
        }
        rerenderCreate();
        $('#creator-container').removeClass('hidden');
        $('.loading').hide();
    });
    $(document).on('click', '.button--add-set', e => {
        addSet(e.target.dataset.uuid);
    });
    $(document).on('click', '.button--add-type', e => {
        addType(e.target.dataset.uuid);
    });
    $(document).on('click', '.creator-remove', e => {
        removeElement(e.target.dataset.uuid);
        if ($('input:invalid').length === 0 && $('.btn-create').attr('disabled') !== undefined) {
            $('.btn-create').removeAttr('disabled');
        }
    });
    $(document).on('click', '.button--add-predefined-type', e => {
        setUuid = e.target.dataset.uuid;
        $('#types-modal').modal();
    });
    $(document).on('click', '.button--add-predefined-set', e => {
        setUuid = e.target.dataset.uuid;
        $('#sets-modal').modal();
    });
    $('#add-selected-type').click(e => {
        let index = $('#selected-type').val();
        addToDom(setUuid, model, types[index]);
        rerenderCreate();
    });
    $('#add-selected-set').click(e => {
        let index = $('#selected-set').val();
        addToDom(setUuid, model, sets[index]);
        rerenderCreate();
    });

    $(document).on('change', '.data-creator-set input, .data-creator-set select', e => {
        if (e.target.type !== 'checkbox') {
            editValue(e.target.dataset.uuid, e.target.dataset.key, e.target.value)
        } else {
            editValue(e.target.dataset.uuid, e.target.dataset.key, e.target.checked)
        }
        if ($('input:invalid').length === 0 && $('.btn-create').attr('disabled') !== undefined) {
            $('.btn-create').removeAttr('disabled');
        }
    });
    $('.btn-create').click(e => {
        addDatabaseNode();
        addColumnsNode();
        if ($('input:invalid').length > 0) {
            $('#data-client-create').addClass('show-invalid');
            $('.btn-create').attr('disabled', 'disabled');

            $('.danger-message').text(emptyDisplayNameMessage);

            $('html').animate({scrollTop: 0}, 400);
            $('.alert-danger').slideDown(400);
        } else {
            addEditNode(model, oldDatabase);

            let diff = getCleanDeepDiff(oldData, model.data);

            let data = {
                diff,
                edit: model.edit,
                database: model.database,
                columns: model.columns,
                display_name: model.display_name,
                used_for: model.used_for,
                uuid: model.uuid
            };
            $.ajax({
                type: 'POST',
                url: createURL,
                data: data,
            }).done(data => {
                if (data) {
                    window.location.replace(showAllURL);
                }
            });
            $('.loading').show();
            let time = setTimeout(() => {
                $('.loading').hide();
                alert(somethingWrongMessage);
            }, 30000);
        }
    });

    function removeElement(uuid) {
        removeFromModel(uuid, model);
        rerenderCreate();
    }

    function editValue(uuid, key, value) {
        editValueInModel(uuid, key, value, model);
    }

    function addSet(uuid) {
        addToDom(uuid, model, {
            uuid: generateUuid(),
            data: [],
            used_for: '',
            required: 1,
            object_type: 'set',
            display_name: 'test'
        });
        rerenderCreate();
    }

    function addType(uuid) {
        addToDom(uuid, model, {
            uuid: generateUuid(),
            internal_name: '',
            display_name: '',
            data_type: 'string',
            searchable: 1,
            sortable: 1,
            sensitive: 1,
            pseudonymisation: 0,
            tag: '',
            used_for: 'us',
            required: 1,
            object_type: 'type',
        });
        rerenderCreate();
    }

    /**
     * Recursive Function to render set.
     */
    function renderSet(model) {
        let html = '<div class="data-creator-set">';
        html += '<div data-uuid="' + model.uuid + '" class="creator-remove glyphicon glyphicon-trash"></div>';
        html += '<div class="set-header">';
        if (model.order !== undefined) {
            html += '<span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>';
        }
        let req = 'required';

        if (model.uuid === mainUuid) {
            req = '';
        }

        html += '<label class="display-name">' + displayNameMessage + ': </label><input ' + req + ' data-key="display_name" class="form-control header-creator-input" data-uuid="' + model.uuid + '" type="text" name="set" value="' + model.display_name + '"/>';
        html += '<button aria-expanded="true" aria-controls="toggle-' + model.uuid + '" class="toggle-button" data-toggle="collapse" data-target="#toggle-' + model.uuid + '"><span class="glyphicon glyphicon-collapse-up"></span></button>';
        html += '</div>';
        html += '<div class="collapse in sortable" id="toggle-' + model.uuid + '">';
        const isIterable = object =>
            object != null && typeof object[Symbol.iterator] === 'function';
        if (isIterable(model.data)) {
            for (let data of model.data) {
                if (data.object_type === 'type') {
                    if (data.tag === null) {
                        data.tag = '';
                    }
                    let required = false;
                    if (data.required !== undefined) {
                        if (data.required === 1 || data.required == '1') {
                            data.required = 'true';
                        }
                        required = (data.required === 'true');
                    }
                    let sensitive = false;
                    if (data.sensitive !== undefined) {
                        if (data.sensitive === 1) {
                            data.sensitive = 'true';
                        }
                        sensitive = (data.sensitive === 'true');
                    }

                    let pseudonymisation = false;
                    if (data.pseudonymisation !== undefined) {
                        if (data.pseudonymisation === 1) {
                            data.pseudonymisation = 'true';
                        }
                        pseudonymisation = (data.pseudonymisation === 'true');
                    }

                    if (data.category === undefined) {
                        data.category = 'normal';
                    }
                    let category = data.category;

                    html += '<div class="data-creator--type">';

                    html += '<span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>';
                    html += '<div style="color: rgb(51, 51, 51);" data-uuid="' + data.uuid + '" class="creator-retention fa fa-user-clock"></div>';
                    html += '<div style="color: rgb(51, 51, 51);" data-uuid="' + data.uuid + '" class="creator-lawful fa fa-user-shield"></div>';
                    html += '<div style="color: rgb(51, 51, 51);" data-uuid="' + data.uuid + '" class="creator-processors fa fa-user-tie"></div>';
                    html += '<div style="color: rgb(51, 51, 51);" data-uuid="' + data.uuid + '" class="creator-remove glyphicon glyphicon-trash"></div>';
                    html += '<label class="display-name">' + displayNameMessage + ':</label>';
                    html += '<label>' + requiredMessage + ': </label><span><input type="checkbox" data-key="required" data-uuid="' + data.uuid + '" name="required" ' + (required ? 'checked="true"' : '') + '"/></span>';
                    html += '<label>' + sensitiveMessage + ': </label><span><input type="checkbox" data-key="sensitive" data-uuid="' + data.uuid + '" name="sensitive" ' + (sensitive ? 'checked="true"' : '') + '"/></span>';
                    html += '<label>' + pseudonymisationMessage + ': </label><span><input type="checkbox" data-key="pseudonymisation" data-uuid="' + data.uuid + '" name="pseudonymisation" ' + (pseudonymisation ? 'checked="true"' : '') + '"/></span>';
                    html += '<label>' + dataCategoryMessage + ': </label><span>' +
                        '<select data-key="category" data-uuid="' + data.uuid + '" name="category">';
                    html += '<option value="normal" ' + (category === "normal" ? 'selected="selected"' : '') + '>' + normalCategoryMessage + '</option>';
                    html += '<option value="health" ' + (category === "health" ? 'selected="selected"' : '') + '>' + healthCategoryMessage + '</option>';
                    html += '<option value="special" ' + (category === "special" ? 'selected="selected"' : '') + '>' + specialCategoryMessage + '</option>';
                    html += '</select></span>';
                    html += '<input class="form-control creator-input" required type="text" data-key="display_name" data-uuid="' + data.uuid + '" name="type" value="' + data.display_name + '" />';

                    html += '</div>';
                } else if (data.object_type === 'set') {
                    html += renderSet(data);
                }
            }
        }

        if (extendedClientSetsCreator) {
            html += '<div class="data-creator-buton-container">';
            html += '<button class="btn btn-app-blue button--add-type btn-own-orange" data-uuid="' + model.uuid + '">' + addTypeMessage + '</button>';
            html += '<button class="btn btn-app-blue button--add-predefined-type btn-predefined btn-own-orange" data-uuid="' + model.uuid + '">' + addPredefinedType + '</button>';
            html += '<button class="btn btn-adn button--add-set btn-own-orange" data-uuid="' + model.uuid + '">' + addSetMessage + '</button>';
            html += '<button class="btn btn-adn button--add-predefined-set btn-predefined btn-own-orange" data-uuid="' + model.uuid + '">' + addPredefinedSet + '</button>';
            html += '</div>';
        }
        html += '</div>';
        html += '</div>';
        return html;
    }

    function sort(data) {
        for (let i = 0; i < data.data.length; i++) {
            data.data[i].order = i + 1;
        }
    }

    function addDatabaseNode() {
        model.database = findDataTypes(model);
    }

    function addColumnsNode() {
        model.columns = findColumns(model);
    }

    function searchModelToSort(uuid, model, oldIndex, newIndex) {
        if (model.uuid === uuid) {
            move(model.data, oldIndex, newIndex);
            sort(model);
            rerenderCreate();
            return true;
        }
        for (let data of model.data) {
            if (data.object_type === 'set') {
                if (searchModelToSort(uuid, data, oldIndex, newIndex)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Function to rerender creator form.
     */
    function rerenderCreate() {
        $('#data-client-create').html(renderSet(model));
        addDatabaseNode();
        addColumnsNode();
        for (let object of document.querySelectorAll('.sortable')) {
            Sortable.create(object, {
                draggable: '.data-creator--type, .data-creator-set',
                animation: 150,
                group: 'nested',
                fallbackOnBody: true,
                handle: '.ui-sortable-handle',
                onEnd: (evt) => {
                    if (evt.from.id !== evt.to.id) {
                        let from = searchSet(evt.from.id.split('toggle-')[1], model);
                        let data;
                        if (from !== false) {
                            data = from.data[evt.oldIndex];
                            from.data.splice(evt.oldIndex, 1);
                            sort(from);
                        }
                        let to = searchSet(evt.to.id.split('toggle-')[1], model);
                        if (to !== false) {
                            to.data.splice(evt.newIndex, 0, data);
                            sort(to);
                            rerenderCreate();
                        }
                    } else {
                        searchModelToSort(evt.to.id.split('toggle-')[1], model, evt.oldIndex, evt.newIndex);
                    }
                }
            });
        }
    }

    $('body').on('click', '.creator-processors', e => {
        dpoType = getDataType(e.target.dataset.uuid, model);
        if (dpoType.gdpr !== undefined && dpoType.gdpr.listDataProcessors !== undefined) {
            let html = '';
            for (let dpo of dpoType.gdpr.listDataProcessors) {
                html += `
                    <div class="dpo-div">
                        <textarea style="margin-bottom: 20px" class="form-control dpo-area" name="dpo[]" rows="3">${dpo}</textarea>
                        <span class="glyphicon glyphicon-trash dpo-remove"></span>
                    </div>
                `;
            }
            $('#dpo-container').html(html);
        }
        $('#processors-modal').modal();
    });

    $('#add-dpo').click(e => {
        $('#dpo-container').append(`
              <div class="dpo-div">
                <textarea style="margin-bottom: 20px" class="form-control dpo-area" name="dpo[]" rows="3"></textarea>
                <span class="glyphicon glyphicon-trash dpo-remove"></span>
            </div>
        `);
    });

    $('body').on('click', '.dpo-remove', e => {
        e.target.closest('.dpo-div').remove();
    });

    $('#processors-set').click(e => {
        let dpos = [];
        for (let textarea of $('.dpo-area')) {
            dpos.push($(textarea).val());
        }

        if (dpoType.gdpr === undefined) {
            spoType.gdpr = {};
        }

        dpoType.gdpr.listDataProcessors = dpos;
        $('#processors-modal').modal('hide');

    });

    $('body').on('click', '.creator-retention', e => {
        clearAll();
        retentionType = getDataType(e.target.dataset.uuid, model);

        if (retentionType.gdpr !== undefined) {
            if (retentionType.gdpr.maximum !== undefined && !isNaN(parseInt(retentionType.gdpr.maximum))) {
                $('#businessretentionperiodform-maximum').val(retentionType.gdpr.maximum).change();
                $('.after-maximum').slideDown();
            }
            if (retentionType.gdpr.minimum !== undefined && !isNaN(parseInt(retentionType.gdpr.minimum))) {
                $('#businessretentionperiodform-minimum').val(retentionType.gdpr.minimum).change();
            }
            if (retentionType.gdpr.onExpiry !== undefined) {
                $('#businessretentionperiodform-onexpiry').val(retentionType.gdpr.onExpiry).change();
            }

            if (retentionType.gdpr.reviewCycle !== undefined && !isNaN(parseInt(retentionType.gdpr.reviewCycle))) {
                $('#businessretentionperiodform-reviewcycle').val(retentionType.gdpr.reviewCycle).change();
                $('.after-review').slideDown();
            }

            if (retentionType.gdpr.explanation !== undefined) {
                $('#businessretentionperiodform-explanation').val(retentionType.gdpr.explanation).change();
            }
        }

        $('#period-modal').modal();
    });


    const boxBody = $('body');
    boxBody.on('input', '#businessretentionperiodform-maximum', e => {
        if (!isNaN(parseInt(e.target.value))) {
            $('.after-maximum').slideDown();
            if (!isNaN(parseInt($('#businessretentionperiodform-reviewcycle').val()))) {
                $('.after-review').slideDown();
            }
        } else {
            $('.after-maximum').slideUp();
            $('.after-review').slideUp();
            clearAll();
        }
    });

    boxBody.on('input', '#businessretentionperiodform-reviewcycle', e => {
        if (!isNaN(parseInt(e.target.value))) {
            $('.after-review').slideDown();
        } else {
            $('.after-review').slideUp();
            clearExplanation();
        }
    });

    function clearAll() {
        $('#businessretentionperiodform-minimum').val(null).change();
        $('#businessretentionperiodform-reviewcycle').val(null).change();
        clearExplanation();
        $('.after-maximum').slideUp();
        $('.after-review').slideUp();
    }

    function clearExplanation() {
        $('#businessretentionperiodform-explanation').val(null).change();
    }

    $('#period-form').submit(e => {
        e.preventDefault();
    });

    $('#period-set').click(e => {
        if ($('#period-form').find('.has-error').length) {
            // block
        } else {

            if (retentionType.gdpr === undefined) {
                retentionType.gdpr = {};
            } else {
                if (retentionType.gdpr.maximum !== undefined) {
                    delete retentionType.gdpr.maximum;
                }
                if (retentionType.gdpr.minimum !== undefined) {
                    delete retentionType.gdpr.minimum;
                }
                if (retentionType.gdpr.reviewCycle !== undefined) {
                    delete retentionType.gdpr.reviewCycle;
                }
                if (retentionType.gdpr.explanation !== undefined) {
                    delete retentionType.gdpr.explanation;
                }
                if (retentionType.gdpr.onExpiry !== undefined) {
                    delete retentionType.gdpr.onExpiry;
                }
            }

            if (!isNaN(parseInt($('#businessretentionperiodform-maximum').val()))) {
                retentionType.gdpr.maximum = parseInt($('#businessretentionperiodform-maximum').val());


                if (!isNaN(parseInt($('#businessretentionperiodform-minimum').val()))) {
                    retentionType.gdpr.minimum = parseInt($('#businessretentionperiodform-minimum').val());
                }

                if (!isNaN(parseInt($('#businessretentionperiodform-reviewcycle').val()))) {
                    retentionType.gdpr.reviewCycle = parseInt($('#businessretentionperiodform-reviewcycle').val());


                    if ($('#businessretentionperiodform-explanation').val() !== '') {
                        retentionType.gdpr.explanation = $('#businessretentionperiodform-explanation').val();
                    }
                }
            }
            retentionType.gdpr.onExpiry = $('#businessretentionperiodform-onexpiry').val();

            $('#period-modal').modal('hide');
        }
    });

    $('body').on('click', '.creator-lawful', e => {
        lawfulType = getDataType(e.target.dataset.uuid, model);
        if (lawfulType.gdpr.purposeLimitation !== undefined) {
            $('#dynamicmodel-purposelimitation').val(lawfulType.gdpr.purposeLimitation).change();
        }

        if (lawfulType.gdpr.lawfulMessage !== undefined) {
            $('#dynamicmodel-message').val(lawfulType.gdpr.lawfulMessage).change();
            $('#dynamicmodel-messages').val(lawfulType.gdpr.lawfulMessage);
        }

        if (lawfulType.gdpr.lawfulBasis !== undefined) {
            $('#dynamicmodel-legal').val(lawfulType.gdpr.lawfulBasis).change();
        }

        $('#lawful-modal').modal();
    });

    $('#dynamicmodel-messages').change(e => {
        $('#dynamicmodel-message').val(e.target.value);
    });

    $('#lawful-set').click(e => {
        if ($('#lawful-form').find('.has-error').length) {
            //block;
        } else {
            if (lawfulType.gdpr === undefined) {
                lawfulType.gdpr = {};
            }
            lawfulType.gdpr.purposeLimitation = $('#dynamicmodel-purposelimitation').val();
            lawfulType.gdpr.lawfulMessage = $('#dynamicmodel-message').val();
            lawfulType.gdpr.lawfulBasis = $('#dynamicmodel-legal').val();
        }

        $('#lawful-modal').modal('hide');
    });
})();
