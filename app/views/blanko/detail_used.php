<?php $this->load->helper('format');
if (!function_exists('set_date_to')) {
    function set_date_to($from, $to, $days)
    {
        if ($to === null) {
            return format_date(modify_days($from, '+' . (intval($days) - 1)));
        } else {
            return format_date($to);
        }
    }
}
$detail_jaminan = array(
    ['title' => 'Jenis Jaminan', 'value' => $jaminan['tipe'] . ' (' . $jaminan['tipe2'] . ')', 'nowrap' => array(true, true)],
    ['title' => 'Nomor Jaminan', 'value' => $jaminan['nomor'], 'nowrap' => array(true, true)],
    ['title' => 'Nilai Jaminan', 'value' => $jaminan['currency'] . ' ' . self_number_format($jaminan['nilai'], 2), 'nowrap' => array(true, true)],
    ['title' => 'Principal', 'value' => $jaminan['principal'], 'nowrap' => array(false, true)],
    ['title' => 'Nomor Kontrak', 'value' => nl2br($jaminan['kontrak']), 'nowrap' => array(true, false)],
    ['title' => 'Pekerjaan', 'value' => nl2br($jaminan['pekerjaan']), 'nowrap' => array(false, false)],
    ['title' => 'Obligee', 'value' => $jaminan['obligee'], 'nowrap' => array(false, true)],
    ['title' => 'Jaminan Berlaku', 'value' => $jaminan['day'] . ' Hari', 'nowrap' => array(true, true)],
    ['title' => 'Berlaku Mulai Tanggal', 'value' => format_date($jaminan['date']), 'nowrap' => array(true, true)],
    ['title' => 'Berlaku Sampai Tanggal', 'value' => set_date_to($jaminan['date'], $jaminan['date_to'], $jaminan['day']), 'nowrap' => array(true, true)]
);
foreach ($detail_jaminan as $key => $dj) if ($dj['value'] === null) $detail_jaminan[$key]['value'] = '-'; ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title text-bold text-olive">Blanko Digunakan</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-6">
                <div class="text-center">
                    <h6 class="text-bold">Bukti Pemakaian :</h6>
                </div>
                <?php if ($blanko['image_use'] === null) : ?>
                    <div class="text-center py-5 text-fade mt-5">
                        <p><i class="far fa-file-image fa-3x"></i></p>
                        <p>Tidak ada Bukti Pemakaian</p>
                    </div>
                    <div class="text-center mb-5">
                        <?php if ($true_office) : ?>
                            <a href="" class="btn btn-primary text-bold disabled"><i class="fas fa-upload mr-2"></i>Upload Bukti Pemakaian</a>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="mw-400 mx-auto mb-3">
                        <img class="img-fluid img-bordered" src="<?= base_url('asset/img/blanko_use/' . $blanko['image_use']); ?>" alt="">
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-xl-6">
                <div class="text-center">
                    <h5 class="text-bold">Data Jaminan</h5>
                    <?php if ($true_office) : ?>
                        <a href="<?= base_url('edit/jaminan/' . $jaminan['id']); ?>" class="btn btn-default btn-sm"><i class="fas fa-edit mr-2"></i>Edit Data Jaminan</a>
                    <?php endif; ?>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-borderless">
                        <?php foreach ($detail_jaminan as $dj) : ?>
                            <tr>
                                <?= (($dj['nowrap'][0]) ? '<th class="text-nowrap">' : '<th>') . $dj['title']; ?></th>
                                <th>:</th>
                                <?= (($dj['nowrap'][1]) ? '<td class="text-nowrap">' : '<td>') . $dj['value']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php if ($blanko['id_status'] == '2' && $true_office) : ?>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('revision/b/' . self_md5($blanko['id'])); ?>" class="btn btn-info text-bold"><i class="fas fa-recycle mr-2"></i>Revisi dan Gunakan Blanko Baru</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>