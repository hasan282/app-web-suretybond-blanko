<div class="mw-400 mx-auto">
    <div class="card">
        <div class="card-body pb-1">
            <div class="form-group row mw-300 mx-auto">
                <label for="month" class="col-3 col-form-label">Bulan</label>
                <select id="month" class="form-control col-9">
                    <?php foreach ($report['list'] as $rl) :
                        $selected = ($report['select'] == $rl) ? 'selected ' : '';  ?>
                        <option <?= $selected; ?>value="<?= urlencode(self_encrypt($rl)); ?>"><?= format_date($rl . '-01', 'MM3 YY2'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#month').change(function() {
            const params = $(this).val();
            window.location.href = '<?= base_url('production/report?val='); ?>' + params;
        });
    });
</script>