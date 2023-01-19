<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->helper(['login', 'id', 'enkrip', 'error']);
    }

    public function index()
    {
        if (is_login()) {
            $data['title'] = 'User';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $this->load->view('template/head', $data);
            $this->load->view('template/navbar');
            $this->load->view('template/sidebar');
            $this->load->view('user/main');
            $this->load->view('template/footer');
            $this->load->view('template/foot');
        } else {
            redirect(login_url());
        }
    }

    public function setting()
    {
        if (is_login()) {
            $data['title'] = 'Pengaturan Akun';
            $data['bread'] = 'User,user|Pengaturan';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $this->load->view('template/head', $data);
            $this->load->view('template/navbar');
            $this->load->view('template/sidebar');
            // $this->load->view('user/setting');
            $this->load->view('errors/construct');
            $this->load->view('template/footer');
            $this->load->view('template/foot');
        } else {
            redirect(login_url());
        }
    }

    public function manage()
    {
        if (special_access(1)) {
            $this->load->model('User_model', 'users');
            $data['title'] = 'User Manajemen';
            $data['bread'] = 'User,user|User Manajemen';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $data['userlist'] = $this->users->get_list();
            $this->load->view('template/head', $data);
            $this->load->view('template/navbar');
            $this->load->view('template/sidebar');
            $this->load->view('user/manage');
            $this->load->view('template/footer');
            $this->load->view('template/foot');
        } else {
            custom_404_admin();
        }
    }

    public function add()
    {
        if (special_access(1)) {
            if (empty($_POST)) {
                $this->_add_view();
            } else {
                $this->_add_process();
            }
        } else {
            custom_404_admin();
        }
    }

    private function _add_view()
    {
        $data['title'] = 'Tambah User Baru';
        $data['bread'] = 'User,user|Manajemen,user/manage|Tambah Baru';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('user/add_form');
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }

    private function _add_process()
    {
        var_dump($_POST);
    }

    public function logout()
    {
        $this->session->unset_userdata(
            ['id', 'user', 'nama', 'foto', 'role']
        );
        $this->session->sess_destroy();
        redirect('', 'refresh');
    }
}
