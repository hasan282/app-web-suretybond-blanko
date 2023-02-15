<div class="text-center mb-3">
    <a href="<?= base_url('blanko/detail/' . $blanko['enkrip']); ?>" class="btn btn-default"><i class="fas fa-times mr-2"></i>Batalkan Edit</a>
</div>
<div class="mx-auto mw-900">
    <form action="<?= base_url(uri_string()); ?>" method="POST">
        <div class="card">
            <div class="card-body">
                <div class="mw-600 mx-auto px-sm-5">
                    <input type="hidden" name="var_value" value="<?= $this->input->get('var'); ?>">
                    <div class="form-group mw-400">
                        <label for="jenis">Jenis Jaminan</label>
                        <select name="jenis" id="jenis" class="form-control">
                            <option disabled selected>---</option>
                            <?php foreach ($this->db->query('SELECT id, tipe FROM jaminan_tipe ORDER BY tipe ASC')->result_array() as $jen) : ?>
                                <option value="<?= $jen['id']; ?>"><?= $jen['tipe']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mw-400">
                        <label>Nilai Jaminan</label>
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
                        <label for="jaminan_num">Nomor Jaminan</label>
                        <input name="jaminan_num" id="jaminan_num" class="form-control" placeholder="No. Jaminan">
                    </div>
                    <div class="form-group mw-400">
                        <label for="principal">Principal</label>
                        <select name="principal" id="principal" class="form-control" disabled></select>
                        <div id="other_principal"></div>
                    </div>
                    <div class="form-group mw-400">
                        <label for="obligee">Obligee</label>
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
                    <label>Jaminan Berlaku</label>
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
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary text-bold"><i class="fas fa-save mr-2"></i>Simpan Perubahan Data</button>
            </div>
        </div>
    </form>
</div>
<?php $guarantee = array(
    'jenis' => ['val', $jaminan['tipe_id'], 'change'],
    'currency' => ['val', $jaminan['currency_id'], 'change'],
    'nilai' => ['val', self_number_format($jaminan['nilai'], 2), 'keyup'],
    'jaminan_num' => ['val', $jaminan['nomor'], 'keyup'],
    'principal' => ['val', 'NUM' . $jaminan['principal_id'], 'change'],
    'obligee' => ['val', 'NUM' . $jaminan['obligee_id'], 'change'],
    'contract' => ['html', $jaminan['kontrak'], 'keyup'],
    'pekerjaan' => ['html', $jaminan['pekerjaan'], 'keyup'],
    'val_tanggal_from' => ['val', $jaminan['date'], 'input'],
    'tanggal_from_input' => ['val', $jaminan['date'], 'input'],
    'val_tanggal_to' => ['val', $jaminan['date_to'], 'input'],
    'tanggal_to_input' => ['val', $jaminan['date_to'], 'input'],
    'days' => ['val', $jaminan['day'], 'keyup']
);
foreach ($guarantee as $key => $gr) $guarantee[$key][1] = trim($gr[1]);
$office_id = get_user_office($this->session->userdata('id'))->id;
if (isset($office)) $office_id = $office['id'];
$principal = $this->db->query('SELECT id AS num, nama FROM principal WHERE id_office = ? ORDER BY nama ASC', $office_id)->result_array();
$obligee = $this->db->query('SELECT id AS num, nama FROM obligee WHERE id_office = ? ORDER BY nama ASC', $office_id)->result_array();
$princ_data = [];
$obli_data = [];
foreach ($principal as $pr) $princ_data['NUM' . $pr['num']] = $pr['nama'];
foreach ($obligee as $ob) $obli_data['NUM' . $ob['num']] = $ob['nama']; ?>
<script>
    const jaminandata = <?= json_encode($guarantee); ?>;
    const data_select = {
        "principal": <?= json_encode($princ_data); ?>,
        "obligee": <?= json_encode($obli_data); ?>
    };
</script>