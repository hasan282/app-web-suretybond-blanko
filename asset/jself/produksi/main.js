$(function () {

    checklist_setup({
        checkall: '#checkall',
        checkone: '.checkone',
        enablesubmit: true,
        submit: '#submitreport'
    });

    $('#clearall').click(function () {
        $('#asuransi').val('');
        $('#office').val('');
    });

});

