let initial_setup = false;
let filter_values = { asuransi: false, office: false, range: false, status: false, laprod: false, tipe: false };
$(function () {

    checklist_setup({
        changecolor: true,
        enablesubmit: true,
        submit: '#submitexport'
    });

    trigger_click_id([
        'check_asuransi', 'check_fullnumber',
        'check_status', 'check_principal',
        'check_office'
    ]);

    $('#export_open').click(function () {
        $('#export_card').removeClass('mw-300').addClass('mw-600');
        $('#box_open').addClass('zero-height');
        $('#box_process').removeClass('zero-height');
    });
    $('#export_close').click(function () {
        $('#export_card').removeClass('mw-600').addClass('mw-300');
        $('#box_process').addClass('zero-height');
        $('#box_open').removeClass('zero-height');
    });

    $('#searchnumber').on('search', function () {
        const allempty = ($('input[type="hidden"]').filter(function () {
            return ($.trim($(this).val()).length > 0);
        }).length === 0);
        if ($(this).val() != '' || !allempty) blanko_action();
    });

    $('#searchbutton').on('click', function () {
        if ($('#searchnumber').val() != '') blanko_action();
    });

    $('[name="dataview"]').change(function () {
        if (initial_setup) table_option({ limit: parseInt($(this).val()) });
    });

    $('.filterselect').on('change', function () {
        const elementid = $(this).attr('id');
        const elementval = $(this).val();
        filter_values[elementid] = (elementval != '');
        enable_button();
    });

    $('#clearall').click(function () {
        $('.filterselect').each(function () {
            $(this).val('');
        });
        $('.numrange').each(function () {
            $(this).val('');
        });
        $('input[type="hidden"]').each(function () {
            $(this).val('');
        });
        Object.keys(filter_values).forEach(k => filter_values[k] = false);
        enable_button();
    });

    $('#filterbutton').click(function () {
        $('.filterselect').each(function () {
            const elementid = $(this).attr('id');
            const elementval = $(this).val();
            $('#val_' + elementid).val(elementval);
        });
        $('#val_range').val(number_range());
        $('#searchnumber').val('');
        blanko_action();
    });

    $('.numrange').on('keyup', function () {
        filter_values.range = (number_range() != '');
        enable_button();
    });

});

function blanko_action() {
    const number = $('#searchnumber').val();
    const asuransi = $('#val_asuransi').val();
    const agent = $('#val_office').val();
    const stats = $('#val_status').val();
    const produksi = $('#val_laprod').val();
    const tipe = $('#val_tipe').val();
    const range = $('#val_range').val();
    let queries = '&get=search';
    if (number != '') queries += '&nomor=' + number;
    if (asuransi != '') queries += '&asuransi=' + asuransi;
    if (agent != '') queries += '&office=' + agent;
    if (stats != '') queries += '&status=' + stats;
    if (produksi != '') queries += '&produksi=' + produksi;
    if (tipe != '') queries += '&tipe=' + tipe;
    if (range != '') queries += '&range=' + range;
    if (initial_setup) {
        table_option({ query: queries });
    } else {
        init_action(queries);
    }
    $('#export_close').trigger('click');
}

function number_range() {
    const valfrom = $('#rangefrom').val();
    const valto = $('#rangeto').val();
    return (valfrom != '' && valto != '') ? valfrom + '-' + valto : '';
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

function enable_button() {
    const allfalse = Object.values(filter_values).every(v => v === false);
    $('#filterbutton').attr('disabled', allfalse);
}