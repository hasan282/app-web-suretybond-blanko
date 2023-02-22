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
            $datesplit = explode('-', $datenow);
            $dateshow = format_date($datenow, 'MM3 YY2'); ?>
            <div id="setup_box" class="text-center<?= ($is_null) ? '' : ' zero-height'; ?>">
                <?php if (!$is_null) : ?>
                    <button id="cancel_btn" type="button" class="btn btn-default btn-sm mb-4"><i class="fas fa-times mr-2"></i>Batalkan</button>
                <?php endif; ?>
                <form action="<?= base_url('production/setmonth'); ?>" method="POST">
                    <input type="hidden" id="blanko" name="blanko" value="<?= self_md5($blanko['id']); ?>">
                    <input type="hidden" id="month" name="month" value="<?= format_date($datenow, 'YY2-MM1'); ?>">
                    <input type="hidden" id="showmonth" value="<?= $dateshow; ?>">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button type="button" id="prevmonth" class="btn btn-default"><i class="fas fa-chevron-left"></i></button>
                        </div>
                        <select id="select_month" class="form-control">
                            <?php $months = explode(',', 'Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember');
                            foreach ($months as $num => $mnt) : ?>
                                <option <?= (intval($datesplit[1]) == $num + 1) ? 'selected ' : ''; ?>value="<?= str_pad(strval($num + 1), 2, '0', STR_PAD_LEFT); ?>">
                                    <?= $mnt; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="select_year" class="form-control">
                            <?php $yearnow = intval(date('Y')) + 1;
                            // $yearfrom = $yearnow - 10;
                            $yearfrom = 2015;
                            for ($yr = $yearnow; $yr >= $yearfrom; $yr--) : ?>
                                <option <?= (intval($datesplit[0]) == $yr) ? 'selected ' : ''; ?>value="<?= $yr; ?>"><?= $yr; ?></option>
                            <?php endfor; ?>
                        </select>
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
        $('#select_month').change(function() {
            change_month(0, $(this).val());
        });
        $('#select_year').change(function() {
            change_month(0, null, $(this).val());
        });
    });

    function change_month(change, setmonth = null, setyear = null) {
        const mn = ('Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember').split(',');
        const dt = ($('#month').val()).split('-');
        let year = parseInt(dt[0]);
        if (setyear != null) year = parseInt(setyear);
        let month = parseInt(dt[1]);
        if (setmonth != null) month = parseInt(setmonth);
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
        if (setmonth == null && setyear == null) {
            $('#select_month').val(month);
            $('#select_year').val(year);
        }
        $('#month').val(year + '-' + month);
    }
</script>