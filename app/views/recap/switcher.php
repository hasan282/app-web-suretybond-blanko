<div class="mw-600 mx-auto">
    <div class="card">
        <div class="card-body text-center">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <?php $uri_string = explode('/', uri_string());
                $switcher = array(
                    ['value' => 'report', 'id' => 'v_report', 'icon' => 'fas fa-user-friends', 'text' => 'Rekap Subagen'],
                    ['value' => 'recap', 'id' => 'v_recap', 'icon' => 'fas fa-shield-alt', 'text' => 'Rekap Asuransi']
                );
                foreach ($switcher as $sw) :
                    $is_active = ($sw['value'] == $uri_string[0]); ?>
                    <label class="btn bg-lightblue<?= ($is_active) ? ' active' : ''; ?>">
                        <input <?= ($is_active) ? 'checked ' : ''; ?>type="radio" name="switcher" id="<?= $sw['id']; ?>" value="<?= $sw['value']; ?>" autocomplete="off">
                        <i class="<?= $sw['icon']; ?> mr-2"></i><?= $sw['text']; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('[name="switcher"]').change(function() {
            const uri = $(this).val();
            window.location.href = '<?= base_url(); ?>' + uri;
        });
    });
</script>