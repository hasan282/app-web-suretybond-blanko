function checklist_setup(setting = {}) {
    let setup = {
        checkall: '#check_all',
        checkone: '.checkcolumns',
        changecolor: false,
        colorclass: 'text-primary',
        enablesubmit: false,
        submit: '#submitcheck'
    };
    for (const key in setting) {
        setup[key] = setting[key];
    }
    $(setup.checkall).change(function () {
        if ($(this).is(':checked')) {
            $(setup.checkone + ':not(:checked)').each(function () {
                $(this).trigger('click');
            });
        } else {
            $(setup.checkone + ':checked').each(function () {
                $(this).trigger('click');
            });
        }
    });
    $(setup.checkone).change(function () {
        const check_id = this.id;
        if ($(this).is(':checked')) {
            if (setup.changecolor) $('label[for="' + check_id + '"]').addClass(setup.colorclass);
            if ($(setup.checkone + ':checked').length == $(setup.checkone).length) $(setup.checkall).prop('checked', true);
        } else {
            if (setup.changecolor) $('label[for="' + check_id + '"]').removeClass(setup.colorclass);
            $(setup.checkall).prop('checked', false);
        }
        if (setup.enablesubmit) $(setup.submit).attr('disabled', ($(setup.checkone + ':checked').length < 1));
    });
}
function trigger_click_id(ids = []) {
    ids.forEach(id => {
        $('#' + id).trigger('click');
    });
}