<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Attachment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('form_validation');
        $this->load->helper(['login', 'error', 'user']);
    }

    public function index()
    {
        if (is_login()) {
            custom_404_admin();
        } else {
            redirect();
        }
    }

    public function used()
    {
        if (is_login()) {
            // $data['title'] = 'Lampiran Blanko Terpakai';
            // $data['bread'] = 'Blanko Terpakai,blanko/used|Lampiran';
            // $data['plugin'] = 'basic|fontawesome|scrollbar';
            // $this->load->view('template/head', $data);
            // $this->load->view('template/navbar');
            // $this->load->view('template/sidebar');
            // $this->load->view('attach/used');
            // $this->load->view('template/footer');
            // $this->load->view('template/foot');
            under_construct();
        } else {
            redirect(login_url());
        }
    }

    public function crash()
    {
        if (is_login()) {
            under_construct();
        } else {
            redirect(login_url());
        }
    }
}
