<?php $office_data = get_user_office($this->session->userdata('id'));
$blanko_total = $this->db->query("SELECT COUNT(id_office) AS total FROM blanko WHERE id_office = ? AND id_status = 1", $office_data->id)->row(); ?>
<div class="row">
    <div class="col-lg-5 col-md-6">
        <div class="small-box bg-olive">
            <div class="inner px-3">
                <h3><?= number_format(intval($blanko_total->total), 0, ',', '.'); ?></h3>
                <p>Blanko Tersedia</p>
            </div>
            <div class="icon"><i class="fas fa-list-alt"></i></div>
            <a href="<?= base_url('blanko'); ?>" class="small-box-footer">Lihat Semua<i class="fas fa-arrow-circle-right ml-2"></i></a>
        </div>