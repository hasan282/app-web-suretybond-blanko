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

function convert_date(date) {
    const month = ('Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember').split(',');
    const dt = date.split('-');
    return (dt.length == 3) ? parseInt(dt[2]) + ' ' + month[dt[1] - 1] + ' ' + dt[0] : null;
}

function formating_number(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function setup_datepicker(selector, multiple_form = false) {
    $(selector).datetimepicker({
        format: 'YYYY-MM-DD'
    });
    let preview = '.datetimepicker-input';
    if (multiple_form) preview = selector + '_input';
    $(preview).on('input', function () {
        const vals = $(this).val();
        $(this).val(convert_date(vals));
    });
    $(preview).on('keydown keypress', function () {
        return false;
    });
}

function first_letter_capital(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}