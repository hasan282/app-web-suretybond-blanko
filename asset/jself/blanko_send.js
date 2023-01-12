let select_office = false;
let select_asuransi = false;
let date_input = false;

$(function () {

    number_only('.blankorange');

    $('#tanggal').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $('.datetimepicker-input').on('input', function () {
        const vals = $(this).val();
        $(this).val(convert_date(vals));
    });

    $('.datetimepicker-input').keydown(function () {
        return false;
    });

    $('.blankorange').on('keyup', function () {
        set_total();
    });

    $('#office_to').change(function () {
        const keys = 'OF' + $(this).val();
        $('#alamat_to').html(addrs[keys]);
        select_office = true;
        form_enable();
    });

    $('#asuransi').on('change', function () {
        $('#status_asr').html('<i class="fas fa-spinner fa-pulse text-secondary"></i>');
        setTimeout(() => {
            get_message($(this).val());
        }, 100);
    });

    $('#val_tanggal').on('input', function () {
        date_input = true;
        form_enable();
    });

});

function set_total() {
    const val_from = $('#number_from').val();
    const val_to = $('#number_to').val();
    let range = (val_from.length === val_to.length) ? val_to - val_from : 0;
    $('[type="submit"]').attr('disabled', (range <= 0));
    $('#total_blanko').val((range > 0) ? range + 1 : 0);
}

function get_message(id) {
    $.get(base_url('api/asuransi/' + id), function (data, status) {
        if (data['status'] && status == 'success') {
            const result = parseInt(data['data']);
            const message = (result > 0) ? '<small class="text-success">' + result + ' Blanko Belum Terpakai</small>' : '<small class="text-danger">Tidak Ada Sisa Blanko</small>';
            $('#status_asr').html(message);
            select_asuransi = (result > 0) ? true : false;
            form_enable();
        } else {
            $('#status_asr').html('<small class="text-danger">Gagal Memuat Data</small>');
            select_asuransi = false;
        }
    }).fail(function () {
        $('#status_asr').html('<small class="text-danger">Gagal Memuat Data</small>');
        select_asuransi = false;
    });
}

function form_enable() {
    const status = (select_asuransi && select_office && date_input) ? false : true;
    if (!$('[type="submit"]').is(':disabled') && status) {
        $('[type="submit"]').attr('disabled', true);
        $('.blankorange').val('');
        $('#total_blanko').val('0');
    }
    $('.blankorange').attr('disabled', status);
}