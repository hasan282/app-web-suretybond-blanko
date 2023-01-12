<div class="card">
    <div class="card-body">
        <div class="mw-300 mx-auto">
            <?php $allowed_access = array('1', '2');
            $user_access = get_user_access($this->session->userdata('id'));
            if (in_array($user_access, $allowed_access)) : ?>
                <a href="<?= base_url('blanko/add'); ?>" class="btn btn-primary btn-block text-bold"><i class="fas fa-plus mr-2"></i>Tambah Data Blanko</a>
            <?php endif; ?>
            <a href="<?= base_url('blanko/send'); ?>" class="btn btn-default btn-block text-bold"><i class="fas fa-archive mr-2"></i>Kirim Blanko</a>
            <a href="<?= base_url('process/crash'); ?>" class="btn btn-danger btn-block text-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Lapor Blanko Rusak</a>
        </div>
    </div>
</div>