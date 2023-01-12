<script>
    function setup_list_page(limit, page, url = null) {
        let url_to = '<?= base_url('report/setlist/'); ?>' + limit + '/' + page;
        if (url === null) {
            url_to += '<?= login_url(); ?>';
        } else {
            url_to += '?log=' + url;
        }
        window.location.href = url_to;
    }
</script>
<div class="card mw-900 mx-auto">
    <div class="card-body">
        <div class="row">
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
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <?php $uri_string = explode('/', uri_string());
                    $switcher = array(
                        ['value' => 'list', 'id' => 'v_simple', 'text' => 'Simple View'],
                        ['value' => 'listall', 'id' => 'v_detail', 'text' => 'Detail View']
                    );
                    foreach ($switcher as $sw) :
                        $is_active = ($sw['value'] == $uri_string[1]); ?>
                        <label class="btn bg-lightblue<?= ($is_active) ? ' active' : ''; ?>">
                            <input <?= ($is_active) ? 'checked ' : ''; ?>type="radio" name="views" id="<?= $sw['id']; ?>" value="<?= $sw['value']; ?>" autocomplete="off"><?= $sw['text']; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('[name="views"]').change(function() {
            setup_list_page(<?= $pagination['limit']; ?>, <?= $pagination['page']; ?>, '<?= urlencode('report/'); ?>' + $(this).val() + '<?= urlencode('/' . $asuransi->enkripsi . '?' . $_SERVER['QUERY_STRING']); ?>');
        });
        $('[name="dataview"]').change(function() {
            setup_list_page($(this).val(), 1);
        });
    });
</script>