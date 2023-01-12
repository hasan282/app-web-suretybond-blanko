<div class="timeline">
    <?php $current_date = '';
    $this->load->helper('id');
    foreach ($history as $his) :
        $date_now = substr($his['id'], 0, 6);
        $history_time = id_to_time($his['id'], 'H : i');
        if ($date_now != $current_date) : ?>
            <div class="time-label">
                <span class="bg-secondary"><?= id_to_date($date_now); ?></span>
            </div>
        <?php $current_date = $date_now;
        endif;
        if ($his['tipe'] == 'crash') : ?>
            <div>
                <i class="fas fa-exclamation-circle bg-danger"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock mr-2"></i><?= $history_time; ?></span>
                    <h3 class="timeline-header">Membuat laporan <b class="text-danger">kerusakan blanko</b></h3>
                    <div class="timeline-body">Blanko <b><?= $his['asuransi']; ?></b> nomor <a href="<?= base_url('blanko/detail/' . $his['enkrip']); ?>"><?= $his['nomor']; ?></a></div>
                </div>
            </div>
        <?php endif;
        if ($his['tipe'] == 'use') : ?>
            <div>
                <i class="fas fa-print bg-olive"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock mr-2"></i><?= $history_time; ?></span>
                    <h3 class="timeline-header">Membuat laporan <b class="text-olive">penggunaan blanko</b></h3>
                    <div class="timeline-body">Blanko <b><?= $his['asuransi']; ?></b> nomor <a href="<?= base_url('blanko/detail/' . $his['enkrip']); ?>"><?= $his['nomor']; ?></a></div>
                </div>
            </div>
        <?php endif;
        if ($his['tipe'] == 'add') : ?>
            <div>
                <i class="fas fa-plus bg-primary"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock mr-2"></i><?= $history_time; ?></span>
                    <h3 class="timeline-header"><b class="text-primary">Menambahkan</b> data blanko baru</h3>
                    <div class="timeline-body">Blanko <b><?= $his['asuransi']; ?></b> nomor <a href="<?= base_url('result/add/' . $his['enkrip']); ?>"><?= $his['nomor']; ?></a></div>
                </div>
            </div>
        <?php endif;
        if ($his['tipe'] == 'send') : ?>
            <div>
                <i class="fas fa-paper-plane bg-primary"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fas fa-clock mr-2"></i><?= $history_time; ?></span>
                    <h3 class="timeline-header"><b class="text-primary">Mengirim</b> blanko ke <b><?= $his['office']; ?></b></h3>
                    <div class="timeline-body">Blanko <b><?= $his['asuransi']; ?></b> nomor <a href="<?= base_url('result/send/' . $his['enkrip']); ?>"><?= $his['nomor']; ?></a></div>
                </div>
            </div>
    <?php endif;
    endforeach; ?>
    <div>
        <i class="fas fa-clock bg-gray"></i>
    </div>
</div>
<?php $showval = intval($this->input->get('show'));
if ($showval < 90 && sizeof($history) === $showval + 10) : ?>
    <div class="text-center">
        <a class="btn btn-default btn-sm" href="<?= base_url('history?show=' . ($showval + 10)); ?>">Lihat Lebih Banyak</a>
    </div>
<?php endif; ?>