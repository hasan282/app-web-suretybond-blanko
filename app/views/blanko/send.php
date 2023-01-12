<?php $office = get_user_office($this->session->userdata('id'));
$office_list = $this->db->query('SELECT id, nama, alamat FROM office WHERE is_active = 1 ORDER BY nama ASC')->result_array();
$address_list = [];
foreach ($office_list as $ol) $address_list['OF' . $ol['id']] = $ol['alamat']; ?>
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body pb-2">
                <div class="form-group mw-300">
                    <label for="office_from_text">Dikirim Dari</label>
                    <input type="text" id="office_from_text" class="form-control" value="<?= $office->nama; ?>" readonly>
                </div>
                <div class="form-group mt-3">
                    <label for="alamat_from">Alamat</label>
                    <textarea id="alamat_from" class="form-control" rows="2" readonly><?= $office->alamat; ?></textarea>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url(uri_string()); ?>" method="POST">
                    <input type="hidden" name="office_from" value="<?= $office->id; ?>">
                    <div class="form-group mw-300">
                        <label for="office_to">Tujuan Kirim</label>
                        <select name="office_to" id="office_to" class="form-control">
                            <option disabled selected>---</option>
                            <?php foreach ($office_list as $ol) :
                                if ($ol['id'] != $office->id) : ?>
                                    <option value="<?= $ol['id']; ?>"><?= $ol['nama']; ?></option>
                            <?php endif;
                            endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="alamat_to">Alamat</label>
                        <textarea id="alamat_to" class="form-control" rows="2" readonly></textarea>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="form-group mw-300">
                    <label>Tanggal Kirim</label>
                    <div class="input-group date" id="tanggal" data-target-input="nearest">
                        <div class="input-group-prepend" data-target="#tanggal" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                        <input type="hidden" id="val_tanggal" name="tanggal">
                        <input type="text" class="form-control datetimepicker-input" data-target="#tanggal" placeholder="<< Pilih Tanggal">
                    </div>
                </div>
                <div class="form-group mw-400">
                    <label for="asuransi">Asuransi</label>
                    <select name="asuransi" id="asuransi" class="form-control">
                        <option disabled selected>---</option>
                        <?php $this->load->database();
                        $asuransi = $this->db->query('SELECT enkripsi, nama FROM asuransi WHERE is_active = 1 ORDER BY nama ASC')->result_array();
                        foreach ($asuransi as $as) : ?>
                            <option value="<?= $as['enkripsi']; ?>"><?= $as['nama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="status_asr" class="px-3"></div>
                </div>
                <label class="mb-0 text-secondary" for="number_from"><small>Nomor Blanko</small></label>
                <div class="row mw-600 pt-3 pr-3 border-fade">
                    <?php $input_blanko = array(
                        ['id' => 'number_from', 'label' => 'Dari'],
                        ['id' => 'number_to', 'label' => 'Sampai']
                    );
                    foreach ($input_blanko as $ib) : ?>
                        <div class="col-md">
                            <div class="form-group row">
                                <label for="<?= $ib['id']; ?>" class="col-4 col-form-label text-right"><?= $ib['label']; ?></label>
                                <input type="text" name="<?= $ib['id']; ?>" id="<?= $ib['id']; ?>" class="form-control blankorange col-8" disabled>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="form-group row mw-200 mt-3 ml-auto ml-md-0 mr-auto">
                    <label for="total_blanko" class="col-7 col-form-label">Total Blanko</label>
                    <input type="text" id="total_blanko" class="col-5 form-control" value="0" readonly>
                </div>
                <hr>
                <div class="text-md-right text-center">
                    <button class="btn btn-primary text-bold" type="submit" disabled><i class="fas fa-paper-plane mr-2"></i>Kirim Blanko</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const addrs = JSON.parse('<?= str_replace("'", "\'", json_encode($address_list)); ?>');
</script>