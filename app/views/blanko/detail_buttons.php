<?php if (intval($blanko['id_status']) < 2) : ?>
    <div class="text-center py-3">
        <a href="<?= base_url('process/used/' . self_md5($blanko['id'])); ?>" class="btn btn-primary text-bold"><i class="fas fa-print mr-2"></i>Gunakan Blanko</a>
    </div>
<?php endif;
if (intval($blanko['id_status']) < 3) : ?>
    <div class="text-center py-3">
        <a href="<?= base_url('process/crash/' . self_md5($blanko['id'])); ?>" class="btn btn-danger text-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Laporkan Kerusakan</a>
    </div>
<?php endif; ?>