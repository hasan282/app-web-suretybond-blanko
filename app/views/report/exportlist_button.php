<?php $params = array();
array_push($params, $asuransi->id);
array_push($params, $office->id);
array_push($params, $tanggal);
array_push($params, $office->id_tipe); ?>
<div id="export_box" class="mw-300 mx-auto transition-05">
    <div class="card">
        <div class="card-body">
            <div id="box_open" class="text-center">
                <button id="export_open" type="button" class="btn bg-gradient-olive text-bold">
                    <i class="fas fa-external-link-square-alt mr-2"></i>Eksport ke File Excel
                </button>
            </div>
            <div id="box_process" class="text-center zero-height">
                <button id="export_close" type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-times mr-2"></i>Batalkan
                </button>
                <form action="<?= base_url('export/list') ?>" target="_blank" method="POST">
                    <div class="row text-left">
                        <div class="col-12">
                            <div class="icheck-secondary">
                                <input type="checkbox" id="check_all">
                                <label for="check_all" class="text-gray">Semua Kolom</label>
                            </div>
                            <hr>
                        </div>
                        <?php
                        $col_names = array(
                            'check_nomor' => 'Nomor Blanko',
                            'check_status' => 'Status',
                            'check_tipe' => 'Jenis Jaminan',
                            'check_jaminan' => 'Nomor Jaminan',
                            'check_principal' => 'Principal',
                            'check_obligee' => 'Obligee',
                            'check_nilai' => 'Nilai Jaminan',
                            'check_tanggal' => 'Tanggal Berlaku',
                            'check_tanggal_to' => 'Tanggal Berakhir',
                            'check_days' => 'Lama Berlaku'
                        );
                        foreach ($col_names as $key => $val) : ?>
                            <div class="col-xl-4 col-sm-6">
                                <div class="icheck-primary">
                                    <input type="checkbox" class="checkcolumns" name="<?= $key; ?>" id="<?= $key; ?>">
                                    <label class="labelcheck" for="<?= $key; ?>"><?= $val; ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <input type="hidden" name="params" value="<?= self_encrypt(implode('_and_', $params)); ?>">
                    <div class="form-group text-left mw-300 mx-auto">
                        <label for="filename">Nama File</label>
                        <input id="filename" name="filename" class="form-control" placeholder="export_file">
                        <small class="ml-2 text-secondary"><i>nama default export_file.xlsx</i></small>
                    </div>
                    <button type="submit" id="submitexport" class="btn btn-success text-bold">
                        <i class="fas fa-download mr-2"></i>Download File Excel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#export_open').click(function() {
            $('#export_box').removeClass('mw-300').addClass('mw-600');
            $('#box_open').addClass('zero-height');
            $('#box_process').removeClass('zero-height');
        });
        $('#export_close').click(function() {
            $('#export_box').removeClass('mw-600').addClass('mw-300');
            $('#box_process').addClass('zero-height');
            $('#box_open').removeClass('zero-height');
        });
        $('#check_all').change(function() {
            if ($(this).is(':checked')) {
                $('.checkcolumns:not(:checked)').each(function() {
                    $(this).trigger('click');
                });
            } else {
                $('.checkcolumns:checked').each(function() {
                    $(this).trigger('click');
                });
            }
        });
        $('.checkcolumns').change(function() {
            const check_id = this.id;
            if ($(this).is(':checked')) {
                $('label[for="' + check_id + '"]').addClass('text-primary');
                if ($('.checkcolumns:checked').length == $('.checkcolumns').length) $('#check_all').prop('checked', true);
            } else {
                $('label[for="' + check_id + '"]').removeClass('text-primary');
                $('#check_all').prop('checked', false);
            }
            $('#submitexport').attr('disabled', ($('.checkcolumns:checked').length < 1));
        });
        trigger_click(['check_nomor', 'check_status', 'check_jaminan', 'check_principal']);
    });

    function trigger_click(ids = []) {
        ids.forEach(id => {
            $('#' + id).trigger('click');
        });
    }
</script>