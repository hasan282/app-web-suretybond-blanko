<?php $query = 'SELECT date_sent AS tanggal, ';
$query .= 'MIN(nomor) AS mins, MAX(nomor) AS maxs, SUM(CASE WHEN id_status = 1 THEN 1 ELSE 0 END) AS available, ';
$query .= 'SUM(CASE WHEN id_status = 2 THEN 1 ELSE 0 END) AS used, SUM(CASE WHEN id_status = 3 OR id_status = 4 THEN 1 ELSE 0 END) AS crash ';
$query .= 'FROM (SELECT @prev := 0) AS vars, (SELECT @group := 1) AS groups, (SELECT id, enkripsi, prefix, nomor, id_status, date_in AS date_sent, ';
$query .= '(CASE WHEN (@prev - @prev := nomor) = -1 THEN @group ELSE @group := @group + 1 END) AS grps FROM blanko ';
$query .= 'WHERE id_asuransi = ? ORDER BY nomor ASC) AS blankos GROUP BY grps, date_sent ORDER BY date_sent DESC';
$recap_arrive = $this->db->query($query, $asuransi->id)->result_array(); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rekap Data Blanko</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Tanggal Terima Blanko</th>
                    <th class="text-center border-right">Nomor Blanko</th>
                    <th class="text-center">Blanko <span class="text-primary">Tersedia</span></th>
                    <th class="text-center">Blanko <span class="text-olive">Terpakai</span></th>
                    <th class="text-center"><span class="text-danger">Rusak</span> dan <span class="text-secondary">Revisi</span></th>
                    <th class="text-center border-left">Total Data</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_available = 0;
                $total_used = 0;
                $total_crash = 0;
                $total_all_data = 0;
                foreach ($recap_arrive as $rp) :
                    $total_available += intval($rp['available']);
                    $total_used += intval($rp['used']);
                    $total_crash += intval($rp['crash']);
                    $total_data = intval($rp['available']) + intval($rp['used']) + intval($rp['crash']);
                    $total_all_data += $total_data; ?>
                    <tr class="tr-link arrivelink" data-direct="<?= urlencode(self_encrypt($rp['mins'] . '_and_' . $rp['maxs'])); ?>">
                        <td class="text-bold"><?= format_date($rp['tanggal']); ?></td>
                        <td class="text-center border-right border-left"><?= ($rp['mins'] == $rp['maxs']) ? $rp['mins'] : $rp['mins'] . ' - ' . $rp['maxs']; ?></td>
                        <td class="text-center"><?= self_number_format($rp['available']); ?></td>
                        <td class="text-center"><?= self_number_format($rp['used']); ?></td>
                        <td class="text-center"><?= self_number_format($rp['crash']); ?></td>
                        <td class="text-center text-bold border-left"><?= self_number_format($total_data); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="text-bold">
                    <td colspan="2" class="text-center border-right">TOTAL</td>
                    <td class="text-center"><?= self_number_format($total_available); ?></td>
                    <td class="text-center"><?= self_number_format($total_used); ?></td>
                    <td class="text-center"><?= self_number_format($total_crash); ?></td>
                    <td class="text-center border-left"><?= self_number_format($total_all_data); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function() {
        $('.arrivelink').on('click', function() {
            window.location.href = '<?= base_url('recap/b/' . $asuransi->enkripsi . '?var='); ?>' + $(this).data('direct');
        });
    });
</script>