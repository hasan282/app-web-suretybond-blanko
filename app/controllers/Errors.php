<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Errors extends SELF_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = '404 Not Found';
        $data['plugin'] = 'basic|fontawesome';
        $data['type'] = 'blank';
        $this->load->view('template/head', $data);
        $this->load->view('errors/admin_body_404');
        $this->load->view('template/foot');
    }

    public function admin()
    {
        $data['title'] = '404 Not Found';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $this->layout->variable($data);
        $this->layout->content('errors/admin_body_404');
        $this->layout->script()->print();
    }
}
