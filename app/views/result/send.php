<?php $message = $this->session->flashdata('flash_message');
if ($message != null) : ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Berhasil</h5>
        <?= $message; ?>
    </div>
<?php endif;
$total_blanko = intval($result['number_to']) - intval($result['number_from']) + 1; ?>
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
    <div class="card-body row">
        <div class="col-lg-6">
            <table class="table table-borderless table-sm table-responsive">
                <tr>
                    <td class="text-nowrap" rowspan="2">Dikirim dari</td>
                    <td rowspan="2">:</td>
                    <td class="text-nowrap text-bold"><?= $result['office_from']; ?></td>
                </tr>
                <tr>
                    <td><?= $result['alamat_from']; ?></td>
                </tr>
                <tr>
                    <td class="text-nowrap" rowspan="2">Dikirim kepada</td>
                    <td rowspan="2">:</td>
                    <td class="text-nowrap text-bold"><?= $result['office_to']; ?></td>
                </tr>
                <tr>
                    <td><?= $result['alamat_to']; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-lg-6">
            <div class="h-100 d-flex align-items-center">
                <div class="w-100 text-center">
                    <a href="<?= base_url('export/sent/' . $parameter); ?>">
                        <button class="btn btn-primary">Cetak Tanda Terima</button>
                    </a>
                </div>
            </div>
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
                <a href="<?= base_url('blanko/send'); ?>" class="btn btn-primary text-bold"><i class="fas fa-archive mr-2"></i>Kirim Blanko Lain</a>
            </div>
        </div>
    </div>
</div>