<div class="mw-600 mx-auto">
    <div class="card">
        <div class="card-header">Gunakan Untuk Sendiri</div>
        <div class="card-body text-center">
            <h5 class="mb-4"><?= $office->nama; ?></h5>
            <a href="<?= base_url('process/used'); ?>" class="btn btn-primary text-bold"><i class="fas fa-print mr-2"></i>Gunakan Blanko</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Gunakan Untuk Agen Lain</div>
        <div class="card-body">
            <div class="form-group mw-300 mx-auto">
                <label for="office">Gunakan Untuk</label>
                <select name="office" id="office" class="form-control">
                    <option disabled selected>---</option>
                    <?php foreach ($this->db->query('SELECT id, nama FROM office WHERE is_active = 1 ORDER BY nama ASC')->result_array() as $ol) :
                        if ($ol['id'] != $office->id) : ?>
                            <option value="<?= urlencode(self_encrypt($ol['id'])); ?>"><?= $ol['nama']; ?></option>
                    <?php endif;
                    endforeach; ?>
                </select>
            </div>
            <div class="row pt-2">
                <div class="col-sm text-center text-sm-right mb-2">
                    <a href="" class="btn btn-primary text-bold disabled" id="btn_use">
                        <i class="fas fa-certificate mr-2"></i>Gunakan Baru
                    </a>
                </div>
                <div class="col-sm text-center text-sm-left mb-2">
                    <a href="" class="btn btn-secondary text-bold disabled" id="btn_revision">
                        <i class="fas fa-sync-alt mr-2"></i>Gunakan Revisi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#office').change(function() {
            const vals = $(this).val();
            $('#btn_use').attr('href', '<?= base_url('uses?var='); ?>' + vals).removeClass('disabled');
            $('#btn_revision').attr('href', '<?= base_url('uses/rev?var='); ?>' + vals).removeClass('disabled');
        });
    });
</script>