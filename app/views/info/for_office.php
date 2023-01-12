<div class="mw-900 mx-auto">
    <div class="card">
        <div class="card-body text-center">
            <h6 class="my-0 text-bold text-info"><i class="fas fa-info-circle mr-2"></i>Blanko digunakan untuk <?= $office['nama']; ?></h6>
        </div>
    </div>
</div>
<script>
    const src_to = 'uses/in/$id?var=<?= urlencode($this->input->get('var')); ?>';
</script>