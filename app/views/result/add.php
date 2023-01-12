<?php $message = $this->session->flashdata('flash_message');
if ($message != null) : ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Berhasil</h5>
        <?= $message; ?>
    </div>
<?php endif;
$query_where = "BETWEEN '" . $result['number_from'] . "' AND '" . $result['number_to'] . "'";
if ($result['numbers'] !== null) $query_where = "IN (" . $result['numbers'] . ")";
$selected_blanko = $this->db->query("SELECT enkripsi, nomor FROM blanko WHERE nomor " . $query_where . " LIMIT 24")->result_array();
$total_blanko = 0;
if ($result['number_to'] !== null && $result['number_from'] !== null) $total_blanko = intval($result['number_to']) - intval($result['number_from']) + 1;
if ($result['numbers'] !== null) $total_blanko = sizeof(explode(',', $result['numbers'])); ?>
<div class="card">
    <div class="card-body">
        <div class="d-md-flex">
            <div class="px-3 ml-auto">
                <p class="mb-1 text-secondary">Tanggal</p>
                <h5 class="text-bold"><?= format_date($result['tanggal']); ?></h5>
            </div>
            <div class="px-3 border-left">
                <p class="mb-1 text-secondary">Asuransi</p>
                <h5 class="text-bold"><?= $result['asuransi']; ?></h5>
            </div>
            <div class="px-3 border-left mr-auto">
                <p class="mb-1 text-secondary">Total Blanko</p>
                <h5 class="text-bold"><?= $total_blanko; ?></h5>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Nomor Blanko</h3>
    </div>
    <div class="card-body">
        <div class="row justify-content-center">
            <?php foreach ($selected_blanko as $sb) : ?>
                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                    <a href="<?= base_url('blanko/detail/' . $sb['enkripsi']); ?>" class="btn btn btn-default btn-block text-bold"><?= $sb['nomor'] ?></a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($total_blanko > 24) : ?>
            <div class="text-center">
                <hr class="mw-600">
                <a href="" class="btn btn-link btn-sm">lihat selengkapnya</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md text-center text-md-left">
                <a href="<?= base_url('dashboard'); ?>" class="btn btn-default mb-3 mb-md-0"><i class="fas fa-angle-double-left mr-2"></i>Kembali ke Dashboard</a>
            </div>
            <div class="col-md text-center text-md-right">
                <a href="<?= base_url('blanko/add'); ?>" class="btn btn-primary text-bold"><i class="fas fa-plus mr-2"></i>Tambah Data Lain</a>
            </div>
        </div>
    </div>
</div>

<div class="mw-400 mx-auto">
    <div class="card">
        <div class="card-body">
            <div class="text-center" id="boxcancel">
                <button type="button" class="btn btn-default btn-sm text-bold" id="addcancel">
                    <i class="fas fa-times-circle mr-2"></i>Batalkan Tambah Data
                </button>
            </div>
            <div class="text-center zero-height" id="boxconfirm">
                <p class="text-bold text-danger"><?= $total_blanko; ?> data Blanko akan dihapus, yakin hapus data?</p>
                <a href="" class="btn btn-danger">Ya, Hapus Data</a>
                <button class="btn btn-default" id="deletecancel">Jangan Hapus</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#addcancel').click(function() {
            $('#boxcancel').addClass('zero-height');
            $('#boxconfirm').removeClass('zero-height');
        });
        $('#deletecancel').click(function() {
            $('#boxcancel').removeClass('zero-height');
            $('#boxconfirm').addClass('zero-height');
        });
    });
</script>