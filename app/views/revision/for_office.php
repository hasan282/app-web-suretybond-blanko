<div class="mw-900 mx-auto">
    <div class="card">
        <div class="card-body text-center">
            <h6 class="my-0 text-bold text-info">
                <i class="fas fa-info-circle mr-2"></i>Revisi penggunaan Blanko dari <?= $officedata['nama']; ?>
            </h6>
        </div>
    </div>
</div>
<input type="hidden" id="var_value" value="<?= urlencode($this->input->get('var')); ?>">