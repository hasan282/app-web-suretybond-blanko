$(function () {

    $('#loading').fadeOut(100, function () {
        $(this).html('<i class="fas fa-2x fa-spinner fa-pulse text-secondary"></i>');
        setTimeout(() => {
            $(this).fadeIn(function () {
                get_and_fill();
            });
        }, 100);
    });

    $('#asuransi').change(function () {
        blanko_action();
    });

    $('#btn_search').click(function () {
        $('#number_search').val($('#blanko_number').val());
        blanko_action();
    });

    $('.data-nav').click(function () {
        const order = $(this).data('page');
        const page_now = parseInt($('#halaman').html());
        const page_max = parseInt($('#halaman_max').html());
        switch (order) {
            case 'first':
                blanko_action();
                break;
            case 'last':
                blanko_action(page_max);
                break;
            case 'prev':
                blanko_action(page_now - 1);
                break;
            case 'next':
                blanko_action(page_now + 1);
                break;
            default:
                blanko_action();
                break;
        }
    });

    $('.clear-filter').click(function () {
        const to_clear = $(this).data('clear').split(',');
        $.each(to_clear, function (key, val) {
            $('#' + val).val('');
        });
        blanko_action();
    });

});

function blanko_action(page = 1) {
    const asuransi_val = $('#asuransi').val();
    const number_val = $('#number_search').val();
    const limit = 10;
    const offset = (page - 1) * limit;
    $('.data-nav').attr('disabled', true);
    $('#halaman').html(page);
    $('#loading').fadeIn(function () {
        setTimeout(() => {
            get_and_fill(offset, limit, (asuransi_val == '') ? null : asuransi_val, (number_val == '') ? null : number_val);
        }, 100);
    });
}

function get_and_fill(offset = 0, limit = 10, asuransi = null, nomor = null) {
    let query_string = '?limit=' + limit;
    if (offset > 0) query_string += '&offset=' + offset;
    if (asuransi != null) query_string += '&asuransi=' + asuransi;
    if (nomor != null) query_string += '&nomor=' + nomor;
    $.get(base_url('api/usedblanko' + encodeURI(query_string)), function (data, status) {
        if (data.status && status == 'success' && data.data.list.length > 0) {
            let table_rows = '';
            data.data.list.forEach(val => {
                table_rows += table_row(val.id, val.asuransi, val.nomor, val.principal);
            });
            $('#blanko_list').addClass('table-responsive p-0').html(table_body(table_rows));
            $('#count_data').html(formating_number(data.data.count));
            set_page(data.data.count);
            $('#loading').fadeOut();
        } else {
            failed_load('Tidak ada data', 'far fa-file');
        }
    }).fail(function () {
        failed_load('Gagal memuat data');
    });
}

function table_body(rows = '') {
    let table = '<table class="table table-hover text-nowrap">';
    table += '<thead><tr><th>Asuransi</th><th class="text-center">Nomor Blanko</th><th>Nama Principal</th>';
    table += '<th class="text-center"><i class="fas fa-cog"></i></th>';
    table += '</tr></thead><tbody>' + rows + '</tbody></table>';
    return table;
}

function table_row(id = '', asuransi = '', nomor = '', principal = '') {
    let table_row = '<tr><td>' + asuransi + '</td>';
    table_row += '<td class="text-center">' + nomor + '</td>';
    table_row += '<td>' + principal + '</td><td class="text-center py-0 align-middle">';
    table_row += '<a href="' + base_url('blanko/detail/' + id) + '" class="btn btn-primary btn-sm text-bold">';
    table_row += 'Lihat Detail<i class="fas fa-eye ml-2"></i></a>';
    table_row += '</td></tr>';
    return table_row;
}

function set_page(count = 0, views = 10) {
    const now_page = parseInt($('#halaman').html());
    const max_page = Math.floor(count / views) + ((count % views == 0) ? 0 : 1);
    $('#halaman_max').html((count === 0) ? 1 : max_page);
    if (now_page < max_page) {
        $('button[data-page="next"]').attr('disabled', false);
        $('button[data-page="last"]').attr('disabled', false);
    }
    if (now_page > 1) {
        $('button[data-page="prev"]').attr('disabled', false);
        $('button[data-page="first"]').attr('disabled', false);
    }
}

function failed_load(str = '', icon = 'fas fa-exclamation-triangle') {
    let body = '<div class="text-secondary d-flex justify-content-center" style="min-height:30vh">';
    body += '<div class="text-center my-auto"><i class="' + icon + ' fa-3x"></i>';
    body += '<p class="mt-3 mb-0">' + str + '</p></div></div>';
    $('#blanko_list').removeClass('table-responsive p-0').html(body);
    $('#halaman_max').html('1');
    $('#count_data').html('0');
    $('#loading').fadeOut();
}