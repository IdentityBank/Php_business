/**
 * Generate uuid using crypto.
 *
 * @returns {string}
 */
function generateUuid() {
    function randomDigit() {
        if (crypto && crypto.getRandomValues) {
            let rands = new Uint8Array(1);
            crypto.getRandomValues(rands);
            return (rands[0] % 16).toString(16);
        } else {
            return ((Math.random() * 16) | 0).toString(16);
        }
    }

    let crypto = window.crypto || window.msCrypto;
    return 'xxxxxxxx-xxxx-4xxx-8xxx-xxxxxxxxxxxx'.replace(/x/g, randomDigit);
}