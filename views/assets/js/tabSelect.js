$('a').click(function () {
    let name = $(this).text();
    if (name === 'Other') {
        $('#page-header-normal').hide();
        $('#page-header-other').show();
    } else {
        $('#page-header-normal').show();
        $('#page-header-other').hide();
    }
});
