<?php $card_theme = array('Tersedia' => 'primary', 'Terpakai' => 'success', 'Rusak' => 'danger'); ?>
<div class="card card-outline card-<?= $card_theme[$head_name]; ?>">
    <div class="overlay" id="loading"></div>
    <div class="card-header">
        <h3 class="card-title">Blanko <?= $head_name; ?> <strong id="count_data">0</strong> Data</h3>
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
</div>
</div>