<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Blanko <span class="text-bold text-secondary"><?= $header; ?></span></h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Nomor Blanko</th>
                    <th class="text-center border-right">Status</th>
                    <th>Nomor Jaminan</th>
                    <th>Principal</th>
                    <th class="text-center border-left">Pengguna</th>
                    <th class="text-center">Produksi</th>
                    <th class="text-center border-left"><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report['data'] as $key => $rc) : ?>
                    <tr>
                        <td class="text-center text-bold"><?= $key + 1; ?></td>
                        <td class="text-center"><span class="text-secondary"><?= $rc['prefix']; ?></span><span class="text-bold"><?= $rc['nomor']; ?></span></td>
                        <td class="text-center border-right text-bold text-<?= $rc['color']; ?>"><?= $rc['status']; ?></td>
                        <td class="border-right"><?= ($rc['jaminan'] === null) ? '-' : $rc['jaminan']; ?></td>
                        <td><?= ($rc['principal'] === null) ? '-' : $rc['principal']; ?></td>
                        <td class="text-center text-bold border-left"><?= $rc['office']; ?></td>
                        <td class="text-center border-left"><?= ($rc['produksi'] === null) ? '-' : format_date($rc['produksi'] . '-01', 'MM2 YY2'); ?></td>
                        <td class="text-center py-0 align-middle border-left"><a href="<?= base_url('blanko/detail/' . $rc['id']); ?>" class="btn btn-info text-bold btn-sm"><i class="fas fa-info-circle mr-2"></i>Detail</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php if (!empty($enkrip)) {
    foreach ($enkrip as $en) : ?>
        <div class="text-center">
            <a href="<?= base_url('result/add/' . $en['enkripsi']); ?>" class="btn btn-default btn-sm">Hasil Tambah</a>
        </div>
<?php endforeach;
} ?>