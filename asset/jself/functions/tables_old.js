class table_data {

    constructor(setting = {}) {
        this.options = {
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
            navigator_class: 'data-nav',
            navigator_data: 'page',
            case_prev: 'prev',
            case_next: 'next',
            case_first: 'first',
            case_last: 'last',
            error_icon: 'fas fa-exclamation-triangle',
            table_header: [
                { title: 'Asuransi', center: false },
                { title: 'Nomor Blanko', center: true }
            ],
            option_field: true,
            option_icon: 'fas fa-cog'
        }
        this.api_url = base_url()
        this.page = 1
    }

    option(key) {
        return this.options[key]
    }

    url(uri) {
        this.api_url = base_url(uri)
    }

    fills() {
        $.get(this.api_url, function (data, status) {
            if (data.status && status == 'success' && data.data.list.length > 0) {
                let table_rows = '';
                data.data.list.forEach(val => {
                    table_rows += table_row(val.id, val.asuransi, val.prefix, val.nomor);
                });
                $('#').addClass('table-responsive p-0').html(table_body(table_rows));
                $('#count_data').html(formating_number(data.data.count));
                set_page(data.data.count);
                $('#loading').fadeOut();
            } else {
                this.failed('Tidak ada data', 'far fa-file');
            }
        }).fail(function () {
            this.failed('Gagal memuat data');
        });


    }

    failed(str = '', icon = this.options.error_icon) {
        let body = '<div class="text-secondary d-flex justify-content-center" style="min-height:30vh">';
        body += '<div class="text-center my-auto"><i class="' + icon + ' fa-3x"></i>';
        body += '<p class="mt-3 mb-0">' + str + '</p></div></div>';
        $('#' + this.options.id_table).removeClass('table-responsive p-0').html(body);
        if (this.options.show_page) $('#' + this.options.id_page_max).html('1');
        if (this.options.show_count) $('#count_data').html('0');
        $('#loading').fadeOut();
    }

}

let tables;

function setup_table(setup = {}) {

    tables = new table_data(setup);

    $('#' + tables.option('id_loader')).fadeOut(tables.option('loader_timeout'), function () {
        $(this).html('<i class="' + tables.option('loader_class') + '"></i>');
        setTimeout(() => {
            $(this).fadeIn(function () {
                // get_and_fill();
                tables.failed('Tidak Ada Data');
            });
        }, tables.option('loader_timeout'));
    });

    $('.' + tables.option('navigator_class')).click(function () {
        const order = $(this).data(tables.option('navigator_data'));
        const page_now = parseInt($('#' + tables.option('')).html());
        const page_max = parseInt($('#' + tables.option('')).html());
        switch (order) {
            case tables.option('case_first'):
                blanko_action();
                break;
            case tables.option('case_last'):
                blanko_action(page_max);
                break;
            case tables.option('case_prev'):
                blanko_action(page_now - 1);
                break;
            case tables.option('case_next'):
                blanko_action(page_now + 1);
                break;
            default:
                blanko_action();
                break;
        }
    });

}

function get_and_fill(uri = '') {
    $.get(base_url(), function (data, status) {
        if (data.status && status == 'success' && data.data.list.length > 0) {
            let table_rows = '';
            data.data.list.forEach(val => {
                table_rows += table_row(val.id, val.asuransi, val.prefix, val.nomor);
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
    table += '<thead><tr><th>Asuransi</th><th class="text-center">Nomor Blanko</th>';
    table += '<th class="text-center"><i class="fas fa-cog"></i></th>';
    table += '</tr></thead><tbody>' + rows + '</tbody></table>';
    return table;
}

function table_row(id = '', asuransi = '', prefix = '', nomor = '') {
    let table_row = '<tr><td>' + asuransi + '</td><td class="text-center">';
    table_row += '<span class="text-secondary">' + prefix + '</span><b>' + nomor + '</b></td>';
    table_row += '<td class="text-center py-0 align-middle">';
    table_row += set_action_button(id, idstatus);
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

function set_action_button(vals, sts = '1') {
    if ((parseInt(sts) > 1)) {
        return '<a href="' + base_url('blanko/detail/' + vals) + '" class="btn btn-info btn-sm text-bold">Lihat Detail<i class="fas fa-info ml-2"></i></a>';
    } else {
        return '<a href="' + base_url('process/used/' + vals) + '" class="btn btn-default btn-sm text-bold">Gunakan<i class="fas fa-share ml-2"></i></a>';
    }
}

setup_table();