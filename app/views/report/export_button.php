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
                <div class="form-group text-left">
                    <label for="filename">Nama File</label>
                    <input id="filename" class="form-control" placeholder="export_file">
                    <small class="ml-2 text-secondary"><i>nama default export_file.xlsx</i></small>
                </div>
                <a href="<?= base_url('export/report'); ?>" target="_blank" id="buttonexport" class="btn btn-success text-bold">
                    <i class="fas fa-download mr-2"></i>Download File Excel
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    const urlexport = '<?= base_url('export/report?filename='); ?>';
    $(function() {
        $('#export_open').click(function() {
            $('#export_box').removeClass('mw-300').addClass('mw-400');
            $('#box_open').addClass('zero-height');
            $('#box_process').removeClass('zero-height');
        });
        $('#export_close').click(function() {
            $('#export_box').removeClass('mw-400').addClass('mw-300');
            $('#box_process').addClass('zero-height');
            $('#box_open').removeClass('zero-height');
        });
        $('#filename').on('keyup', function() {
            $('#buttonexport').attr('href', urlexport + $(this).val());
        });
    });
</script>