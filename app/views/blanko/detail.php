<?php $rev_to = $this->db->query('SELECT enkripsi, prefix, nomor FROM revisi INNER JOIN blanko ON blanko.id = revisi.id_to WHERE revisi.id_from = ?', $blanko['id'])->row();
$rev_from = $this->db->query('SELECT enkripsi, prefix, nomor FROM revisi INNER JOIN blanko ON blanko.id = revisi.id_from WHERE revisi.id_to = ?', $blanko['id'])->row(); ?>
<div class="card mw-600 mx-auto">
    <div class="card-body">
        <div class="text-center">
            <h5><?= $blanko['asuransi']; ?></h5>
            <h3 class="mb-3"><span class="text-secondary"><?= $blanko['prefix']; ?></span><b><?= $blanko['nomor']; ?></b></h3>
            <h6 class="mb-0">
                Status : <span class="text-bold text-<?= $blanko['color']; ?>"><?= $blanko['status']; ?></span>
                <?php isset($true_office) ?: $true_office = false;
                if ($true_office) : ?>
                    <a href="<?= base_url('edit/status/' . self_md5($blanko['id'])); ?>" class="ml-1 link-muted show-tooltip" title="Ubah Status"><i class="fas fa-edit"></i></a>
                <?php endif; ?>
            </h6>
            <?php if ($rev_to != null) : ?>
                <hr>
                <p class="my-0 text-bold text-secondary">Direvisi ke <a href="<?= base_url('blanko/detail/' . $rev_to->enkripsi); ?>"><?= $rev_to->prefix . $rev_to->nomor; ?></a></p>
            <?php endif; ?>
            <?php if ($rev_from != null) : ?>
                <hr>
                <p class="my-0 text-bold text-secondary">Cetakan Revisi Dari <a href="<?= base_url('blanko/detail/' . $rev_from->enkripsi); ?>"><?= $rev_from->prefix . $rev_from->nomor; ?></a></p>
            <?php endif; ?>
        </div>
    </div>
</div>