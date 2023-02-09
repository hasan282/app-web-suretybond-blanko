<?php
$data_asuransi = $this->db->query('SELECT enkripsi, nama FROM asuransi ORDER BY nama ASC')->result_array();
$data_office = $this->db->query('SELECT id, nama FROM office ORDER BY id_tipe, nama ASC')->result_array();
$data_tipe = $this->db->query('SELECT id, tipe FROM jaminan_tipe ORDER BY tipe ASC')->result_array();
$data_month = $this->db->query('SELECT laprod FROM blanko WHERE laprod IS NOT NULL GROUP BY laprod ORDER BY laprod DESC')->result_array();
?>
<div class="mx-auto mw-900">
    <div class="card collapsed-card">
        <div class="card-header">
            <h3 class="card-title text-bold text-secondary">Filter Data</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body pb-2">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="asuransi">Asuransi</label>
                        <select name="asuransi" id="asuransi" class="form-control">
                            <option value="" selected>---</option>
                            <?php foreach ($data_asuransi as $da) : ?>
                                <option value="<?= $da['enkripsi']; ?>"><?= $da['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="office">Agen Pengguna</label>
                        <select name="office" id="office" class="form-control">
                            <option value="" selected>---</option>
                            <?php foreach ($data_office as $do) : ?>
                                <option value="<?= $do['id']; ?>"><?= $do['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="rangefrom">Nomor Blanko <small>(range)</small></label>
                        <input type="text" name="rangefrom" id="rangefrom" class="form-control" disabled>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="blankostatus">Status Blanko</label>
                        <select name="blankostatus" id="blankostatus" class="form-control">
                            <option value="" selected>---</option>
                            <option value="1">Tersedia</option>
                            <option value="2">Terpakai</option>
                            <option value="3">Rusak</option>
                            <option value="4">Revisi</option>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="laprod">Laporan Produksi</label>
                        <select name="laprod" id="laprod" class="form-control">
                            <option value="" selected>---</option>
                            <?php foreach ($data_month as $dm) : ?>
                                <option value="<?= $dm['laprod']; ?>"><?= format_date($dm['laprod'] . '-01', 'MM3 YY2'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="jaminantipe">Jenis Jaminan</label>
                        <select name="jaminantipe" id="jaminantipe" class="form-control">
                            <option value="" selected>---</option>
                            <?php foreach ($data_tipe as $dt) : ?>
                                <option value="<?= $dt['id']; ?>"><?= $dt['tipe']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="button" class="btn btn-primary text-bold" disabled><i class="fas fa-search mr-2"></i>Cari Data Blanko</button>
            <button type="button" class="btn btn-default ml-1 show-tooltip" title="Clear Filter"><i class="fas fa-eraser"></i></button>
        </div>
    </div>
</div>