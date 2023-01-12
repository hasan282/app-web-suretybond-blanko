</div>
<div class="col-lg-7 col-md-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-bold text-secondary"><i class="fas fa-bell mr-2"></i>Notifikasi</h3>
        </div>
        <div class="card-body" style="min-height:270px">
            <?php
            $notification = 0;
            $user_enkrip = $this->session->userdata('id');
            $user_office = (array) $this->db->query('SELECT office.id AS id, alamat, telpon FROM user INNER JOIN office ON user.id_office = office.id WHERE user.enkripsi = ?', $user_enkrip)->row();
            $no_img_used = $this->db->query('SELECT COUNT(user.id_office) AS jumlah FROM blanko_used INNER JOIN user ON blanko_used.id_user = user.id WHERE user.id_office = ? AND image IS NULL', $user_office['id'])->row();
            $no_img_crash = $this->db->query('SELECT COUNT(user.id_office) AS jumlah FROM blanko_crash INNER JOIN user ON blanko_crash.id_user = user.id WHERE user.id_office = ? AND image IS NULL', $user_office['id'])->row();
            if ($user_office['alamat'] == null || $user_office['telpon'] == null) :
                $notification++; ?>
                <div class="callout callout-info">
                    <h5 class="text-info">Pengaturan profil akunmu belum lengkap.</h5>
                    <p>Mohon lengkapi <a href="<?= base_url('user/setting'); ?>">profilmu disini</a>!</p>
                </div>
            <?php endif;
            if (intval($no_img_used->jumlah) > 0) :
                $notification++; ?>
                <div class="callout callout-warning">
                    <h5 class="text-orange"><b><?= intval($no_img_used->jumlah); ?> data blanko</b> belum menyertakan bukti penggunaan.</h5>
                    <p>Sertakan bukti penggunaan blanko dan <a href="<?= base_url('attachment/used'); ?>">upload disini</a>!</p>
                </div>
            <?php endif;
            if (intval($no_img_crash->jumlah) > 0) :
                $notification++; ?>
                <div class="callout callout-warning">
                    <h5 class="text-orange"><b><?= intval($no_img_crash->jumlah); ?> data blanko</b> belum menyertakan bukti kerusakan.</h5>
                    <p>Sertakan bukti blanko rusak dan <a href="<?= base_url('attachment/crash'); ?>">upload disini</a>!</p>
                </div>
            <?php endif;
            if ($notification == 0) : ?>
                <div class="d-flex" style="min-height:245px">
                    <p class="mx-auto my-auto text-secondary">Tidak ada Notifikasi</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>