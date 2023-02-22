<form action="<?= base_url(uri_string()); ?>" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body pt-2">
                    <div class="text-right">
                        <small class="text-danger"><i>kolom bertanda</i> * <i>wajib diisi</i></small>
                    </div>
                    <input type="hidden" name="var_value" value="<?= $this->input->get('var'); ?>">
                    <div class="form-group mw-400">
                        <label for="jenis">Jenis Jaminan <span class="text-danger">*</span></label>
                        <select name="jenis" id="jenis" class="form-control">
                            <option disabled selected>---</option>
                            <?php foreach ($this->db->query('SELECT id, tipe FROM jaminan_tipe ORDER BY tipe ASC')->result_array() as $jen) : ?>
                                <option value="<?= $jen['id']; ?>"><?= $jen['tipe']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mw-300">
                        <label>Nilai Jaminan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="currency" id="currency" class="form-control">
                                <?php foreach ($this->db->query('SELECT id, symbol_1 AS val FROM currency ORDER BY symbol_1 ASC')->result_array() as $cr) : ?>
                                    <option <?= ($cr['id'] == '1') ? 'selected ' : ''; ?>value="<?= $cr['id']; ?>"><?= $cr['val']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="nilai" id="nilai" class="form-control" data-inputmask="'alias':'numeric','groupSeparator':'.','radixPoint':','" data-mask>
                        </div>
                    </div>
                    <div class="form-group mw-400">
                        <label for="jaminan_num">Nomor Jaminan <span class="text-danger">*</span></label>
                        <input name="jaminan_num" id="jaminan_num" class="form-control" placeholder="No. Jaminan">
                    </div>
                    <div class="form-group mw-400">
                        <label for="principal">Principal <span class="text-danger">*</span></label>
                        <select name="principal" id="principal" class="form-control" disabled></select>
                        <div id="other_principal"></div>
                    </div>
                    <div class="form-group mw-400">
                        <label for="obligee">Obligee <span class="text-danger">*</span></label>
                        <select name="obligee" id="obligee" class="form-control" disabled></select>
                        <div id="other_obligee"></div>
                    </div>
                    <div class="form-group">
                        <label for="contract">Nomor Kontrak</label>
                        <textarea class="form-control" name="contract" id="contract" rows="2" placeholder="No. Kontrak"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan</label>
                        <textarea class="form-control" name="pekerjaan" id="pekerjaan" rows="2" placeholder="Pekerjaan"></textarea>
                    </div>
                    <label>Jaminan Berlaku <span class="text-danger">*</span></label>
                    <div class="border-fade pt-3 pr-3">
                        <?php $input_date = array('tanggal_from' => 'Mulai', 'tanggal_to' => 'Sampai');
                        foreach ($input_date as $id => $titles) : ?>
                            <div class="form-group row ml-xl-0 ml-2">
                                <label for="<?= $id; ?>" class="col-xl-4 col-form-label text-xl-right"><?= $titles; ?> Tanggal</label>
                                <div class="input-group date col-xl-8" id="<?= $id; ?>" data-target-input="nearest">
                                    <div class="input-group-prepend" data-target="#<?= $id; ?>" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="hidden" id="val_<?= $id; ?>" name="<?= $id; ?>">
                                    <input type="text" class="form-control datetimepicker-input" id="<?= $id; ?>_input" data-target="#<?= $id; ?>" placeholder="<< Pilih Tanggal">
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="form-group row ml-xl-0 ml-2">
                            <label for="days" class="col-xl-4 col-form-label text-xl-right">Selama</label>
                            <div class="input-group col-xl-8 mw-200">
                                <input type="text" name="days" id="days" class="form-control" data-inputmask="'alias':'numeric'" data-mask>
                                <div class="input-group-append">
                                    <span class="input-group-text text-bold">Hari</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center pt-3">
                        <button type="button" id="show_keterangan" data-show="0" class="btn btn-sm btn-link">
                            <i class="fas fa-plus mr-2"></i>Tambahkan Keterangan
                        </button>
                    </div>
                    <div id="box_keterangan" class="zero-height">
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" rows="4" placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body pb-2">
                    <div class="form-group mw-400 mx-auto">
                        <label for="image_upload">Upload Bukti Pemakaian</label>
                        <div class="custom-file">
                            <label class="custom-file-label" for="image_upload">Pilih Gambar</label>
                            <input type="file" class="custom-file-input input-sm" id="image_upload" name="image_upload">
                        </div>
                        <small class="text-secondary ml-2"><i>format file .jpg .png .gif .bmp</i></small>
                    </div>
                    <input type="hidden" value="0" name="inp_width" id="inp_width">
                    <input type="hidden" value="0" name="inp_height" id="inp_height">
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="preview-cover">
                        <div class="text-center py-5 text-fade">
                            <p><i class="far fa-file-image fa-3x"></i></p>
                            <p>Image Preview</p>
                        </div>
                    </div>
                    <div class="preview-image zero-height">
                        <img id="img_preview" src="#" alt="" class="img-fluid">
                        <div class="text-center pt-3">
                            <p class="text-secondary"><span id="num_width">0</span> x <span id="num_height">0</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary text-bold" disabled><i class="fas fa-save mr-2"></i>Simpan Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $office_id = get_user_office($this->session->userdata('id'))->id;
if (isset($office)) $office_id = $office['id'];
$principal = $this->db->query('SELECT id AS num, nama FROM principal WHERE id_office = ? ORDER BY nama ASC', $office_id)->result_array();
$obligee = $this->db->query('SELECT id AS num, nama FROM obligee WHERE id_office = ? ORDER BY nama ASC', $office_id)->result_array();
$princ_data = [];
$obli_data = [];
foreach ($principal as $pr) $princ_data['NUM' . $pr['num']] = $pr['nama'];
foreach ($obligee as $ob) $obli_data['NUM' . $ob['num']] = $ob['nama']; ?>
<script>
    const data_select = {
        "principal": <?= json_encode($princ_data); ?>,
        "obligee": <?= json_encode($obli_data); ?>
    };
    $(function() {
        $('#show_keterangan').click(function() {
            const is_show = ($(this).data('show') == '1');
            if (is_show) {
                $(this).html('<i class="fas fa-plus mr-2"></i>Tambahkan Keterangan');
                $(this).data('show', '0');
                $('#keterangan').val('');
                $('#box_keterangan').addClass('zero-height');
            } else {
                $(this).html('<i class="fas fa-times mr-2"></i>Batalkan');
                $(this).data('show', '1');
                $('#box_keterangan').removeClass('zero-height');
            }
        });
    });
</script>