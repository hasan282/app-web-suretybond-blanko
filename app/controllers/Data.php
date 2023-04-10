<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Data extends SELF_Controller
{
    private $key;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['api', 'login', 'enkrip']);
        $this->key = self_md5('surety.ptjis.com');
        api_header(['GET', 'POST']);
    }

    public function index()
    {
        http_response_code(404);
    }

    public function user()
    {
        $this->_key_check();
        $username = $this->input->get('user');
        $password = self_md5($this->input->get('pass'));
        $userdata = $this->db->get_where('user', array(
            'username' => $username, 'password' => $password, 'is_active' => 1
        ))->row();
        if ($userdata === null || !method_is('GET')) {
            bad_request();
        } else {
            $data = array(
                'id' => $userdata->enkripsi,
                'user' => $userdata->username,
                'nama' => $userdata->nama,
                'foto' => base_url('asset/img/user/' . $userdata->photo)
            );
            print_data(array($data));
        }
    }

    private function _key_check()
    {
        $key = $this->input->get('key');
        if ($this->key !== $key) {
            bad_request();
            exit;
        }
    }
}
