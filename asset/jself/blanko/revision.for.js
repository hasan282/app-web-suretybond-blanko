const officeid = $('#var_value').val();
$(function () {
    table_setup({
        url: base_url('api/data'),
        query: '&get=usedfrom&office=' + officeid,
        table_header: [
            { title: 'Asuransi', center: false },
            { title: 'Nomor Blanko', center: true },
            { title: 'Principal', center: false },
            { title: 'Obligee', center: false },
        ],
        table_row: '<td>$asuransi</td><td class="text-center"><span class="text-secondary">$prefix</span><b>$nomor</b></td><td>$principal</td><td>$obligee</td>',
        option_button: '<a href="' + base_url('uses/rev/$enkrip?var=' + officeid) + '" class="btn btn-secondary btn-sm text-bold">Revisi<i class="fas fa-sync-alt ml-2"></i></a><a href="' + base_url('blanko/detail/$enkrip') + '" class="btn btn-info ml-2 btn-sm text-bold"><i class="fas fa-info-circle"></i></a>'
    });

    $('#asuransi').change(function () {
        blanko_action();
    });

    $('#principal').change(function () {
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
    const principal = $('#principal').val();
    let queries = '&get=usedfrom&office=' + officeid;
    if (asuransi != '') queries += '&asuransi=' + asuransi;
    if (number != '') queries += '&nomor=' + number;
    if (principal != '') queries += '&principal=' + principal;
    table_option({ query: queries });
}