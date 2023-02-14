form_values.crash = false;

$(function () {

    $('#keterangan').on('keyup', function () {
        form_values.crash = ($(this).val() != '');
        enable_submit_button();
    });

});