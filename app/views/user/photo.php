<?php $photos = array(
    'male' => 'user_default_male.jpg',
    'female' => 'user_default_female.jpg'
);
$now_photo = $this->session->userdata('foto');
if (!in_array($now_photo, array_values($photos))) $photos['other'] = $now_photo; ?>
<div class="card">
    <div class="card-body">
        <div class="mx-auto mw-900">
            <div class="row mt-3 justify-content-center">
                <?php foreach ($photos as $key => $pho) : ?>
                    <div class="col-md-3 col-6">
                        <div class="card-photo card bg-light">
                            <div class="card-body">
                                <div class="text-center">
                                    <img src="<?= base_url('asset/img/user/' . $pho); ?>" alt="" class="img-circle img-fluid elevation-1">
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-center div-choose">
                                    <?php if ($now_photo == $pho) : ?>
                                        <button type="button" class="btn btn-sm btn-default" disabled>Foto Profil</button>
                                    <?php else : ?>
                                        <a href="<?= base_url('user/photo/' . $key); ?>" class="btn btn-sm btn-primary">Pilih Foto</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="col-md-3 col-6">
                    <div class="card-photo card bg-light">
                        <div class="card-body">
                            <div class="text-center">
                                <img src="<?= base_url('asset/img/icon/plus_gray.jpg'); ?>" alt="" class="img-circle img-fluid elevation-1">
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-secondary" id="new_photo">Tambah Foto</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mx-auto mw-400 zero-height" id="box_new">
    <form action="<?= base_url('user/photo/new'); ?>" method="POST" enctype="multipart/form-data">
        <div class="card mb-1">
            <div class="card-body pb-2">
                <div class="form-group mw-400 mx-auto">
                    <label for="image_upload">Upload Foto Profil</label>
                    <small class="text-secondary ml-2"><i>disarankan rasio foto 1 : 1</i></small>
                    <div class="custom-file">
                        <label class="custom-file-label" for="image_upload">Pilih Gambar</label>
                        <input type="file" class="custom-file-input input-sm" id="image_upload" name="image_upload">
                    </div>
                    <small class="text-secondary ml-2"><i>format file .jpg .png .gif .bmp</i></small>
                </div>
                <input type="hidden" value="0" name="inp_width" id="inp_width">
                <input type="hidden" value="0" name="inp_height" id="inp_height">
                <div class="text-center pt-3">
                    <button type="submit" id="btn_upload" class="btn btn-primary text-bold" disabled><i class="fas fa-upload mr-2"></i>Upload Foto</button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="preview-cover">
                    <div class="text-center py-5 text-fade">
                        <p><i class="far fa-file-image fa-3x"></i></p>
                        <p>Image Preview</p>
                    </div>
                </div>
                <div class="preview-image zero-height">
                    <img id="img_preview" src="#" alt="" class="img-fluid">
                    <div class="text-center pt-3">
                        <p class="text-secondary"><span id="num_width">0</span> x <span id="num_height">0</span></p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(function() {
        $('#new_photo').click(function() {
            $(this).attr('disabled', true);
            $('#box_new').removeClass('zero-height');
        });
        $('#image_upload').on('change', function() {
            setTimeout(() => {
                const wdth = ($('#inp_width').val() == '0');
                const higt = ($('#inp_height').val() == '0');
                $('#btn_upload').attr('disabled', (wdth || higt));
            }, 100);
        });
    });
</script>