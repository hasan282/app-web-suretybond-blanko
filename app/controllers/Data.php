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
        $this->load->model('User_model', 'users');
        $userdata = $this->users->api_user($username, $password);
        if ($userdata === null || !method_is('GET')) {
            bad_request();
        } else {
            $data = array(
                'id' => $userdata->id,
                'user' => $userdata->user,
                'nama' => $userdata->name,
                'foto' => base_url('asset/img/user/' . $userdata->foto),
                'office_id' => $userdata->office_id,
                'office' => $userdata->office,
                'role_id' => $userdata->role_id,
                'role' => $userdata->role
            );
            print_data(array($data));
        }
    }

    public function blanko()
    {
        $this->_key_check();
        $datatype = $this->input->get('data');
        switch ($datatype) {
            case 'available':
                $this->_get_available();
                break;
            case 'marking':
                $this->_post_marking();
                break;
            case 'use':
                break;
            default:
                bad_request();
                break;
        }
    }

    private function _get_available()
    {
        if (method_is('GET')) {
            $this->load->model('Blanko_new_model', 'blankos');
            $asuransi = $this->input->get('asuransi');
            $office = $this->input->get('office');
            $rows = intval($this->input->get('rows'));
            if ($asuransi === null || $office === null || $rows < 1) {
                bad_request();
            } else {
                $where = array(
                    'status' => 1,
                    'asuransi' => $asuransi,
                    'office' => $office
                );
                print_data($this->blankos->getdata(
                    array('id', 'prefix', 'nomor')
                )->where($where)->order(['nomor'])->limit($rows)->data_list());
            }
        } else {
            bad_request();
        }
    }

    private function _post_marking()
    {
        if (method_is('POST')) {
            $result = false;
            $blanko = $this->input->post('blanko');
            if (is_array($blanko) && !empty($blanko)) {
                $this->db->trans_start();
                $this->db->where_in('id', $blanko);
                $this->db->update('blanko', ['id_status' => 5]);
                $row = $this->db->affected_rows();
                $this->db->trans_complete();
                $result = $this->db->trans_status();
            }
            print_data(array(
                'result' => $result,
                'rows' => $row
            ));
        } else {
            bad_request();
        }
    }

    private function _post_use()
    {
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
