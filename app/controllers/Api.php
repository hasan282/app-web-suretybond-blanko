<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Api extends CI_Controller
{
    private $office;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['api', 'user', 'login', 'enkrip']);
        $this->office = get_user_office($this->session->userdata('id'));
        api_header();
    }

    public function index()
    {
        bad_request();
    }

    public function asuransi($id = null)
    {
        if (!method_is('GET') || $id === null || !is_login()) {
            bad_request();
        } else {
            $this->load->model('Blanko_model', 'blankos');
            $office = get_user_office($this->session->userdata('id'));
            print_data($this->blankos->blanko_available($id, $office->id));
        }
    }

    public function blanko($status = null)
    {
        if (is_login() && method_is('GET')) {
            $limit = intval($this->input->get('limit'));
            $offset = intval($this->input->get('offset'));
            $asuransi = $this->input->get('asuransi');
            $nomor = $this->input->get('nomor');
            $this->load->model('Blanko_model', 'blankos');
            $office = get_user_office($this->session->userdata('id'));
            $where['id_office'] = $office->id;
            if ($asuransi != '') {
                $where['asuransi.enkripsi'] = $asuransi;
            }
            if ($nomor != '') {
                $where['nomor'] = $nomor;
            }
            if ($status === null) {
                $where['id_status'] = 1;
                print_data($this->blankos->get_list(['id', 'asuransi', 'prefix', 'nomor'], $where, $limit, $offset));
            } else {
                $status_list = array(1, 2, 3, 4);
                $status_request = array_intersect($status_list, str_split($status));
                if (sizeof($status_request) > 0) {
                    $where['id_status'] = array_values($status_request);
                    $request_field = ['id', 'asuransi', 'prefix', 'nomor', 'status', 'color'];
                    print_data($this->blankos->get_list($request_field, $where, $limit, $offset));
                } else {
                    bad_request();
                }
            }
        } else {
            bad_request();
        }
    }

    public function usedblanko()
    {
        if (is_login() && method_is('GET')) {
            $this->load->model('Blanko_model', 'blankos');
            $office = get_user_office($this->session->userdata('id'));
            $where = array('blanko.id_office' => $office->id);
            $limit = intval($this->input->get('limit'));
            $offset = intval($this->input->get('offset'));
            $asuransi = $this->input->get('asuransi');
            $nomor = $this->input->get('nomor');
            if ($asuransi != '') {
                $where['asuransi.enkripsi'] = $asuransi;
            }
            if ($nomor != '') {
                $where['nomor'] = $nomor;
            }
            print_data($this->blankos->get_used_list(['id', 'asuransi', 'nomor', 'principal'], $where, $limit, $offset));
        } else {
            bad_request();
        }
    }

    public function data()
    {
        if (is_login() && method_is('GET')) {
            $data = $this->input->get('get');
            switch ($data) {
                case 'used':
                    $this->_blanko_used();
                    break;
                case 'usedfrom':
                    $this->_blanko_used($this->input->get('office'));
                    break;
                case 'search':
                    $this->_search();
                    break;
                default:
                    bad_request();
                    break;
            }
        } else {
            bad_request();
        }
    }

    private function _blanko_used($id_office = '')
    {
        $where = array('office' => $this->office->id);
        $decrypt_id_office = self_decrypt($id_office);
        if ($decrypt_id_office !== false) {
            $where['office'] = $decrypt_id_office;
        }
        $filter = array('asuransi', 'nomor', 'principal');
        foreach ($filter as $fil) {
            $value = $this->input->get($fil);
            if ($value != '') {
                $where[$fil] = $value;
            }
        }
        $this->load->model('Blanko_new_model', 'blankos');
        $fields = array('enkrip', 'prefix', 'nomor', 'asuransi', 'principal', 'obligee');
        $order = array('asuransi', 'nomor');
        $data = $this->blankos->used($fields, $where)->order($order);
        print_data(array(
            'count' => $data->count(),
            'list' => $data->limit(
                intval($this->input->get('limit')),
                intval($this->input->get('offset'))
            )->data_list()
        ));
    }

    private function _search()
    {
        print_data('3454');
    }
}
