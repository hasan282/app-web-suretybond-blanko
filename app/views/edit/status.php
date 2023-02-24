<?php
$bdata = $statusedit;
$disable_avail = ($bdata['status_id'] == '1');
$disable_used = ($bdata['status_id'] == '2');
$disable_crash = ($bdata['status_id'] == '3');
$disable_revs = ($bdata['status_id'] == '4' || $bdata['used'] == null);
$buttons = array(
    '1' => ['name' => 'Tersedia', 'color' => 'primary', 'disable' => $disable_avail],
    '2' => ['name' => 'Terpakai', 'color' => 'success', 'disable' => $disable_used],
    '3' => ['name' => 'Rusak', 'color' => 'danger', 'disable' => $disable_crash],
    '4' => ['name' => 'Revisi', 'color' => 'secondary', 'disable' => $disable_revs]
);
?>
<div class="text-center mb-3">
    <a href="<?= base_url('blanko/detail/' . $blanko['enkripsi']); ?>" class="btn btn-default btn-sm">
        <i class="fas fa-times mr-2"></i>Batalkan
    </a>
</div>
<div class="mx-auto mw-600">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <?php foreach ($buttons as $sta => $btn) : ?>
                    <div class="col-6 col-sm-3">
                        <button id="button<?= $sta; ?>" <?= ($btn['disable']) ? 'disabled ' : ''; ?>class="btn btn-<?= ($btn['disable']) ? 'default' : $btn['color']; ?> btn-block text-bold showopt"><?= $btn['name']; ?></button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="box1" class="text-center zero-height">
                <!-- zero-height -->
                <?php $change1 = 'status=>1'; ?>
                <hr>
                <h6 class="text-bold mt-4">
                    <span class="border-fade py-2 px-3 mr-2 text-<?= $blanko['color']; ?>"><?= $blanko['status']; ?></span>
                    <i class="fas fa-angle-double-right text-fade"></i>
                    <i class="fas fa-angle-double-right text-secondary"></i>
                    <i class="fas fa-angle-double-right"></i>
                    <span class="border-fade py-2 px-3 ml-2 text-primary">Tersedia</span>
                </h6>
                <p class="mt-4 text-secondary">Ubah status menjadi Tersedia ?</p>
                <div class="mx-auto mw-400 text-left">
                    <?php if ($bdata['crash'] != null) :
                        $change1 .= ',crash=>' . $bdata['crash']; ?>
                        <p class="text-secondary mb-1"><i class="fas fa-minus-circle text-danger mr-2"></i>Laporan Blanko Rusak akan dihapus.</p>
                    <?php endif; ?>
                    <?php if ($bdata['used'] != null) :
                        $change1 .= ',used=>' . $bdata['used']; ?>
                        <p class="text-secondary mb-1"><i class="fas fa-minus-circle text-danger mr-2"></i>Laporan Terpakai akan dihapus.</p>
                    <?php endif; ?>
                    <?php if ($bdata['jaminan'] != null) :
                        $change1 .= ',jaminan=>' . $bdata['jaminan']; ?>
                        <p class="text-secondary mb-1"><i class="fas fa-minus-circle text-danger mr-2"></i>Data Jaminan yang tersimpan akan dihapus.</p>
                    <?php endif; ?>
                    <?php if ($bdata['produksi'] != null) :
                        $change1 .= ',produksi=>null'; ?>
                        <p class="text-secondary mb-1"><i class="fas fa-minus-circle text-danger mr-2"></i><?= $blanko['prefix'] . $blanko['nomor']; ?> akan dihapus dari Laprod. <?= format_date($bdata['produksi'] . '-01', 'MM2. YY2'); ?></p>
                    <?php endif; ?>
                    <?php if ($bdata['from_id'] != null) :
                        $change1 .= ',from=>' . $bdata['from_id'];
                        $revisi_from = $this->db->get_where('blanko', ['id' => $bdata['from_id']])->row(); ?>
                        <p class="text-secondary mb-1"><i class="fas fa-info-circle text-info mr-2"></i>Status <?= $revisi_from->prefix . $revisi_from->nomor; ?> akan berubah menjadi Terpakai.</p>
                    <?php endif;
                    if ($bdata['to_id'] != null) $change1 .= ',to=>' . $bdata['to_id']; ?>
                </div>
                <form method="POST">
                    <input type="hidden" name="statuschange" value="<?= self_encrypt($change1); ?>">
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-undo-alt mr-2"></i>Ubah menjadi Tersedia</button>
                        <button type="button" class="btn btn-default ml-1 cancelbtn" data-target="box1"><i class="fas fa-times fa-fw"></i></button>
                    </div>
                </form>
            </div>

            <div id="box2" class="text-center zero-height">
                <hr>
                <h6 class="text-bold mt-4">
                    <span class="border-fade py-2 px-3 mr-2 text-<?= $blanko['color']; ?>"><?= $blanko['status']; ?></span>
                    <i class="fas fa-angle-double-right text-fade"></i>
                    <i class="fas fa-angle-double-right text-secondary"></i>
                    <i class="fas fa-angle-double-right"></i>
                    <span class="border-fade py-2 px-3 ml-2 text-success">Terpakai</span>
                </h6>
                <p class="mt-4 text-secondary">Ubah status menjadi Terpakai ?</p>
                <h6 class="text-bold mt-4">
                    <span class="border-fade py-2 px-3 mr-2 text-secondary">Under Construct</span>
                </h6>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-default ml-1 cancelbtn" data-target="box2"><i class="fas fa-times fa-fw"></i></button>
                </div>
            </div>

            <div id="box3" class="text-center zero-height">
                <hr>
                <h6 class="text-bold mt-4">
                    <span class="border-fade py-2 px-3 mr-2 text-<?= $blanko['color']; ?>"><?= $blanko['status']; ?></span>
                    <i class="fas fa-angle-double-right text-fade"></i>
                    <i class="fas fa-angle-double-right text-secondary"></i>
                    <i class="fas fa-angle-double-right"></i>
                    <span class="border-fade py-2 px-3 ml-2 text-danger">Rusak</span>
                </h6>
                <p class="mt-4 text-secondary">Ubah status menjadi Rusak ?</p>
                <h6 class="text-bold mt-4">
                    <span class="border-fade py-2 px-3 mr-2 text-secondary">Under Construct</span>
                </h6>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-default ml-1 cancelbtn" data-target="box3"><i class="fas fa-times fa-fw"></i></button>
                </div>
            </div>

            <div id="box4" class="text-center zero-height">
                <hr>
                <h6 class="text-bold mt-4">
                    <span class="border-fade py-2 px-3 mr-2 text-<?= $blanko['color']; ?>"><?= $blanko['status']; ?></span>
                    <i class="fas fa-angle-double-right text-fade"></i>
                    <i class="fas fa-angle-double-right text-secondary"></i>
                    <i class="fas fa-angle-double-right"></i>
                    <span class="border-fade py-2 px-3 ml-2 text-secondary">Revisi</span>
                </h6>
                <p class="mt-4 text-secondary">Ubah status menjadi Revisi ?</p>
                <h6 class="text-bold mt-4">
                    <span class="border-fade py-2 px-3 mr-2 text-secondary">Under Construct</span>
                </h6>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-default ml-1 cancelbtn" data-target="box4"><i class="fas fa-times fa-fw"></i></button>
                </div>
            </div>

            <!--  
            <textarea disabled class="form-control mt-3" rows="3"><?= $change1; ?></textarea>
            <textarea disabled class="form-control mt-3" rows="3"><?= self_encrypt($change1); ?></textarea>
            -->
            <?php
            // var_dump($bdata);
            // var_dump($blanko);
            ?>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.showopt').click(function() {
            const to_show = '#' + $(this).attr('id').replace('button', 'box');
            $(to_show).removeClass('zero-height').siblings('div:not(.row)').addClass('zero-height');
        });
        $('.cancelbtn').click(function() {
            const to_close = '#' + $(this).data('target');
            $(to_close).addClass('zero-height');
        });
    });
</script>