</div>
<script>
    const basic_all_url = '<?= base_url(); ?>';
    $(function() {
        $('.show-tooltip').tooltip({
            placement: 'top',
            trigger: 'hover'
        });
    });
</script>
<?= (isset($plugin)) ? $this->plugin->get($plugin, 'bottom') : ''; ?>
<?= (isset($jscript)) ? show_jscript($jscript) : ''; ?>
<?php function show_jscript($js)
{
    $jss = explode('|', $js);
    foreach ($jss as $key => $val) $jss[$key] = '<script src="' . base_url('asset/jself/' . $val) . '.js?j=s' . mt_rand(100, 999) . '"></script>' . PHP_EOL;
    return join('', $jss);
} ?>
</body>

</html>