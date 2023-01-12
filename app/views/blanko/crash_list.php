<div class="card">
    <div class="card-header">
        <h3 class="card-title">Filter Data</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl col-lg-6 mx-auto">
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
                        <button class="btn btn-link pr-0 clear-filter" data-clear="asuransi"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-xl col-lg-6 mx-auto">
                <div class="form-group">
                    <label for="blanko_number">Nomor Blanko</label>
                    <div class="d-flex">
                        <div class="input-group">
                            <input type="text" id="blanko_number" class="form-control" placeholder="Nomor Blanko">
                            <div class="input-group-append">
                                <button type="button" id="btn_search" class="btn btn-default"><i class="fas fa-search mr-2"></i>Search</button>
                            </div>
                        </div>
                        <button class="btn btn-link pr-0 clear-filter" data-clear="number_search,blanko_number"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <input type="hidden" id="number_search">
            </div>
            <div class="col-xl col-lg-6 mx-auto">
                <div class="form-group">
                    <label for="status">Status Pemakaian</label>
                    <select id="status" class="form-control">
                        <option value="12" selected>Semua</option>
                        <option value="1">Tersedia</option>
                        <option value="2">Sudah Terpakai</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="overlay" id="loading"></div>
    <div class="card-header">
        <h3 class="card-title">Total <strong id="count_data">0</strong> Data Blanko</h3>
    </div>
    <div class="card-body" id="blanko_list">
        <div class="text-muted d-flex justify-content-center" style="min-height:30vh;opacity:0.3">
            <i class="far fa-file-alt fa-5x my-auto"></i>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md d-flex">
                <p class="my-auto mx-md-2 mx-auto">
                    <span class="text-muted">Halaman </span><span id="halaman" class="text-bold">1</span><span class="text-muted"> dari </span><span id="halaman_max" class="text-bold">1</span>
                </p>
            </div>
            <div class="col-md text-md-right text-center">
                <div class="btn-group mt-md-0 mt-4">
                    <button type="button" class="btn btn-secondary data-nav" data-page="first" disabled>
                        <i class="fas fa-angle-double-left"></i>
                    </button>
                    <button type="button" class="btn btn-secondary data-nav" data-page="prev" disabled>
                        <i class="fas fa-angle-left"></i>
                    </button>
                    <button type="button" class="btn btn-secondary data-nav" data-page="next" disabled>
                        <i class="fas fa-angle-right"></i>
                    </button>
                    <button type="button" class="btn btn-secondary data-nav" data-page="last" disabled>
                        <i class="fas fa-angle-double-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>