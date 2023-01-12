<div class="col-xl-4">
    <div class="card">
        <div class="card-body text-center">
            <h6 class="text-bold text-secondary mb-4">Input Tipe</h6>
            <button type="button" class="btn btn-primary btn-block text-bold">Otomatis Input</button>
            <a href="<?= base_url('blanko/tambah'); ?>" type="button" class="btn btn-default btn-block">Manual Input</a>
        </div>
    </div>
</div>
</div>
<script>
    function enable_form() {
        if ($('#val_tanggal').val() != '' && $('#asuransi').val() != null) {
            $('#prefix').attr('disabled', false);
            $('.blankorange').attr('disabled', false);
        }
    }
</script>