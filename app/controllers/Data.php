<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Data extends SELF_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['login', 'error', 'user', 'format']);
    }

    public function index()
    {
        if (is_login()) {
            custom_404_admin();
        } else {
            redirect();
        }
    }

    public function principal()
    {
        echo 'Principal';

        $number = '344.454.550,55215';
        // var_dump(substr($number, 0, 2));
        // var_dump(intval('-43gfgf31.ewer'));
        var_dump(float_input($number));
    }

    public function obligee()
    {
        echo 'Obligee';
    }
}
