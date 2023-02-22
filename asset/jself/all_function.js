function number_only(selector) {
    $(selector).on('keypress input', event => {
        const input_value = /[0-9]/;
        if (!input_value.test(String.fromCharCode(event.keyCode))) event.preventDefault();
    });
}

function input_regex_only(selector, regex) {
    $(selector).on('keypress input', event => {
        const input_value = regex;
        if (!input_value.test(String.fromCharCode(event.keyCode))) event.preventDefault();
    });
}

function base_url(uri = '') {
    return basic_all_url + uri;
}

function convert_date(date, tipe = 1) {
    let dateresult = null; let dt = [];
    const month = ('Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember').split(',');
    switch (tipe) {
        case 1:
            dt = date.split('-');
            if (dt.length == 3) dateresult = parseInt(dt[2]) + ' ' + month[dt[1] - 1] + ' ' + dt[0];
            break;
        case 2:
            dt = date.split('-');
            if (dt.length == 3) dateresult = dt[2] + '/' + dt[1] + '/' + dt[0];
            break;
        case 11:
            dt = date.split('/');
            if (dt.length == 3) dateresult = parseInt(dt[2]) + ' ' + month[dt[1] - 1] + ' ' + dt[0];
            break;
        case 12:
            dt = date.split('/');
            if (dt.length == 3) dateresult = dt[2] + '-' + dt[1] + '-' + dt[0];
            break;
        case 13:
            dt = date.split('/');
            if (dt.length == 3) dateresult = parseInt(dt[0]) + ' ' + month[dt[1] - 1] + ' ' + dt[2];
            break;
        default:
            dateresult = null;
            break;
    }
    return dateresult;
}

function formating_number(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function setup_datepicker(selector, multiple_form = false) {
    $(selector).datetimepicker({
        format: 'DD/MM/YYYY'
    });
    let preview = '.datetimepicker-input';
    if (multiple_form) preview = selector + '_input';
    $(preview).on('focus', function () {
        const preval = $(this).prev().val();
        $(this).val(convert_date(preval, 2));
    });
    $(preview).on('focusout', function () {
        const vals = $(this).prev().val();
        $(this).val(convert_date(vals));
    });
    $(preview).prev().on('input', function () {
        const vals = $(this).val();
        $(this).val(convert_date(vals, 12));
        $(preview).val(convert_date(vals, 13));
    });
}

function first_letter_capital(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
