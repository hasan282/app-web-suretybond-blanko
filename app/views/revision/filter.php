<?php $office_id = get_user_office($this->session->userdata('id'))->id;
if (isset($office)) $office_id = $office;
if (isset($officedata)) $office_id = $officedata['id']; ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title text-secondary">Filter dan Pencarian Data</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="form-group">
                    <label for="asuransi">Asuransi</label>
                    <div class="d-flex">
                        <select id="asuransi" class="form-control">
                            <option value="">---</option>
                            <?php $asuransi_list = $this->db->query('SELECT enkripsi, nama FROM asuransi WHERE is_active = 1 ORDER BY nama ASC')->result_array();
                            foreach ($asuransi_list as $al) : ?>
                                <option value="<?= $al['enkripsi']; ?>"><?= $al['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-link pr-0 clear-filter show-tooltip" data-clear="asuransi" title="Reset Asuransi"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="form-group">
                    <label for="blanko_number">Nomor Blanko</label>
                    <div class="d-flex">
                        <div class="input-group">
                            <input type="text" id="blanko_number" class="form-control" placeholder="Nomor Blanko">
                            <div class="input-group-append">
                                <button type="button" id="btn_search" class="btn btn-default"><i class="fas fa-search mr-2"></i>Search</button>
                            </div>
                        </div>
                        <button class="btn btn-link pr-0 clear-filter show-tooltip" data-clear="number_search,blanko_number" title="Reset Nomor"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <input type="hidden" id="number_search">
            </div>
            <div class="col-xl-4 col-md-6 mx-auto">
                <div class="form-group">
                    <label for="principal">Principal</label>
                    <div class="d-flex">
                        <select id="principal" class="form-control">
                            <option value="">---</option>
                            <?php $principal_list = $this->db->query('SELECT id, nama FROM principal WHERE id_office = ? ORDER BY nama ASC', $office_id)->result_array();
                            foreach ($principal_list as $pl) : ?>
                                <option value="<?= $pl['id']; ?>"><?= $pl['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-link pr-0 clear-filter show-tooltip" data-clear="principal" title="Reset Principal"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>