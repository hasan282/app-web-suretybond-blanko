<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url(uri_string()); ?>" method="POST">
                    <div class="form-group mw-300">
                        <label>Tanggal Masuk</label>
                        <div class="input-group date" id="tanggal" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#tanggal" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                            <input type="hidden" id="val_tanggal" name="tanggal" oninput="enable_form()">
                            <input type="text" class="form-control datetimepicker-input" data-target="#tanggal" placeholder="<< Pilih Tanggal">
                        </div>
                    </div> <?php $prefixes = array(); ?>
                    <div class="form-group mw-400">
                        <label for="asuransi">Asuransi</label>
                        <select name="asuransi" id="asuransi" class="form-control" onchange="enable_form()">
                            <option disabled selected>-</option>
                            <?php $this->load->database();
                            $asuransi = $this->db->query('SELECT enkripsi, nama, form_prefix AS prefix FROM asuransi WHERE is_active = 1 ORDER BY nama ASC')->result_array();
                            foreach ($asuransi as $as) :
                                $prefixes['AS' . $as['enkripsi']] = $as['prefix']; ?>
                                <option value="<?= $as['enkripsi']; ?>"><?= $as['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mw-200">
                        <label for="prefix"><small class="text-secondary">Blanko Prefix</small></label>
                        <input type="text" id="prefix" name="prefix" class="form-control" disabled>
                    </div>
                    <script>
                        const prefix = JSON.parse('<?= str_replace("'", "\'", json_encode($prefixes)); ?>');
                        $('#asuransi').change(function() {
                            const key = 'AS' + $(this).val();
                            $('#prefix').val(prefix[key]).trigger('keyup');
                        });
                    </script>