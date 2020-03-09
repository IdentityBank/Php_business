passwordPolicy = JSON.parse(passwordPolicyJson);

const digitSet = '0123456789';
const lowerSet = 'zaqxswcdevfrbgtnhymjukilop';
const upperSet = 'ZAQXSWCDEVFRBGTNHYMJUKILOP';
let generated = false;

$('.password-button').click((e) => {
    if (generated) {
        generated = false;
        $('#idbbusinesssignupform-password').val('');
        $('#idbbusinesssignupform-repeatpassword').val('');

        $('#generate-password-container').slideUp(400, () => {
            $('#type-password-container').slideDown(400);
        });
    } else {
        generated = true;
        let password = generatePassword();
        $('#idbbusinesssignupform-password').val(password);
        $('#idbbusinesssignupform-repeatpassword').val(password);

        $('#text-generate-pass').html(password);

        $('#type-password-container').slideUp(400, () => {
            $('#generate-password-container').slideDown(400);
        });
    }
});


function generatePassword() {
    let password = '';
    password += generateFromSet(digitSet, passwordPolicy.digit);
    password += generateFromSet(passwordPolicy.special_chars_set, passwordPolicy.special);
    password += generateFromSet(lowerSet, passwordPolicy.lowercase);
    password += generateFromSet(upperSet, passwordPolicy.uppercase);
    if (parseInt(password.length) < parseInt(passwordPolicy.max_length)) {
        let minTmp = parseInt(password.length) < parseInt(passwordPolicy.min_length) ? passwordPolicy.min_length : password.length;
        if (minTmp < 64) minTmp = 64;
        let completeLength = Math.floor((Math.random() * (parseInt(passwordPolicy.max_length) - parseInt(minTmp))) + parseInt(minTmp));
        password += generateFromSet(
            passwordPolicy.special_chars_set + digitSet + lowerSet + upperSet,
            completeLength - password.length
        );
    }

    return password.split('').sort(function () {
        return 0.5 - Math.random()
    }).join('');
}

function generateFromSet(set, length) {
    let text = '';
    for (let i = 0; i < length; i++) {
        text += set[Math.floor((Math.random() * set.length))];
    }

    return text;
}
