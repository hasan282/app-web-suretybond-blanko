<?php $genders = array('male', 'female'); ?>
<form action="<?= base_url(uri_string()); ?>" method="POST">
    <div class="mw-900 mx-auto">
        <div class="card">
            <div class="card-body row">
                <div class="col-md mb-4 mb-md-0">
                    <div class="form-group mw-300 mx-auto">
                        <label for="in_nama">Nama Lengkap</label>
                        <input type="text" name="in_nama" id="in_nama" class="form-control" placeholder="Nama Lengkap">
                    </div>
                    <div class="form-group mw-300 mx-auto">
                        <label for="in_username">Username</label>
                        <input type="text" name="in_username" id="in_username" class="form-control" placeholder="Username">
                        <small class="ml-2 text-secondary">Karakter Huruf Kecil (a-z) dan Angka (0-9)</small>
                    </div>
                    <div class="form-group mw-300 mx-auto">
                        <label for="in_role">Role Access</label>
                        <select name="in_role" id="in_role" class="form-control">
                            <option selected disabled>---</option>
                            <?php foreach ($this->db->get('access')->result_array() as $acc) : ?>
                                <option value="<?= $acc['id']; ?>"><?= $acc['access']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mw-300 mx-auto">
                        <label for="in_office">Kantor Agen</label>
                        <select name="in_office" id="in_office" class="form-control">
                            <option selected disabled>---</option>
                            <?php foreach ($this->db->query('SELECT id, nama FROM office WHERE is_active = 1 ORDER BY id_tipe, nama ASC')->result_array() as $of) : ?>
                                <option value="<?= $of['id']; ?>"><?= $of['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="zero-height">
                        <?php foreach ($genders as $gen) : ?>
                            <input type="radio" name="in_photo" id="rad_<?= $gen; ?>" value="user_default_<?= $gen; ?>.jpg">
                        <?php endforeach; ?>
                    </div>
                </div>
                <script>
                    function choose_this(e) {
                        const ids = $(e).parent().attr('id');
                        $('.div-choose').html('<button type="button" class="btn btn-sm btn-primary text-bold btn-choose" onclick="choose_this(this)">Pilih Foto</button>');
                        $('#' + ids).html('<button class="btn btn-sm btn-default" disabled><i class="fas fa-check-circle mr-2"></i>Dipilih</button>');
                        $('#' + ids.replace('choose', 'rad')).trigger('click');
                        $('.card-photo').removeClass('bg-gradient-primary').addClass('bg-light');
                        $('#' + ids.replace('choose', 'card')).removeClass('bg-light').addClass('bg-gradient-primary');
                    }
                </script>
                <div class="col-md d-flex justify-content-center">
                    <div class="row my-auto mw-400">
                        <?php foreach ($genders as $gen) : ?>
                            <div class="col">
                                <div class="card-photo card bg-light" id="card_<?= $gen; ?>">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="<?= base_url('asset/img/user/user_default_' . $gen . '.jpg'); ?>" alt="" class="img-circle img-fluid elevation-2">
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center div-choose" id="choose_<?= $gen; ?>">
                                            <button type="button" class="btn btn-sm btn-primary text-bold btn-choose" onclick="choose_this(this)">Pilih Foto</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mw-400 mx-auto">
        <div class="card">
            <div class="card-body text-center">
                <button type="submit" class="btn btn-primary text-bold"><i class="fas fa-save mr-2"></i>Simpan Data User</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $('#choose_male').children().trigger('click');
    });
</script>