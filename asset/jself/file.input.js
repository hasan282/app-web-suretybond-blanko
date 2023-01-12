$(function () {

    bsCustomFileInput.init();

    $('[type="file"]').change(function () {
        const [file] = this.files;
        const extension = ['jpg', 'jpeg', 'png', 'gif'];
        const imgs = new Image();
        imgs.onload = function () {
            const im_height = parseInt(this.height);
            const im_width = parseInt(this.width);
            set_dimension(im_width, im_height);
        }
        if (file) {
            if (extension.includes($(this).val().split(/[.]+/).pop())) {
                img_preview.src = URL.createObjectURL(file);
                imgs.src = URL.createObjectURL(file);
                show_image();
            } else {
                hide_image();
                set_dimension(0, 0);
            }
        } else {
            hide_image();
            set_dimension(0, 0);
        }
    });

});

function show_image() {
    $('.preview-cover').addClass('zero-height');
    $('.preview-image').removeClass('zero-height');
}

function hide_image() {
    $('.preview-cover').removeClass('zero-height');
    $('.preview-image').addClass('zero-height');
}

function set_dimension(width, height) {
    $('#num_width').html(width);
    $('#num_height').html(height);
    $('#inp_width').val(width);
    $('#inp_height').val(height);
}