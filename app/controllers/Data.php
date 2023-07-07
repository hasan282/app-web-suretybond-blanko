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
                $this->_post_use();
                break;
            case 'crash':
                $this->_post_crash();
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
        if (method_is('POST')) {
            $datablanko = $this->_datablanko();
            if ($datablanko === null) {
                bad_request();
            } else {
                $used = $datablanko['used'];
                $datamodel = $datablanko['model'];
                $blanko = $datablanko['blanko'];
                $in_principal = true;
                $in_obligee = true;
                $this->db->trans_start();
                $trans_used = $this->db->insert('blanko_used', $used);
                $in_jaminan = $this->db->insert('jaminan', $datamodel['jaminan']);
                if (!empty($datamodel['principal']))
                    $in_principal = $this->db->insert('principal', $datamodel['principal']);
                if (!empty($datamodel['obligee']))
                    $in_obligee = $this->db->insert('obligee', $datamodel['obligee']);
                $trans_jaminan = ($in_jaminan && $in_principal && $in_obligee);
                $trans_blanko = $this->db->update(
                    'blanko',
                    ['id_status' => 2],
                    ['id' => $blanko['id']]
                );
                $this->db->trans_complete();
                $result = $this->db->trans_status();
                print_data([
                    'all' => $result,
                    'blanko' => $trans_blanko,
                    'jaminan' => $trans_jaminan,
                    'used' => $trans_used
                ]);
            }
        } else {
            bad_request();
        }
    }

    private function _post_crash()
    {
        if (method_is('POST')) {
            $datablanko = $this->_datablanko();
            if ($datablanko === null) {
                bad_request();
            } else {
                $blanko = $datablanko['blanko'];
                $model = $datablanko['model'];
                $used = $datablanko['used'];
                $this->db->trans_start();

                $trans_blanko = $this->db->update(
                    'blanko',
                    array('id_status' => 3),
                    array('id' => $blanko['id'])
                );
                $this->db->trans_complete();
                $transtatus = $this->db->trans_status();
                print_data(array(
                    'all' => $transtatus,
                    'blanko' => '',
                    'jaminan' => '',
                    'used' => '',
                    'crash' => '',
                    'data' => ''
                ));
            }
        } else {
            bad_request();
        }
    }

    private function _datablanko()
    {
        $returns = null;
        $this->load->helper('id');
        $this->load->model('Blanko_new_model', 'blankos');
        $blanko = $this->input->get('blanko');
        $user = get_real_id('user', $this->input->post('user'));
        $blankodata = $this->blankos->getdata(
            array('id', 'nomor', 'status_id')
        )->where(['id' => $blanko])->data();
        if (!empty($blankodata) && $blankodata['status_id'] == '5') {
            $this->load->model('Blanko_use_model', 'useblanko');
            $datamodel = $this->useblanko->api_usedata($this->input->post());
            $used['id'] = date('ymdHis') . mt_rand(1000, 9999);
            $used['enkripsi'] = self_md5($used['id']);
            $used['id_blanko'] = $blankodata['id'];
            $used['id_jaminan'] = $datamodel['jaminan']['id'];
            $used['id_user'] = $user;
            $returns = array(
                'used' => $used,
                'blanko' => $blankodata,
                'model' => $datamodel
            );
        }
        return $returns;
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
