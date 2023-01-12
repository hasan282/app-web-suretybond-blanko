<?php function create_page_number($now, $total)
{
    $int = 1;
    $pages = array($now);
    $all_page = range(1, $total);
    while (sizeof($pages) < 5) {
        if (in_array($now + $int, $all_page)) array_push($pages, $now + $int);
        if (in_array($now - $int, $all_page)) array_push($pages, $now - $int);
        $int++;
    }
    sort($pages);
    return $pages;
}
$page_now = intval($pagination['page']);
$page_total = ceil($recap['count'] / intval($pagination['limit']));
$page_number = ($page_total > 5) ? create_page_number($page_now, intval($page_total)) : range(1, intval($page_total)); ?>
<div class="card mw-400 mx-auto">
    <div class="card-body">
        <div class="text-center">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <?php if ($page_now != 1) : ?>
                    <label class="btn bg-lightblue">
                        <input type="radio" name="pages" id="page_first" value="1" autocomplete="off"><i class="fas fa-angle-double-left"></i>
                    </label>
                <?php endif;
                foreach ($page_number as $pn) :
                    $is_active = (intval($pagination['page']) == $pn); ?>
                    <label class="btn bg-lightblue<?= ($is_active) ? ' active' : ''; ?>">
                        <input <?= ($is_active) ? 'checked ' : ''; ?>type="radio" name="pages" id="page_<?= $pn; ?>" value="<?= $pn; ?>" autocomplete="off"><?= $pn; ?>
                    </label>
                <?php endforeach;
                if ($page_now != $page_total) : ?>
                    <label class="btn bg-lightblue">
                        <input type="radio" name="pages" id="page_last" value="<?= $page_total; ?>" autocomplete="off"><i class="fas fa-angle-double-right"></i>
                    </label>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('[name="pages"]').change(function() {
            setup_list_page(<?= $pagination['limit']; ?>, $(this).val());
        });
    });
</script>