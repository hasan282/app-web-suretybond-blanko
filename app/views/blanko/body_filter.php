<div class="row">
    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Data</h3>
            </div>
            <div class="card-body">
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
        </div>