$(function () {

    table_setup({
        url: base_url('api/data'),
        query: '&get=search',
        table_header: [
            { title: 'Asuransi', center: false },
            { title: 'Nomor', center: true },
            { title: 'Status', center: true },
            { title: 'Principal', center: false },
            { title: 'Pengguna', center: true },
            { title: 'Produksi', center: true }
        ],
        table_row: '<td class="text-bold">$asuransi_nick</td><td class="text-center"><span class="text-secondary">$prefix</span><b>$nomor</b></td><td class="text-center text-bold text-$color">$status</td><td>$principal</td><td class="text-center text-bold">$office_nick</td><td class="text-center">$produksi</td>',
        option_button: '<a href="' + base_url('blanko/detail/$enkripsi') + '" class="btn btn-info btn-sm text-bold"><i class="fas fa-info-circle mr-2"></i>Detail</a><a href="' + base_url('blanko/detail/$enkripsi') + '" target="_blank" class="btn btn-default ml-1 btn-sm text-bold"><i class="fas fa-external-link-alt"></i></a>'
    });

    $('#searchnumber').on('search', function () {
        blanko_action();
    });

    $('#searchbutton').on('click', function () {
        blanko_action();
    });

});

function blanko_action() {
    const number = $('#searchnumber').val();
    let queries = '&get=search';
    if (number != '') queries += '&nomor=' + number;
    table_option({ query: queries });
}