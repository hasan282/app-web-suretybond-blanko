$(function () {

    table_setup({
        option_button: '<a href="' + base_url(src_to) + '" class="btn btn-default btn-sm text-bold">Gunakan<i class="fas fa-share ml-2"></i></a>'
    });

    $('#asuransi').change(function () {
        blanko_action();
    });

    $('#btn_search').click(function () {
        $('#number_search').val($('#blanko_number').val());
        blanko_action();
    });

    $('.clear-filter').click(function () {
        const to_clear = $(this).data('clear').split(',');
        $.each(to_clear, function (key, val) {
            $('#' + val).val('');
        });
        blanko_action();
    });

});

function blanko_action() {
    const asuransi = $('#asuransi').val();
    const number = $('#number_search').val();
    let queries = '';
    if (asuransi != '') queries += '&asuransi=' + asuransi;
    if (number != '') queries += '&nomor=' + number;
    table_option({ query: queries });
}