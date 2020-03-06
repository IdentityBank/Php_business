/**
 * Recursive function to remove Set or Type from model(by uuid).
 *
 * @param uuid
 * @param model
 * @returns {boolean}
 */
function removeFromModel(uuid, model) {
    for (let object of model.data) {
        if (object.uuid === uuid) {
            if (confirm('Are you sure you want to delete this item?')) {
                let index = model.data.findIndex(o => o.uuid === uuid);
                model.data.splice(index, 1);
            }
            return true;
        } else if (object.object_type === 'set') {
            if (removeFromModel(uuid, object) !== false) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Recursive function to remove Set or Type from model(by uuid).
 *
 * @param uuid
 * @param key
 * @param value
 * @param model
 * @returns {boolean}
 */
function editValueInModel(uuid, key, value, model) {
    if (model.uuid === uuid) {
        model[key] = value;
        return true;
    }
    for (let object of model.data) {
        if (object.uuid === uuid) {
            object[key] = value + '';
            return true;
        } else if (object.object_type === 'set') {
            if (editValueInModel(uuid, key, value, object)) {
                return true;
            }
        }
    }
}

/**
 * Recursive Function to search set in model(by uuid).
 *
 * @param uuid
 * @param model
 * @returns {*|boolean|boolean}
 */
function searchSet(uuid, model) {
    if (model.uuid === uuid) {
        return model;
    }

    for (let data of model.data) {
        if (data.object_type === 'set') {
            if (searchSet(uuid, data) !== false) {
                return searchSet(uuid, data);
            }

        }

    }

    return false;
}

/**
 * Recursive function to find all columns in metadata.
 *
 * @param model
 * @returns {[]}
 */
function findColumns(model) {
    let dataTypes = [];

    for (let object of model.data) {
        if (object.object_type === 'type') {
            dataTypes.push({
                uuid: object.uuid,
                title: object.display_name,
                order: object.order,
                on: true,
                type: object.data_type
            });
        } else if (object.object_type === 'set') {
            dataTypes = dataTypes.concat(findColumns(object));
        }
    }

    return dataTypes;
}

/**
 * Recursive function to add object to data node.
 *
 * @param uuid
 * @param model
 * @param data
 * @returns {boolean}
 */
function addToDom(uuid, model, data) {
    data['order'] = model.data.length + 1;

    if (model.uuid === uuid) {
        model.data.push(data);

        return true;
    }

    for (let object of model.data) {
        if (object.object_type === 'set') {
            if (object.uuid === uuid) {
                object.data.push(data);
            } else {
                if (addToDom(uuid, object, data) !== false) {
                    return true;
                }
            }
        }
    }
    return false;
}

/**
 * Recursive function to add object to data node.
 *
 * @param uuid
 * @param model
 * @param data
 * @returns {boolean}
 */
function getDataType(uuid, model) {
    for (let object of model.data) {
        if (object.object_type === 'set') {
            if(getDataType(uuid, object) !== false) {
                return getDataType(uuid, object);
            }
        } else if(object.object_type === 'type') {
            if(object.uuid === uuid) {
                return object;
            }
        }
    }

    return false;
}

/**
 * Recursive function to find all datatype, return format {uuid, type}.
 *
 * @param model
 * @returns {[]}
 */
function findDataTypes(model) {
    let dataTypes = [];

    if (model.data === undefined) {
        model.data = [];
    }

    for (let object of model.data) {
        if (object.object_type === 'type') {
            dataTypes.push({
                uuid: object.uuid,
                type: object.data_type
            });
        } else if (object.object_type === 'set') {
            dataTypes = dataTypes.concat(findDataTypes(object));
        }
    }
    return dataTypes;
}

/**
 * Add Edit node to metadata.
 *
 * @param model
 * @param oldDatabase
 */
function addEditNode(model, oldDatabase) {
    let change = {};
    for (let column of oldDatabase) {
        if (!model.database.some(object => object.uuid === column.uuid)) {
            if (change.drop === undefined) {
                change.drop = [];
            }
            change.drop.push({
                uuid: column.uuid,
                type: 'string'
            });
        }
    }

    for (let column of model.database) {
        if (!oldDatabase.some(object => object.uuid === column.uuid)) {
            if (change.add === undefined) {
                change.add = [];
            }
            change.add.push({
                uuid: column.uuid,
                type: 'string'
            });
        }
    }

    model.edit = change;
}
