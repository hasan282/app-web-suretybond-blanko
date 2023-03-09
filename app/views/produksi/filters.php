<?php
$data_asuransi = $this->db->query('SELECT enkripsi, nama FROM asuransi ORDER BY nama ASC')->result_array();
$data_office = $this->db->query('SELECT id, nama FROM office ORDER BY id_tipe, nama ASC')->result_array();
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
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="asuransi">Asuransi</label>
                        <select name="asuransi" id="asuransi" class="form-control filterselect">
                            <option value="" selected>---</option>
                            <?php foreach ($data_asuransi as $da) : ?>
                                <option value="<?= $da['enkripsi']; ?>"><?= $da['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="office">Agen Pengguna</label>
                        <select name="office" id="office" class="form-control filterselect">
                            <option value="" selected>---</option>
                            <?php foreach ($data_office as $do) : ?>
                                <option value="<?= $do['id']; ?>"><?= $do['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="laprod">Laporan Produksi</label>
                        <select name="laprod" id="laprod" class="form-control filterselect" disabled>
                            <option value="">---</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="button" id="filterbutton" class="btn btn-primary text-bold" disabled><i class="fas fa-search mr-2"></i>Cari Data Blanko</button>
            <button type="button" id="clearall" class="btn btn-default ml-1 show-tooltip" title="Clear Filter"><i class="fas fa-eraser"></i></button>
        </div>
    </div>
</div>