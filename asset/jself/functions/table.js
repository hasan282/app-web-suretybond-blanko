let table_options = {
    url: base_url('api/blanko/1'),
    query: '',
    id_loader: 'loading',
    loader_class: 'fas fa-2x fa-spinner fa-pulse text-secondary',
    loader_timeout: 100,
    id_table: 'blanko_list',
    show_count: true,
    id_count: 'count_data',
    show_page: true,
    id_page_now: 'halaman',
    id_page_max: 'halaman_max',
    pagination: true,
    limit: 10,
    offset: 0,
    failed_message: 'Tidak ada data',
    failed_icon: 'far fa-file',
    navigator_class: 'data-nav',
    navigator_data: 'page',
    case_prev: 'prev',
    case_next: 'next',
    case_first: 'first',
    case_last: 'last',
    table_header: [
        { title: 'Asuransi', center: false },
        { title: 'Nomor Blanko', center: true }
    ],
    table_row: '<td>$asuransi</td><td class="text-center"><span class="text-secondary">$prefix</span><b>$nomor</b></td>',
    option_field: true,
    option_icon: 'fas fa-cog',
    option_button: '<a href="' + base_url('process/used/$id') + '" class="btn btn-default btn-sm text-bold">Gunakan<i class="fas fa-share ml-2"></i></a>'
}

function table_setup(setup = {}) {
    for (const key in setup) {
        table_options[key] = setup[key];
    }
    $('#' + table_options.id_loader).fadeOut(table_options.loader_timeout, function () {
        $(this).html('<i class="' + table_options.loader_class + '"></i>');
        setTimeout(() => {
            $(this).fadeIn(function () {
                table_fill();
            });
        }, table_options.loader_timeout);
    });
    $('.' + table_options.navigator_class).click(function () {
        const order = $(this).data(table_options.navigator_data);
        const page_now = parseInt($('#' + table_options.id_page_now).html());
        const page_max = parseInt($('#' + table_options.id_page_max).html());
        switch (order) {
            case table_options.case_first:
                table_change();
                break;
            case table_options.case_last:
                table_change(page_max);
                break;
            case table_options.case_prev:
                table_change(page_now - 1);
                break;
            case table_options.case_next:
                table_change(page_now + 1);
                break;
            default:
                table_change();
                break;
        }
    });
}

function table_fill() {
    let query_string = '?limit=' + table_options.limit;
    if (table_options.offset > 0) query_string += '&offset=' + table_options.offset;
    query_string += table_options.query;
    $.get(table_options.url + encodeURI(query_string), function (data, status) {
        if (data.status && status == 'success' && data.data.list.length > 0) {
            let table_rows = '';
            data.data.list.forEach(val => {
                table_rows += table_row(val);
            });
            $('#' + table_options.id_table).addClass('table-responsive p-0').html(table_body(table_rows));
            $('#' + table_options.id_count).html(formating_number(data.data.count));
            table_page(data.data.count);
            $('#' + table_options.id_loader).fadeOut();
        } else {
            table_failed(table_options.failed_message, table_options.failed_icon);
        }
    }).fail(function () {
        table_failed('Gagal memuat data');
    });
}

function table_option(setup = {}) {
    for (const key in setup) {
        table_options[key] = setup[key];
    }
    table_change();
}

function table_change(page = 1) {
    table_options.offset = (page - 1) * table_options.limit;
    $('.' + table_options.navigator_class).attr('disabled', true);
    $('#' + table_options.id_page_now).html(page);
    $('#' + table_options.id_loader).fadeIn(function () {
        setTimeout(() => {
            table_fill();
        }, table_options.loader_timeout);
    });
}

function table_page(count = 0) {
    const views = table_options.limit;
    const now_page = parseInt($('#' + table_options.id_page_now).html());
    const max_page = Math.floor(count / views) + ((count % views == 0) ? 0 : 1);
    $('#' + table_options.id_page_max).html((count === 0) ? 1 : max_page);
    if (now_page < max_page) {
        $('button[data-page="' + table_options.case_next + '"]').attr('disabled', false);
        $('button[data-page="' + table_options.case_last + '"]').attr('disabled', false);
    }
    if (now_page > 1) {
        $('button[data-page="' + table_options.case_prev + '"]').attr('disabled', false);
        $('button[data-page="' + table_options.case_first + '"]').attr('disabled', false);
    }
}

function table_body(rows = '') {
    let table = '<table class="table table-hover text-nowrap"><thead><tr>';
    table_options.table_header.forEach(field => {
        table += '<th' + ((field.center) ? ' class="text-center"' : '') + '>' + field.title + '</th>';
    });
    if (table_options.option_field) {
        table += '<th class="text-center"><i class="' + table_options.option_icon + '"></i></th>';
    }
    table += '</tr></thead><tbody>' + rows + '</tbody></table>';
    return table;
}

function table_row(fields = {}) {
    let table_row = table_options.table_row;
    if (table_options.option_field) {
        table_row += '<td class="text-center py-0 align-middle">' + table_options.option_button + '</td>';
    }
    for (const key in fields) {
        const regex = new RegExp('\\$' + key, 'g');
        const field_val = (fields[key] === null) ? '' : fields[key];
        table_row = table_row.replace(regex, field_val);
    }
    return '<tr>' + table_row + '</tr>';
}

function table_failed(str = '', icon = 'fas fa-exclamation-triangle') {
    let body = '<div class="text-secondary d-flex justify-content-center" style="min-height:30vh">';
    body += '<div class="text-center my-auto"><i class="' + icon + ' fa-3x"></i>';
    body += '<p class="mt-3 mb-0">' + str + '</p></div></div>';
    $('#' + table_options.id_table).removeClass('table-responsive p-0').html(body);
    $('#' + table_options.id_page_max).html('1');
    $('#' + table_options.id_count).html('0');
    $('#' + table_options.id_loader).fadeOut();
}