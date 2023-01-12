$(function () {

    number_only('.blankorange');
    setup_datepicker('#tanggal');

    $('#prefix').keyup(function () {
        $('.prefixshow').html($(this).val());
    });

    $('.blankorange').on('keyup', function () {
        set_total();
    });

});

function set_total() {
    const val_from = $('#number_from').val();
    const val_to = $('#number_to').val();
    let range = (val_from.length === val_to.length) ? val_to - val_from : 0;
    $('[type="submit"]').attr('disabled', (range <= 0));
    $('#total_blanko').val((range > 0) ? range + 1 : 0);
}