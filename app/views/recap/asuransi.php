<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rekap Data Blanko</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="border-right">Nama Asuransi</th>
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
                foreach ($report as $key => $rp) :
                    $total_available += intval($rp['available']);
                    $total_used += intval($rp['used']);
                    $total_crash += intval($rp['crash']);
                    $total_data = intval($rp['available']) + intval($rp['used']) + intval($rp['crash']);
                    $total_all_data += $total_data; ?>
                    <tr class="tr-link" data-direct="<?= $rp['id']; ?>">
                        <td class="text-center text-bold"><?= $key + 1; ?></td>
                        <td class="border-right"><?= $rp['asuransi']; ?></td>
                        <td class="text-center"><?= self_number_format($rp['available']); ?></td>
                        <td class="text-center"><?= self_number_format($rp['used']); ?></td>
                        <td class="text-center"><?= self_number_format($rp['crash']); ?></td>
                        <td class="text-center text-bold border-left"><?= self_number_format($total_data); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="text-center text-bold">
                    <td colspan="2" class="border-right">TOTAL</td>
                    <td><?= self_number_format($total_available); ?></td>
                    <td><?= self_number_format($total_used); ?></td>
                    <td><?= self_number_format($total_crash); ?></td>
                    <td class="border-left"><?= self_number_format($total_all_data); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function() {
        $('.tr-link').on('click', function() {
            window.location.href = '<?= base_url('recap/in/'); ?>' + $(this).data('direct');
        });
    });
</script>