<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Export extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('Export_library', null, 'export');
        $this->load->helper(['login', 'enkrip']);
    }

    public function index()
    {
        if (is_login()) {
            custom_404_admin();
        } else {
            redirect();
        }
    }

    public function report()
    {
        if (special_access([1, 2])) {
            $this->load->model('Recap_model', 'recaps');
            $fields = array(
                'office' => 'Pemegang Blanko',
                'available' => 'Tersedia|int',
                'used' => 'Terpakai|int',
                'crash' => 'Rusak|int'
            );
            $this->export->set_fields($fields, true);
            $this->export->set_rows($this->recaps->recap_office()->get_data());
            $filename = $this->input->get('filename');
            if ($filename == '') {
                $this->export->file('export_file');
            } else {
                $this->export->file($filename);
            }
        } else {
            custom_404_admin();
        }
    }

    public function list()
    {
        if (empty($_POST)) {
            redirect('report');
        } else {
            $params = self_decrypt($this->input->post('params'));
            if ($params === false) {
                redirect('report');
            } else {
                $fields = array(
                    'nomor' => 'Nomor Blanko|to_string',
                    'status' => 'Status',
                    'tipe' => 'Jenis Jaminan',
                    'jaminan' => 'Nomor Jaminan',
                    'principal' => 'Principal',
                    'obligee' => 'Obligee',
                    'nilai' => 'Nilai Jaminan|int',
                    'tanggal' => 'Dari Tanggal|date',
                    'tanggal_to' => 'Sampai Tanggal|date',
                    'days' => 'Hari|int'
                );
                $param = explode('_and_', $params);
                $this->load->model('Recap_model', 'recaps');
                foreach (array_keys($fields) as $key)
                    if ($this->input->post('check_' . $key) != 'on') unset($fields[$key]);
                $this->export->set_fields($fields, true);
                $this->export->set_rows(
                    $this->recaps->list($param[0], $param[1], $param[2], ($param[3] == '1'), array_keys($fields))->get_data()
                );
                $filename = $this->input->post('filename');
                if ($filename == '') {
                    $this->export->file('export_file');
                } else {
                    $this->export->file($filename);
                }
            }
        }
    }

    public function sent($param = null)
    {
        $numbers = array();
        $this->load->database();
        $datasent = (array) $this->db->get_where('record_sent', ['enkripsi' => $param])->row();
        if (!empty($datasent)) {
            $this->load->model('List_model', 'lists');
            $numbers = $this->lists->select(['fullnumber'])->where([
                'asuransi' => self_md5($datasent['id_asuransi'])
            ])->between([$datasent['number_from'], $datasent['number_to']])->data_list();
        }
        $data['datasent'] = $numbers;
        $this->load->view('export/index', $data);
    }
}
