<?php $max_data = intval($this->db->get_where('reference', ['ref' => 'maxexport'])->row()->vals); ?>
<div id="export_card" class="mx-auto mw-300 transition-05">
    <div class="card">
        <div class="card-body p-2">
            <div id="box_open">
                <button id="export_open" class="btn bg-gradient-olive btn-sm btn-block text-bold">Export ke Excel</button>
                <div class="text-center font-italic">
                    <small class="text-secondary">Maksimal <?= $max_data; ?> Data untuk Export</small>
                </div>
            </div>
            <div id="box_process" class="text-center zero-height">
                <div class="p-3">
                    <button id="export_close" type="button" class="btn btn-default btn-sm">
                        <i class="fas fa-times mr-2"></i>Batalkan
                    </button>
                    <div class="text-center pt-2">
                        <p class="text-secondary">Export <span class="text-bold text-primary">0</span> Data ke File Excel</p>
                    </div>
                    <form action="<?= base_url('search/export') ?>" target="_blank" method="POST">
                        <input type="hidden" value="">
                        <input type="hidden" name="max_data" value="<?= $max_data; ?>">
                        <input type="hidden" name="val_asuransi" id="val_asuransi" value="">
                        <input type="hidden" name="val_office" id="val_office" value="">
                        <input type="hidden" name="val_status" id="val_status" value="">
                        <input type="hidden" name="val_produksi" id="val_laprod" value="">
                        <input type="hidden" name="val_tipe" id="val_tipe" value="">
                        <input type="hidden" name="val_range" id="val_range" value="">
                        <div class="row text-left">
                            <div class="col-12">
                                <div class="icheck-secondary">
                                    <input type="checkbox" id="check_all">
                                    <label for="check_all" class="text-gray">Semua Kolom</label>
                                </div>
                                <hr>
                            </div>
                            <?php
                            $col_names = array(
                                'check_asuransi' => 'Nama Asuransi',
                                'check_fullnumber' => 'Nomor Blanko',
                                'check_status' => 'Status',
                                'check_jaminan_tipe1' => 'Jenis Jaminan',
                                'check_jaminan' => 'Nomor Jaminan',
                                'check_principal' => 'Principal',
                                'check_obligee' => 'Obligee',
                                'check_kontrak' => 'Kontrak',
                                'check_pekerjaan' => 'Pekerjaan',
                                'check_currency2' => 'Mata Uang',
                                'check_nilai' => 'Nilai Jaminan',
                                'check_date_from' => 'Tanggal Berlaku',
                                'check_date_to' => 'Tanggal Berakhir',
                                'check_days' => 'Lama Berlaku',
                                'check_office' => 'Agen Pengguna'
                            );
                            foreach ($col_names as $key => $val) : ?>
                                <div class="col-xl-4 col-sm-6">
                                    <div class="icheck-primary">
                                        <input type="checkbox" class="checkcolumns" name="<?= $key; ?>" id="<?= $key; ?>">
                                        <label class="labelcheck" for="<?= $key; ?>"><?= $val; ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                        <div class="form-group text-left mw-300 mx-auto">
                            <label for="filename">Nama File</label>
                            <input id="filename" name="filename" class="form-control" placeholder="export_file">
                            <small class="ml-2 text-secondary"><i>nama default export_file.xlsx</i></small>
                        </div>
                        <button type="submit" id="submitexport" class="btn btn-success text-bold">
                            <i class="fas fa-download mr-2"></i>Download File Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>