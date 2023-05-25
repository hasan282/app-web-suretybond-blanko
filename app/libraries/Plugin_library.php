<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Plugin_library
{
    private $plugins, $css, $js, $new_line;

    public function __construct()
    {
        $this->new_line = false;
        $this->plugins = array(
            // ['url' => base_url() '', 'type' => ['css/js', 'top/bottom']]
            'basic' => array(
                ['url' => 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback', 'type' => ['css', 'top']],
                ['url' => base_url('asset/css/adminlte.min.css'), 'type' => ['css', 'top']],
                ['url' => base_url('asset/csself/all_css.css?cs=s' . mt_rand(100, 999)), 'type' => ['css', 'top']],
                ['url' => base_url('asset/plugin/jquery/jquery.min.js'), 'type' => ['js', 'top']],
                ['url' => base_url('asset/plugin/bootstrap/js/bootstrap.bundle.min.js'), 'type' => ['js', 'bottom']],
                ['url' => base_url('asset/js/adminlte.min.js'), 'type' => ['js', 'bottom']],
                ['url' => base_url('asset/jself/functions/all.min.js?j=s' . mt_rand(100, 999)), 'type' => ['js', 'bottom']]
            ),
            'fontawesome' => array(
                ['url' => base_url('asset/plugin/fontawesome-free/css/all.min.css'), 'type' => ['css', 'top']]
            ),
            'icheck' => array(
                ['url' => base_url('asset/plugin/icheck-bootstrap/icheck-bootstrap.min.css'), 'type' => ['css', 'top']]
            ),
            'scrollbar' => array(
                ['url' => base_url('asset/plugin/overlayScrollbars/css/OverlayScrollbars.min.css'), 'type' => ['css', 'top']],
                ['url' => base_url('asset/plugin/overlayScrollbars/js/jquery.overlayScrollbars.min.js'), 'type' => ['js', 'bottom']]
            ),
            'toastr' => array(
                ['url' => base_url('asset/plugin/toastr/toastr.min.css'), 'type' => ['css', 'top']],
                ['url' => base_url('asset/plugin/toastr/toastr.min.js'), 'type' => ['js', 'bottom']]
            ),
            'fileinput' => array(
                ['url' => base_url('asset/plugin/bs-custom-file-input/bs-custom-file-input.min.js'), 'type' => ['js', 'bottom']],
                ['url' => base_url('asset/jself/file.input.js?j=s' . mt_rand(100, 999)), 'type' => ['js', 'bottom']]
            ),
            'dateinput' => array(
                ['url' => base_url('asset/plugin/daterangepicker/daterangepicker.css'), 'type' => ['css', 'top']],
                ['url' => base_url('asset/plugin/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'), 'type' => ['css', 'top']],
                ['url' => base_url('asset/plugin/moment/moment.min.js'), 'type' => ['js', 'top']],
                ['url' => base_url('asset/plugin/inputmask/jquery.inputmask.min.js'), 'type' => ['js', 'bottom']],
                ['url' => base_url('asset/plugin/daterangepicker/daterangepicker.js'), 'type' => ['js', 'bottom']],
                ['url' => base_url('asset/plugin/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js'), 'type' => ['js', 'bottom']]
            )
        );
    }

    public function get($plugin = '', $position=null)
    {
        $this->css = '';
        $this->js = '';
        $plugins = explode('|', $plugin);
        foreach ($plugins as $pl) {
            $this->_setup($pl, $position);
        }
        return $this->css . $this->js;
    }

    private function _setup($plugin, $position)
    {
        if (array_key_exists($plugin, $this->plugins)) {
            foreach ($this->plugins[$plugin] as $plug) {
                if ($plug['type'][0] == 'css' && $plug['type'][1] == $position) $this->css .= $this->_css($plug['url']);
                if ($plug['type'][0] == 'js' && $plug['type'][1] == $position) $this->js .= $this->_js($plug['url']);
            }
        }
    }

    private function _css($link)
    {
        $css = '<link rel="stylesheet" href="' . $link . '">';
        if ($this->new_line) $css .= PHP_EOL;
        return $css;
    }

    private function _js($link)
    {
        $js = '<script src="' . $link . '"></script>';
        if ($this->new_line) $js .= PHP_EOL;
        return $js;
    }
}
