$(document).ready(() => {
    const configuration = {
        locale: LOCALE,
        originKey: ORIGIN_KEY,
        loadingContext: LOADING_CONTEXT
    };

    const checkout = new AdyenCheckout(configuration);

    const sepa = checkout.create("sepadirectdebit", {
        onChange: handleChangeSepa
    }).mount('#sepa');

    const card = checkout.create("card", {
        onChange: handleChangeCard
    }).mount("#card");
});

let sepaDisabled = true;
let cardDisabled = true;

function handleChangeCard(state, component) {
    if (state.isValid & cardDisabled) {
        cardDisabled = false;
        $('#card-button').removeAttr('disabled');
        $('#card-state').val(JSON.stringify(state.data));
    } else if (!state.isValid && !cardDisabled) {
        cardDisabled = true;
        $('#card-button').attr('disabled', 'disabled');
    }
}

function handleChangeSepa(state, component) {
    if (state.isValid & sepaDisabled) {
        sepaDisabled = false;
        $('#sepa-button').removeAttr('disabled');
        $('#sepa-state').val(JSON.stringify(state.data));
    } else if (!state.isValid && !sepaDisabled) {
        sepaDisabled = true;
        $('#sepa-button').attr('disabled', 'disabled');
    }
}


