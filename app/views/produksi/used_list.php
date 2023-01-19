<form action="<?= base_url(uri_string()) ?>" method="POST">
    <div class="card">
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blankodata['data'] as $bd) : ?>
                        <tr>
                            <td class="py-0 text-center align-middle">
                                <div class="icheck-primary">
                                    <input type="checkbox" class="checkone" name="check_<?= $bd['enkrip_use']; ?>" id="check_<?= $bd['enkrip_use']; ?>">
                                    <label for="check_<?= $bd['enkrip_use']; ?>"></label>
                                </div>
                            </td>
                            <td class="text-center"><span class="text-secondary"><?= $bd['prefix']; ?></span><span class="text-bold"><?= $bd['nomor']; ?></span></td>
                            <td class="text-center border-right text-bold text-<?= $bd['color']; ?>"><?= $bd['rev_status']; ?></td>
                            <td class="border-right"><?= $bd['jaminan']; ?></td>
                            <td><?= $bd['principal']; ?></td>
                            <td class="text-center border-left text-bold"><?= $bd['office_nick']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php $datenow = date('Y-m-d');
    $dateshow = format_date($datenow, 'MM3 YY2'); ?>
    <div class="mw-400 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
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
</form>
<script>
    $(function() {
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