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

        // $this->load->model('List_model', 'lists');
        // $vars = $this->lists->where(array(
        //     'status' => 2, 'asuransi' => '334322'
        // ))->order(array('asuransi', 'nomor'))->between(array('0081', '0091'))->query_string();
        // var_dump($vars);

        // echo $vars['query'];
    }

    public function obligee()
    {
        echo 'Obligee';
    }
}
