<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Errors extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
    }

    public function index()
    {
        $data['title'] = '404 Not Found';
        $data['plugin'] = 'basic|fontawesome';
        $data['type'] = 'login';
        $this->load->view('template/head', $data);
        $this->load->view('errors/admin_body_404');
        $this->load->view('template/foot');
    }

    public function admin()
    {
        $data['title'] = '404 Not Found';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('errors/admin_body_404');
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }
}
