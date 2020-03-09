/**
 * Function to move element in array.
 *
 * @param array
 * @param oldIndex
 * @param newIndex
 * @returns {*}
 */
function move(array, oldIndex, newIndex) {
    while (oldIndex < 0) {
        oldIndex += array.length;
    }
    while (newIndex < 0) {
        newIndex += array.length;
    }
    if (newIndex >= array.length) {
        let k = newIndex - array.length;
        while ((k--) + 1) {
            array.push(undefined);
        }
    }
    array.splice(newIndex, 0, array.splice(oldIndex, 1)[0]);
    return array;
}