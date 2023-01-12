let form_values = { jenis: false, nilai: false, nomor: false, principal: false, obligee: false, days: false, date: false };

$(function () {

    fill_select_form('principal');
    fill_select_form('obligee');
    setup_datepicker('#tanggal');
    $('[data-mask]').inputmask();

    $('#prefix').keyup(function () {
        const vals = $(this).val();
        $('#prefix_view').html(vals);
    });

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
        form_values.days = ($(this).val() != '');
        enable_submit_button();
    });

    $('#val_tanggal').on('input', function () {
        form_values.date = ($(this).val() != '');
        enable_submit_button();
    });

});

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

function enable_submit_button() {
    const checker = Object.values(form_values).every(Boolean);
    $('button[type="submit"]').attr('disabled', !checker);
}