<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rekap Data Blanko <?= $asuransi->nama; ?></h3>
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
                foreach ($recap as $rp) :
                    $total_available += intval($rp['available']);
                    $total_used += intval($rp['used']);
                    $total_crash += intval($rp['crash']);
                    $total_data = intval($rp['available']) + intval($rp['used']) + intval($rp['crash']);
                    $total_all_data += $total_data; ?>
                    <tr class="tr-link" data-direct="<?= urlencode(self_encrypt($params . $rp['tanggal'])); ?>">
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
<?php $uri_direct = str_replace('insurance', 'list', uri_string()) . '?var=';
if (isset($direct)) $uri_direct = $direct . '?var='; ?>
<script>
    $(function() {
        $('.tr-link').on('click', function() {
            window.location.href = '<?= base_url($uri_direct); ?>' + $(this).data('direct');
        });
    });
</script>