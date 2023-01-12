<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->helper(['login', 'user']);
    }

    public function index()
    {
        if (is_login()) {
            $access = get_user_access($this->session->userdata('id'));
            $data['title'] = 'Dashboard';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $this->load->view('template/head', $data);
            $this->load->view('template/navbar');
            $this->load->view('template/sidebar');
            if ($access == '1') {
                $this->load->view('dashboard/dash_administrator');
            } else {
                $this->load->view('dashboard/dash_smallbox');
                $this->load->view('dashboard/dash_buttons_' . $access);
                $this->load->view('dashboard/dash_notification');
            }
            $this->load->view('template/footer');
            $this->load->view('template/foot');
        } else {
            redirect();
        }
    }
}