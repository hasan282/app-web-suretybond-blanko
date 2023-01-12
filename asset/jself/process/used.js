let form_values = {
    jenis: false,
    nilai: false,
    nomor: false,
    principal: false,
    obligee: false,
    date_from: false,
    date_to: false,
    days: false
};

$(function () {

    fill_select_form('principal');
    fill_select_form('obligee');
    setup_datepicker('#tanggal_from', true);
    setup_datepicker('#tanggal_to', true);
    $('[data-mask]').inputmask();

    $('form[method="POST"]').submit(function () {
        $('button[type="submit"]').attr('disabled', true);
    });

    $('#jenis').on('change', function () {
        form_values.jenis = ($(this).val() != null);
        enable_submit_button();
    });

    $('#principal').on('change', function () {
        form_values.principal = ($(this).val() != null);
        enable_submit_button();
    });

    $('#obligee').on('change', function () {
        form_values.obligee = ($(this).val() != null);
        enable_submit_button();
    });

    $('#jaminan_num').on('keyup', function () {
        form_values.nomor = ($(this).val() != '');
        enable_submit_button();
    });

    $('#nilai').on('keyup', function () {
        form_values.nilai = ($(this).val() != '');
        enable_submit_button();
    });

    $('#days').on('keyup', function () {
        form_values.days = (parseInt($(this).val()) > 1);
        date_add($(this).val());
        enable_submit_button();
    });

    $('#val_tanggal_from').on('input', function () {
        form_values.date_from = ($(this).val() != '');
        date_range();
        enable_submit_button();
    });

    $('#val_tanggal_to').on('input', function () {
        form_values.date_to = ($(this).val() != '');
        date_range();
        enable_submit_button();
    });

    if (typeof jaminandata != 'undefined') setTimeout(() => {
        fill_forms(jaminandata);
    }, 10);

});

function fill_forms(data = {}) {
    for (const id in data) {
        $('#' + id)[data[id][0]](data[id][1]).trigger(data[id][2]);
    }
}

function fill_select_form(form_id) {
    let options = '<option selected disabled>---</option>';
    $.each(data_select[form_id], function (key, val) {
        options += '<option value="' + key + '">' + val + '</option>';
    });
    $('#' + form_id).html(options).attr('disabled', false);
    $('#other_' + form_id).html('<button type="button" id="btn_' + form_id + '" class="btn btn-link px-0 py-0 btn-sm ml-2">Masukkan Nama ' + first_letter_capital(form_id) + ' Baru?</button>');
    $('#btn_' + form_id).click(function () {
        form_values[form_id] = false;
        enable_submit_button();
        show_input(form_id);
    });
}

function show_input(form_id) {
    let input = '<div class="d-flex mt-2"><input type="text" class="form-control" name="' + form_id + '_input" placeholder="Nama ' + first_letter_capital(form_id) + '">';
    input += '<button type="button" id="input_cancel_' + form_id + '" class="btn btn-link pr-0"><i class="fas fa-times"></i></button></div>';
    $('#other_' + form_id).html(input);
    $('#' + form_id).html('').attr('disabled', true);
    $('#input_cancel_' + form_id).click(function () {
        form_values[form_id] = false;
        enable_submit_button();
        fill_select_form(form_id);
    });
    $('input[name="' + form_id + '_input"]').keyup(function () {
        form_values[form_id] = ($(this).val() != '');
        enable_submit_button();
    });
}

function date_range() {
    let from = 0;
    let to = 0;
    if ($('#val_tanggal_from').val() != '') from = (new Date($('#val_tanggal_from').val())).getTime();
    if ($('#val_tanggal_to').val() != '') to = (new Date($('#val_tanggal_to').val())).getTime();
    if ((to - from) > 0 && from > 0) {
        $('#days').val(Math.ceil((to - from) / (1000 * 3600 * 24)) + 1);
        form_values.days = true;
    } else {
        $('#days').val('');
        form_values.days = false;
    }
}

function date_add(days) {
    if ($('#val_tanggal_from').val() != '') {
        let date_to = new Date($('#val_tanggal_from').val());
        let day = (parseInt(days) - 1 > 0) ? parseInt(days) - 1 : 0;
        if (day > 0) {
            date_to.setDate(date_to.getDate() + day);
            const result_date = date_to.toISOString().substring(0, 10);
            $('#val_tanggal_to').val(result_date);
            $('#tanggal_to_input').val(convert_date(result_date));
            form_values.date_to = true;
        } else {
            empty_date_to();
        }
    } else {
        empty_date_to();
    }
}

function empty_date_to() {
    $('#val_tanggal_to').val('');
    $('#tanggal_to_input').val('');
    form_values.date_to = false;
}

function enable_submit_button() {
    const checker = Object.values(form_values).every(Boolean);
    $('button[type="submit"]').attr('disabled', !checker);
}