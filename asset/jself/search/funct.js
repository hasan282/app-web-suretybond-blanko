let initial_setup = false;
$(function () {

    $('#searchnumber').on('search', function () {
        if ($(this).val() != '') blanko_action();
    });

    $('#searchbutton').on('click', function () {
        if ($('#searchnumber').val() != '') blanko_action();
    });

    $('[name="dataview"]').change(function () {
        if (initial_setup) table_option({ limit: parseInt($(this).val()) });
    });

});

function blanko_action() {
    const number = $('#searchnumber').val();
    let queries = '&get=search';
    if (number != '') queries += '&nomor=' + number;
    if (initial_setup) {
        table_option({ query: queries });
    } else {
        init_action(queries);
    }
}

function init_action(queries) {
    initial_setup = true;
    const limits = parseInt($('[name="dataview"]:checked').val());
    table_setup({
        url: base_url('api/data'),
        query: queries,
        table_header: [
            { title: 'Asuransi', center: false },
            { title: 'Nomor', center: true },
            { title: 'Status', center: true },
            { title: 'Principal', center: false },
            { title: 'Pengguna', center: true },
            { title: 'Produksi', center: true }
        ],
        limit: (limits > 10) ? limits : 10,
        table_row: '<td class="text-bold">$asuransi_nick</td><td class="text-center border-right"><span class="text-secondary">$prefix</span><b>$nomor</b></td><td class="text-center text-bold text-$color">$status</td><td class="border-right">$principal</td><td class="text-center text-bold">$office_nick</td><td class="text-center border-left border-right">$produksi</td>',
        option_button: '<a href="' + base_url('blanko/detail/$enkripsi') + '" class="btn btn-info btn-sm text-bold"><i class="fas fa-info-circle mr-2"></i>Detail</a><a href="' + base_url('blanko/detail/$enkripsi') + '" target="_blank" class="btn btn-default ml-1 btn-sm text-bold"><i class="fas fa-external-link-alt"></i></a>'
    });
}