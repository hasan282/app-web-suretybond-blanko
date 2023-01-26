<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends SELF_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['login', 'user']);
    }

    public function index()
    {
        if (is_login()) {
            $access = get_user_access($this->session->userdata('id'));
            $data['title'] = 'Dashboard';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            if ($access == '1') {
                $this->_administrator($data);
            } else {
                $this->_user_dashboard($data, $access);
            }
        } else {
            redirect();
        }
    }

    private function _administrator($data)
    {
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('dashboard/dash_administrator');
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }

    private function _user_dashboard($data, $access)
    {
        $this->layout->variable($data);
        $this->layout->content('dashboard/dash_smallbox');
        $this->layout->content('dashboard/dash_buttons_' . $access);
        $this->layout->content('dashboard/dash_notification');
        $this->layout->script()->print();
    }
}
