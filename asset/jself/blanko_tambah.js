$(function () {

    setup_datepicker('#tanggal');
    input_regex_only('#numbers', /[,0-9]/);

    $('#numbers').keyup(function () {
        const vals = $(this).val();
        const disable_btn = (vals == '' || vals.endsWith(',') || vals.startsWith(',') || vals.includes(',,'));
        $('[type="submit"]').attr('disabled', disable_btn);
        fill_total(vals, disable_btn);
    });

});

function fill_total(vals, zeroinput = false) {
    const sizeof = vals.split(',').length;
    $('#total_blanko').val((zeroinput) ? 0 : sizeof);
}