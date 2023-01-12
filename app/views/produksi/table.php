<div class="card">
    <div class="card-header">
        <h3 class="card-title">Blanko Terpakai Bulan <span class="text-bold"><?= format_date($report['select'] . '-01', 'MM3 YY2'); ?></span></h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Nomor Blanko</th>
                    <th class="text-center">Status</th>
                    <th>Nomor Jaminan</th>
                    <th>Principal</th>
                    <th class="text-center">Pemakai</th>
                    <th class="text-center border-left"><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report['report'] as $num => $rp) : ?>
                    <tr>
                        <td class="text-center text-bold"><?= $num + 1; ?></td>
                        <td class="text-center"><span class="text-secondary"><?= $rp['prefix']; ?></span><span class="text-bold"><?= $rp['nomor']; ?></span></td>
                        <td class="text-center"><?= $rp['rev_status']; ?></td>
                        <td><?= $rp['jaminan']; ?></td>
                        <td><?= $rp['principal']; ?></td>
                        <td class="text-center"><?= $rp['office_nick']; ?></td>
                        <td class="text-center">-</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!--  
<div class="card">
    <div class="card-body">

      

    </div>
</div>
-->