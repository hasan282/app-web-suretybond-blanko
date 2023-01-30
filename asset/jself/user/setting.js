$(function () {

    $('.showpass').on('click', function () {
        const aidi = ($(this).attr('id')).replace('show_', '');
        const span = $(this).children('span');
        if (span.data('showpass') == 'hide') {
            $('#' + aidi).attr('type', 'text');
            span.attr('class', 'fas fa-fw fa-eye-slash').data('showpass', 'show');
        } else {
            $('#' + aidi).attr('type', 'password');
            span.attr('class', 'fas fa-fw fa-eye').data('showpass', 'hide');
        }
    });

    $('.pass-input').on('keyup', function () {
        const empty_old = ($('#pass_before').val() == '');
        const empty_new = ($('#pass_new').val() == '');
        const empty_confirm = ($('#pass_confirm').val() == '');
        $('#btn_password').attr('disabled', (empty_old || empty_new || empty_confirm));
    });

    $('.office-change').on('keyup', function () {
        const empty_alamat = ($('#alamat').html() == '');
        const empty_telpon = ($('#telpon_num').val() == '');
        $('#save_officedata').attr('disabled', (empty_alamat || empty_telpon));
    });

});