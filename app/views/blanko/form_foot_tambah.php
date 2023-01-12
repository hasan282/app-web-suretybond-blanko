<div class="col-xl-4">
    <div class="card">
        <div class="card-body text-center">
            <h6 class="text-bold text-secondary mb-4">Input Tipe</h6>
            <a href="<?= base_url('blanko/add'); ?>" type="button" class="btn btn-default btn-block">Otomatis Input</a>
            <button type="button" class="btn btn-primary btn-block text-bold">Manual Input</button>
        </div>
    </div>
</div>
</div>
<script>
    function enable_form() {
        if ($('#val_tanggal').val() != '' && $('#asuransi').val() != null) {
            $('#prefix').attr('disabled', false);
            $('#numbers').attr('disabled', false);
        }
    }
</script>