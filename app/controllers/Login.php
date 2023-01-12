<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login extends CI_Controller
{
    private $request_uri;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('form_validation');
        $this->load->helper(['login', 'cookie', 'enkrip']);
    }

    public function index()
    {
        $this->request_uri = $this->input->get('log');
        if (is_login()) {
            redirect('dashboard');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $this->form_validation->set_rules('username_ptjis', 'Username', 'required|regex_match[/^[a-z0-9]*$/]');
        $this->form_validation->set_rules('password_ptjis', 'Password', 'required');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login';
            $data['type'] = 'login';
            $data['req_uri'] = $this->request_uri;
            $data['plugin'] = 'basic|fontawesome';
            $page_source = $this->load->view('template/head', $data, true);
            $page_source .= $this->load->view('login/body', $data, true);
            $page_source .= $this->load->view('template/foot', $data, true);
            echo trim(preg_replace('/\s\s+/', ' ', $page_source));
            // $this->load->view('template/head', $data);
            // $this->load->view('login/body');
            // $this->load->view('template/foot');
        } else {
            $this->_authorize();
        }
    }

    private function _authorize()
    {
        $this->load->model('user_model');
        $user = $this->input->post('username_ptjis', true);
        $pass = $this->input->post('password_ptjis', true);
        $data = $this->user_model->get_user($user);
        if ($data && self_md5($pass) === $data->password) {
            if ($data->is_active == '1') {
                $this->session->set_userdata(array(
                    'id' => $data->enkripsi,
                    'user' => $data->username,
                    'nama' => $data->nama,
                    'foto' => $data->photo,
                    'role' => $data->access
                ));
                $this->_cookie('u_id', $data->enkripsi, 7);
                redirect(urldecode($this->request_uri));
            } else {
                $this->session->set_flashdata('message', 'Username Sudah Non-Aktif');
                redirect();
            }
        } else {
            $this->session->set_flashdata('message', 'Username & Password Tidak Sesuai');
            redirect();
        }
    }

    private function _cookie($name, $value = null, $day = 0)
    {
        $time = ($day === 0) ? null : time() + (60 * 60 * 24 * $day);
        setcookie($name, $value, $time, '/');
    }

    public function other()
    {
        $this->_cookie('u_id');
        delete_cookie('u_id');
        redirect();
    }
}
