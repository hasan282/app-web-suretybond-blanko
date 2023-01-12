<?php $message_alert = array(
    'add' => 'Penambahan data Blanko baru',
    'send' => 'Pengiriman Blanko'
); ?>
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5><i class="icon fas fa-ban"></i> Proses Gagal</h5>
    <p class="mb-0"><?= $message_alert[$data_type]; ?> tidak dapat dilakukan.</p>
</div>
<div class="card">
    <div class="card-body pb-1">
        <div class="callout callout-danger">
            <h5 class="mb-0 text-secondary"><?= $data_message; ?>.</h5>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md text-center text-md-left">
                <a href="<?= base_url('dashboard'); ?>" class="btn btn-default mb-3 mb-md-0"><i class="fas fa-angle-double-left mr-2"></i>Kembali ke Dashboard</a>
            </div>
            <div class="col-md text-center text-md-right">
                <?php if ($data_type == 'add') : ?>
                    <a href="<?= base_url('blanko/add'); ?>" class="btn btn-primary text-bold"><i class="fas fa-plus mr-2"></i>Tambah Data Lagi</a>
                <?php endif;
                if ($data_type == 'send') : ?>
                    <a href="<?= base_url('blanko/send'); ?>" class="btn btn-primary text-bold"><i class="fas fa-archive mr-2"></i>Kirim Blanko Lain</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>