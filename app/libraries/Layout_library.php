<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Layout_library
{
    private $configuration, $variables, $ci, $content, $script;

    public function __construct($config = [])
    {
        $this->ci = &get_instance();
        $this->_config($config);
        $this->content = '';
    }

    public function variable($data)
    {
        $this->variables = $data;
    }

    public function setup($config = [])
    {
        $this->_config($config);
    }

    public function content($path)
    {
        $content_script = '';
        if (is_array($path)) {
            foreach ($path as $pt)
                $content_script .= $this->ci->load->view($pt, $this->variables, true);
        } else {
            $content_script .= $this->ci->load->view(strval($path), $this->variables, true);
        }
        $this->content .= $content_script;
    }

    public function row($paths = [])
    {
        $row_script = '';
        if (sizeof($paths) === $this->configuration['columns']) {
            foreach ($paths as $index => $pts) {
                $classes = array();
                if (sizeof($this->configuration['size_xl']) === $this->configuration['columns']) {
                    $val_xl = intval($this->configuration['size_xl'][$index]);
                    if ($val_xl > 0) array_push($classes, 'col-xl-' . $val_xl);
                }
                if (sizeof($this->configuration['size_lg']) === $this->configuration['columns']) {
                    $val_lg = intval($this->configuration['size_lg'][$index]);
                    if ($val_lg > 0) array_push($classes, 'col-lg-' . $val_lg);
                }
                if (sizeof($this->configuration['size_md']) === $this->configuration['columns']) {
                    $val_md = intval($this->configuration['size_md'][$index]);
                    if ($val_md > 0) array_push($classes, 'col-md-' . $val_md);
                }
                if (sizeof($this->configuration['size_sm']) === $this->configuration['columns']) {
                    $val_sm = intval($this->configuration['size_sm'][$index]);
                    if ($val_sm > 0) array_push($classes, 'col-sm-' . $val_sm);
                }
                $content = '';
                if (is_array($pts)) {
                    foreach ($pts as $pt)
                        $content .= $this->ci->load->view($pt, $this->variables, true);
                } else {
                    $content .= $this->ci->load->view(strval($pts), $this->variables, true);
                }
                $row_script .= '<div class="' . implode(' ', $classes) . '">' . $content . '</div>';
            }
        }
        $row_script = '<div class="row">' . $row_script . '</div>';
        $this->content .= $row_script;
    }

    public function script()
    {
        $all_script = $this->_setup_head() . $this->content . $this->_setup_foot();
        if ($this->configuration['new_line_remove']) {
            $all_script = trim(preg_replace('/\s\s+/', ' ', $all_script));
        }
        $this->script = $all_script;
        return $this;
    }

    public function print()
    {
        echo $this->script;
    }

    public function print_code()
    {
        echo '<code>' . str_replace(array('<', '>'), array('&lt;', '&gt;'), $this->script) . '</code>';
    }

    private function _setup_head()
    {
        $head_script = '';
        foreach ($this->configuration['head_template'] as $head) {
            $head_script .= $this->ci->load->view($head, $this->variables, true);
        }
        return $head_script;
    }

    private function _setup_foot()
    {
        $foot_script = '';
        foreach ($this->configuration['foot_template'] as $foot) {
            $foot_script .= $this->ci->load->view($foot, $this->variables, true);
        }
        return $foot_script;
    }

    private function _config($custom_config)
    {
        $default_config = array(
            'columns' => 2,
            'size_xl' => array(4, 8),
            'size_lg' => array(5, 7),
            'size_md' => array(0, 0),
            'size_sm' => array(0, 0),
            'new_line_remove' => false,
            'head_template' => array(
                'template/head',
                'template/navbar',
                'template/sidebar'
            ),
            'foot_template' => array(
                'template/footer',
                'template/foot'
            )
        );
        if ($custom_config != null && is_array($custom_config) && !empty($custom_config)) {
            foreach ($custom_config as $key => $con) {
                if (key_exists($key, $default_config)) $default_config[$key] = $con;
            }
        }
        $this->configuration = $default_config;
    }
}
