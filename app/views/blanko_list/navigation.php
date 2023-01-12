<?php $blanko_status = array(
    'available' => array(
        'title' => 'Blanko Tersedia',
        'status' => '',
        'link' => 'blanko',
        'icon' => 'fas fa-check-circle',
        'bg' => 'primary'
    ),
    'used' => array(
        'title' => 'Blanko Terpakai',
        'status' => '2',
        'link' => 'blanko/used',
        'icon' => 'fas fa-sync-alt',
        'bg' => 'olive'
    ),
    'crash' => array(
        'title' => 'Revisi dan Rusak',
        'status' => '34',
        'link' => 'blanko/crash',
        'icon' => 'fas fa-ban',
        'bg' => 'danger'
    )
);
$status = (isset($status) && array_key_exists($status, $blanko_status)) ? $status : 'available'; ?>
<div class="card">
    <div class="card-body text-center pb-1">
        <?php foreach ($blanko_status as $key => $button) : ?>
            <a href="<?= base_url($button['link']); ?>" class="btn btn-app pt-2 px-3<?= ($key == $status) ? ' bg-' . $button['bg'] : ''; ?>">
                <i class="<?= $button['icon']; ?> mb-2"></i><?= $button['title']; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<script>
    const statusblanko = '<?= $blanko_status[$status]['status']; ?>';
</script>