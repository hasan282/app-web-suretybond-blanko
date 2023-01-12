<div class="mw-600 mx-auto">
    <div class="card">
        <div class="card-body text-center">
            <a href="<?= base_url('report/period?var=' . urlencode($this->input->get('var'))); ?>" class="btn btn-primary text-bold">Laporan Penggunaan <?= $nickname; ?></a>
        </div>
    </div>
</div>