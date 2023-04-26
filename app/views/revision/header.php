<div class="card">
    <div class="card-body">
        <p class="text-info">Pemakaian Blanko yang direvisi :</p>
        <?php $asuransi = $this->db->query('SELECT asuransi.nama AS asuransi FROM blanko INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id WHERE blanko.id = ?', $blanko['id'])->row(); ?>
        <p class="my-0"><?= $asuransi->asuransi; ?></p>
        <h4 class="mb-0">
            <span class="text-secondary"><?= $blanko['prefix']; ?></span><a href="<?= base_url('blanko/detail/' . $params) ?>" class="text-bold"><?= $blanko['nomor']; ?></a>
        </h4>
    </div>
</div>
<script>
    const src_to = '<?= str_replace('revision/b', 'revision/in', uri_string()); ?>?var=$id';
</script>