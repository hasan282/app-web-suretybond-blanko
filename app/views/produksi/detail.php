<div class="mw-400 mx-auto">
    <div class="card">
        <div class="card-body">
            <?php if ($blanko['produksi'] != null) : ?>
                <div class="text-center">
                    <h6 class="text-bold text-secondary mb-0">
                        Laporan Produksi <a href=""><?= format_date($blanko['produksi'] . '-1', 'MM3 YY2'); ?></a>
                    </h6>
                </div>
            <?php endif; ?>

            <?php $datenow = date('Y-m-d');
            $dateshow = format_date($datenow, 'MM3 YY2'); ?>
            <div class="text-center<?= ($blanko['produksi'] == null) ? '' : ' zero-height'; ?>">
                <input type="hidden" id="month" name="month" value="<?= format_date($datenow, 'YY2-MM1'); ?>">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <button type="button" id="prevmonth" class="btn btn-default"><i class="fas fa-chevron-left"></i></button>
                    </div>
                    <input type="text" id="showmonth" class="form-control text-center" value="<?= $dateshow; ?>" readonly>
                    <div class="input-group-append">
                        <button type="button" id="nextmonth" class="btn btn-default"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <button type="submit" id="submitreport" class="btn btn-primary text-bold" disabled>
                    <i class="fas fa-external-link-square-alt mr-2"></i>Jadikan Laporan <span id="btnmonth"><?= $dateshow; ?></span>
                </button>
            </div>

        </div>
    </div>
</div>