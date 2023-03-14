<?php $month_list = 'Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember';
$months = explode(',', $month_list);
$datenow = date('Y-m-d');
$dateshow = format_date($datenow, 'MM3 YY2');
$datesplit = explode('-', $datenow);
$yearnow = intval(date('Y')) + 1;
$yearfrom = 2015; ?>
<form action="<?= base_url(uri_string()) ?>" method="POST">
    <div class="card mb-2">
        <div class="card-header">
            <h3 class="card-title">Blanko Terpakai <span class="text-bold text-olive"><?= $blankodata['page']; ?></span> Data</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center py-0 align-middle">
                            <div class="icheck-secondary">
                                <input type="checkbox" id="checkall">
                                <label for="checkall"></label>
                            </div>
                        </th>
                        <th class="text-center">Nomor Blanko</th>
                        <th class="text-center border-right">Status</th>
                        <th>Nomor Jaminan</th>
                        <th>Principal</th>
                        <th class="text-center border-left">Pemakai</th>
                        <th class="text-center">Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blankodata['data'] as $bd) : ?>
                        <tr>
                            <td class="py-0 text-center align-middle">
                                <div class="icheck-primary">
                                    <input type="checkbox" class="checkone" name="check_<?= $bd['enkripsi']; ?>" id="check_<?= $bd['enkripsi']; ?>">
                                    <label for="check_<?= $bd['enkripsi']; ?>"></label>
                                </div>
                            </td>
                            <td class="text-center"><span class="text-secondary"><?= $bd['prefix']; ?></span><span class="text-bold"><?= $bd['nomor']; ?></span></td>
                            <td class="text-center border-right text-bold text-<?= $bd['color']; ?>"><?= $bd['status']; ?></td>
                            <td class="border-right"><?= ($bd['jaminan'] == null) ? '-' : $bd['jaminan']; ?></td>
                            <td><?= ($bd['principal'] == null) ? '-' : $bd['principal']; ?></td>
                            <td class="text-center border-left text-bold"><?= $bd['office_nick']; ?></td>
                            <td class="text-center"><?= $bd['pemakaian'] == null ? '-' : format_date($bd['pemakaian'] . '-01', 'MM2 YY2'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mw-400 mx-auto">
        <div class="card">
            <div class="card-body p-2">
                <div class="text-center">
                    <input type="hidden" id="month" name="month" value="<?= format_date($datenow, 'YY2-MM1'); ?>">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button type="button" id="prevmonth" class="btn btn-default"><i class="fas fa-chevron-left"></i></button>
                        </div>
                        <select id="select_month" class="form-control">
                            <?php foreach ($months as $num => $mnt) : ?>
                                <option <?= (intval($datesplit[1]) == $num + 1) ? 'selected ' : ''; ?>value="<?= str_pad(strval($num + 1), 2, '0', STR_PAD_LEFT); ?>">
                                    <?= $mnt; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="select_year" class="form-control">
                            <?php for ($yr = $yearnow; $yr >= $yearfrom; $yr--) : ?>
                                <option <?= (intval($datesplit[0]) == $yr) ? 'selected ' : ''; ?>value="<?= $yr; ?>"><?= $yr; ?></option>
                            <?php endfor; ?>
                        </select>

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
</form>
<script>
    $(function() {
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
        const mn = ('<?= implode(',', $months); ?>').split(',');
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