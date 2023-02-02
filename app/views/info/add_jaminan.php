<div class="card">
    <div class="card-body text-center">
        <h6 class="text-secondary mb-0">Tidak ada Data Jaminan</h6>
        <?php if ($true_office) : ?>
            <hr>
            <a href="<?= base_url('edit/guarantee/' . self_md5($blanko['id'])); ?>" class="btn btn-sm btn-secondary">
                <i class="fas fa-plus mr-2"></i>Tambah Data Jamian
            </a>
        <?php endif; ?>
    </div>
</div>