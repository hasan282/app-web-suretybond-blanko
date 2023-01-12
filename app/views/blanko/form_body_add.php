<label class="mb-0 text-secondary" for="number_from"><small>Nomor Blanko</small></label>
<div class="row mw-600 pt-3 pr-3 border-fade">
    <?php $input_blanko = array(
        ['id' => 'number_from', 'label' => 'Dari'],
        ['id' => 'number_to', 'label' => 'Sampai']
    );
    foreach ($input_blanko as $ib) : ?>
        <div class="col-md">
            <div class="form-group row">
                <label for="<?= $ib['id']; ?>" class="col-3 col-form-label text-right"><?= $ib['label']; ?></label>
                <div class="input-group col-9">
                    <div class="input-group-prepend">
                        <span class="input-group-text prefixshow"></span>
                    </div>
                    <input type="text" name="<?= $ib['id']; ?>" id="<?= $ib['id']; ?>" class="form-control blankorange" disabled>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>