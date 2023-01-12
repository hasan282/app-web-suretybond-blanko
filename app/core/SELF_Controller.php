<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SELF_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $config = array('new_line_remove' => true);
        $this->load->library('Layout_library', $config, 'layout');
        $this->load->library('Plugin_library', null, 'plugin');
    }
}
