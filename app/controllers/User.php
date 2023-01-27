<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends CI_Controller
{
    private $office;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->helper(['login', 'user', 'id', 'enkrip', 'error']);
        $this->office = (array) get_user_office($this->session->userdata('id'));
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

    public function setting($param = null)
    {
        if (is_login()) {
            switch ($param) {
                case self_md5('password'):
                    $this->_setting_password();
                    break;
                default:
                    $this->_setting_view();
                    break;
            }
        } else {
            redirect(login_url());
        }
    }

    private function _setting_view()
    {
        $data['title'] = 'Pengaturan Akun';
        $data['bread'] = 'User,user|Pengaturan';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['office'] = $this->office;
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('user/setting');
        // $this->load->view('errors/construct');
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }

    private function _setting_password()
    {
        var_dump($_POST);
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
        $this->load->library('form_validation', null, 'forms');
        if (special_access(1)) {
            $this->forms->set_rules('in_nama', 'Nama', 'required');
            $this->forms->set_rules('in_username', 'Username', 'required|regex_match[/^[a-z0-9]*$/]');
            $this->forms->set_rules('in_role', 'Role', 'required');
            $this->forms->set_rules('in_office', 'Kantor', 'required');
            if ($this->forms->run() === false) {
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
        $password = $this->db->get_where('reference', array('ref' => 'password'))->row();
        $userdata = array(
            'id' => date('ymdHis'),
            'username' => $this->input->post('in_username'),
            'password' => self_md5($password->vals),
            'nama' => $this->input->post('in_nama'),
            'photo' => $this->input->post('in_photo'),
            'id_office' => $this->input->post('in_office'),
            'id_access' => intval($this->input->post('in_role')),
            'is_active' => 1
        );
        $userdata['enkripsi'] = self_md5($userdata['id']);
        if ($this->db->insert('user', $userdata)) {
            // success
            redirect('user/manage');
        } else {
            // failed
            redirect('user/manage');
        }
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
