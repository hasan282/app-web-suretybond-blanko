<div class="card">
    <div class="card-body text-center pb-1">
        <?php $id_status = '';
        $buttons = array(
            ['name' => 'Tersedia', 'status' => '', 'link' => 'blanko', 'icon' => 'fas fa-check-circle', 'bg' => 'primary'],
            ['name' => 'Terpakai', 'status' => 2, 'link' => 'blanko/used', 'icon' => 'fas fa-sync-alt', 'bg' => 'olive'],
            ['name' => 'Rusak', 'status' => 3, 'link' => 'blanko/crash', 'icon' => 'fas fa-ban', 'bg' => 'danger']
        );
        foreach ($buttons as $btn) :
            if ($head_name == $btn['name']) $id_status = $btn['status']; ?>
            <a href="<?= base_url($btn['link']); ?>" class="btn btn-app pt-2 px-3<?= ($head_name == $btn['name']) ? ' bg-' . $btn['bg'] : ''; ?>"><i class="<?= $btn['icon']; ?> mb-2"></i>Blanko <?= $btn['name']; ?></a>
        <?php endforeach; ?>
    </div>
</div>
<script>
    const statusblanko = '<?= $id_status; ?>';
</script>