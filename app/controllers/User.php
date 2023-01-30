<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends CI_Controller
{
    private $office;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('form_validation', null, 'forms');
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
                case self_md5('office'):
                    $this->_setting_office();
                    break;
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
        $data['jscript'] = 'user/setting';
        $data['office'] = $this->office;
        $config = array('new_line_remove' => true);
        $this->load->library('Layout_library', $config, 'layout');
        $this->layout->variable($data);
        $this->layout->content('user/setting');
        $this->layout->script()->print();
    }

    private function _setting_office()
    {
        $officedata = array(
            'alamat' => $this->input->post('alamat'),
            'telpon' => $this->input->post('telpon_num')
        );
        foreach ($officedata as $key => $val) if ($val == $this->office[$key]) unset($officedata[$key]);
        if (empty($officedata)) {
            $this->session->set_flashdata('text', 'Tidak Ada Data yang Diubah');
        } else {
            if ($this->db->update('office', $officedata, array('id' => $this->office['id']))) {
                $changed_keys = array_keys($officedata);
                foreach ($changed_keys as $key => $ck) $changed_keys[$key] = ucwords($ck);
                $message = 'Data ' . implode(' dan ', $changed_keys) . ' Kantor Telah Diubah';
                $this->session->set_flashdata('type', 'success');
                $this->session->set_flashdata('text', $message);
            } else {
                $this->session->set_flashdata('type', 'danger');
                $this->session->set_flashdata('text', 'Terjadi Kesalahan Saat Merubah Data');
            }
        }
        redirect('user/setting');
    }

    private function _setting_password()
    {
        $this->forms->set_rules('pass_before', 'Password Sebelum', 'required');
        $this->forms->set_rules('pass_new', 'Password Baru', 'required');
        $this->forms->set_rules('pass_confirm', 'Konfirmasi Password', 'required');
        if ($this->forms->run() === false) {
            redirect('user/setting');
        } else {
            $userenkrip = $this->session->userdata('id');
            $userdata = $this->db->query('SELECT id, password FROM user WHERE enkripsi = ?', $userenkrip)->row();
            $pass_before = self_md5($this->input->post('pass_before'));
            $pass_after1 = self_md5($this->input->post('pass_new'));
            $pass_after2 = self_md5($this->input->post('pass_confirm'));
            $valid_old = ($pass_before === $userdata->password);
            $valid_new = ($pass_after1 === $pass_after2);
            if (!$valid_new) {
                $this->session->set_flashdata('type', 'danger');
                $this->session->set_flashdata('text', 'Konfirmasi Password Baru Tidak Sesuai');
            }
            if (!$valid_old) {
                $this->session->set_flashdata('type', 'danger');
                $this->session->set_flashdata('text', 'Password Lama Tidak Sesuai');
            }
            if ($valid_new && $valid_old) {
                if ($this->db->update('user', array('password' => $pass_after1), array('id' => $userdata->id))) {
                    $this->session->set_flashdata('type', 'success');
                    $this->session->set_flashdata('text', 'Password Telah Diubah');
                } else {
                    $this->session->set_flashdata('type', 'danger');
                    $this->session->set_flashdata('text', 'Terjadi Kesalahan Saat Merubah Password');
                }
            }
            redirect('user/setting');
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
