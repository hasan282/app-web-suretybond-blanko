<div class="row mw-900 mx-auto">
    <div class="col-lg-4 col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center mb-4">
                    <img class="profile-image img-fluid img-circle" style="max-width:180px" src="<?= base_url('asset/img/user/' . $this->session->userdata('foto')); ?>" alt="">
                </div>
                <h3 class="profile-username text-center mb-1"><?= $this->session->userdata('nama'); ?></h3>
                <p class="text-muted text-center"><?= $this->session->userdata('role'); ?></p>
                <a href="<?= base_url('user/setting'); ?>" class="btn btn-primary btn-block text-bold"><i class="fas fa-cog mr-2"></i>Pengaturan Akun</a>
            </div>
        </div>
    </div>
    <?php $userdata = $this->db->query('SELECT user.id AS id, office.nama AS office, alamat, telpon FROM user INNER JOIN office ON user.id_office = office.id WHERE user.enkripsi = ?', $this->session->userdata('id'))->row(); ?>
    <div class="col-lg-8 col-md-6">
        <div class="card">
            <div class="card-body pb-1 px-4">
                <p><i class="far fa-calendar-alt fa-fw mr-1"></i>Tanggal Akun Dibuat</p>
                <p class="ml-2 text-bold"><?= id_to_date($userdata->id); ?></p>
                <hr>
                <p><i class="fas fa-laptop-house fa-fw mr-1"></i>Nama Kantor Agen</p>
                <p class="ml-2 text-bold"><?= $userdata->office; ?></p>
                <hr>
                <p><i class="fas fa-map-marked-alt fa-fw mr-1"></i>Alamat Kantor Agen</p>
                <p class="ml-2 text-bold"><?= $userdata->alamat; ?></p>
                <hr>
                <p><i class="fas fa-phone fa-fw mr-1"></i>Nomor Telpon Kantor</p>
                <p class="ml-2 text-bold"><?= ($userdata->telpon == null) ? '-' : $userdata->telpon; ?></p>
            </div>
        </div>
    </div>
</div>