<?php $is_null = ($blanko['produksi'] === null); ?>
<div class="mw-400 mx-auto">
    <div class="card">
        <div class="card-body">
            <?php if (!$is_null) : ?>
                <div id="title_box" class="text-center">
                    <h6 class="text-bold text-secondary mb-0">
                        Laporan Produksi
                        <a href="<?= base_url('production/report?val=' . urlencode(self_encrypt($blanko['produksi']))) ?>">
                            <?= format_date($blanko['produksi'] . '-1', 'MM3 YY2'); ?>
                        </a>
                        <span id="edit_month" class="ml-2 link-transparent show-tooltip" title="Ubah Bulan"><i class="fas fa-edit"></i></span>
                    </h6>
                </div>
            <?php endif; ?>
            <?php $datenow = ($is_null) ? date('Y-m-d') : $blanko['produksi'] . '-01';
            $dateshow = format_date($datenow, 'MM3 YY2'); ?>
            <div id="setup_box" class="text-center<?= ($is_null) ? '' : ' zero-height'; ?>">
                <?php if (!$is_null) : ?>
                    <button id="cancel_btn" type="button" class="btn btn-default btn-sm mb-4"><i class="fas fa-times mr-2"></i>Batalkan</button>
                <?php endif; ?>
                <form action="<?= base_url('production/setmonth'); ?>" method="POST">
                    <input type="hidden" id="blanko" name="blanko" value="<?= self_md5($blanko['id']); ?>">
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
                    <button type="submit" id="submitreport" class="btn btn-primary text-bold">
                        <i class="fas fa-external-link-square-alt mr-2"></i>Jadikan Laporan <span id="btnmonth"><?= $dateshow; ?></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        <?php if (!$is_null) : ?>
            $('#edit_month').click(function() {
                $('#title_box').addClass('zero-height');
                $('#setup_box').removeClass('zero-height');
            });
            $('#cancel_btn').click(function() {
                $('#setup_box').addClass('zero-height');
                $('#title_box').removeClass('zero-height');
            });
        <?php endif; ?>
        $('#prevmonth').click(function() {
            change_month(-1)
        });
        $('#nextmonth').click(function() {
            change_month(1)
        });
    });

    function change_month(change) {
        const mn = ('Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember').split(',');
        const dt = ($('#month').val()).split('-');
        let year = parseInt(dt[0]);
        let month = parseInt(dt[1]);
        month += change;
        if (month > 12) {
            month -= 12;
            year += 1;
        }
        if (month < 1) {
            month += 12;
            year -= 1;
        }
        $('#showmonth').val(mn[month - 1] + ' ' + year);
        $('#btnmonth').html(mn[month - 1] + ' ' + year);
        if (month < 10) month = '0' + month;
        $('#month').val(year + '-' + month);
    }
</script>