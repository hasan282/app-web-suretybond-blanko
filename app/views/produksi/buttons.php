<script>
    function setup_list_page(limit, page, add = '') {
        let url_to = '<?= base_url('production/setlist/'); ?>' + limit + '/' + page;
        url_to += '?log=production' + add;
        window.location.href = url_to;
    }
</script>
<div class="card mb-1">
    <div class="card-body row p-2">
        <div class="col-md text-md-left text-center">
            <div class="btn-group btn-group-toggle mb-md-0 mb-2" data-toggle="buttons">
                <?php $list_option = array(
                    ['value' => '10', 'id' => 'list_10', 'text' => '10'],
                    ['value' => '20', 'id' => 'list_20', 'text' => '20'],
                    ['value' => '50', 'id' => 'list_50', 'text' => '50'],
                    ['value' => '100', 'id' => 'list_100', 'text' => '100'],
                    ['value' => '0', 'id' => 'list_all', 'text' => 'Semua']
                );
                foreach ($list_option as $lo) :
                    $actives = ($lo['value'] == $pagination['limit']); ?>
                    <label class="btn btn-secondary<?= ($actives) ? ' active' : ''; ?>">
                        <input <?= ($actives) ? 'checked ' : ''; ?>type="radio" id="<?= $lo['id']; ?>" value="<?= $lo['value']; ?>" name="dataview" autocomplete="off"><?= $lo['text']; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md text-md-right text-center">
            <a href="<?= base_url('production/report'); ?>" class="btn btn-info text-bold">
                Lihat Laporan Bulanan<i class="fas fa-arrow-circle-right ml-2"></i>
            </a>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('[name="dataview"]').change(function() {
            setup_list_page($(this).val(), 1);
        });
        $('#filterbutton').click(function() {
            const asuransi = $('#asuransi').val();
            const office = $('#office').val();
            const laprod = $('#laprod').val();
            const limit = $('[name="dataview"]:checked').val();
            setup_list_page(limit, 1, '&filter=' + asuransi + '-and-' + office + '-and-' + laprod);
        });
    });
</script>