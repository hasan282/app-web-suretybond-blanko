<div class="card mb-1">
    <div class="card-body py-3">
        <div class="row">
            <div class="col">
                <div class="btn-group btn-group-toggle mb-md-0 mb-2" data-toggle="buttons">
                    <?php $list_option = array(
                        ['value' => '10', 'id' => 'list_10', 'text' => '10'],
                        ['value' => '20', 'id' => 'list_20', 'text' => '20'],
                        ['value' => '50', 'id' => 'list_50', 'text' => '50'],
                        ['value' => '100', 'id' => 'list_100', 'text' => '100']
                    );
                    foreach ($list_option as $lo) :
                        $actives = ($lo['value'] == '10'); ?>
                        <label class="btn btn-secondary<?= ($actives) ? ' active' : ''; ?>">
                            <input <?= ($actives) ? 'checked ' : ''; ?>type="radio" id="<?= $lo['id']; ?>" value="<?= $lo['value']; ?>" name="dataview" autocomplete="off"><?= $lo['text']; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col"></div>
            <div class="col">


                <div class="form-group m-0 mw-300 ml-auto">
                    <div class="input-group">
                        <input type="search" id="searchnumber" class="form-control" placeholder="Cari Nomor Blanko">
                        <div class="input-group-append">
                            <button type="button" id="searchbutton" class="btn btn-default"><i class="fa fa-search mx-2"></i></button>
                        </div>
                    </div>
                </div>

                <!-- 
                <div class="mw-300 ml-auto">
                    <div class="d-flex">
                        <div class="input-group">
                            <input type="text" id="blanko_number" class="form-control" placeholder="Cari Nomor Blanko">
                            <div class="input-group-append">
                                <button type="button" id="btn_search" class="btn btn-default"><i class="fas fa-search mx-3"></i></button>
                            </div>
                        </div>
                        <button class="btn btn-link pr-0 clear-filter"><i class="fas fa-times"></i></button>
                    </div>
                </div>

 -->


            </div>
        </div>
    </div>
</div>
<div class="card mb-1">
    <div class="overlay" id="loading"></div>
    <div class="card-header">
        <h3 class="card-title">Hasil Pencarian <strong id="count_data">0</strong> Data Blanko</h3>
    </div>
    <div class="card-body" id="blanko_list">
        <div class="text-muted d-flex justify-content-center" style="min-height:20vh;opacity:0.3">
            <i class="far fa-file-alt fa-5x my-auto"></i>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body py-3">
        <div class="row">
            <div class="col-md d-flex">
                <p class="my-auto mx-md-2 mx-auto">
                    <span class="text-muted">Halaman </span><span id="halaman" class="text-bold">1</span><span class="text-muted"> dari </span><span id="halaman_max" class="text-bold">1</span>
                </p>
            </div>
            <div class="col-md text-center">
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
            <div class="col-md"></div>
        </div>
    </div>
</div>