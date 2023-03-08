<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Search extends SELF_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['login', 'error', 'user', 'format', 'enkrip']);
    }

    public function index()
    {
        if (true_access()) {
            $data['title'] = 'Pencarian';
            $data['plugin'] = 'basic|fontawesome|scrollbar|icheck';
            $data['jscript'] = 'functions/table.min|functions/check|search/funct';
            $this->layout->variable($data);
            $this->layout->content('search/filters');
            $this->layout->content('search/table');
            $this->layout->content('search/export');
            $this->layout->script()->print();
        } else {
            custom_404_admin();
        }
    }

    public function export()
    {
        if (true_access('search')) {
            if (empty($_POST)) {
                custom_404_admin();
            } else {
                $this->_export_process();
            }
        } else {
            custom_404_admin();
        }
    }

    private function _export_process()
    {
        $fields = array(
            'asuransi' => 'Asuransi',
            'fullnumber' => 'Nomor Blanko|prefix_space',
            'status' => 'Status',
            'jaminan_tipe1' => 'Jenis Jaminan',
            'jaminan' => 'Nomor Jaminan',
            'principal' => 'Principal',
            'kontrak' => 'Kontrak',
            'pekerjaan' => 'Pekerjaan',
            'obligee' => 'Obligee',
            'currency2' => 'Mata Uang',
            'nilai' => 'Nilai Bond|float',
            'date_from' => 'Awal|dateflip',
            'date_to' => 'Akhir|dateflip',
            'days' => 'Hari|int',
            'office' => 'Pengguna'
        );
        foreach (array_keys($fields) as $kf)
            if ($this->input->post('check_' . $kf) != 'on') unset($fields[$kf]);
        $where = array(
            'asuransi' => null,
            'office' => null,
            'status' => null,
            'produksi' => null,
            'tipe' => null
        );
        foreach (array_keys($where) as $kw) {
            if ($this->input->post('val_' . $kw) == '') {
                unset($where[$kw]);
            } else {
                $where[$kw] = $this->input->post('val_' . $kw);
            }
        }
        if (empty($where) && $this->input->post('val_range') == '') {
            echo 'no data';
        } else {
            $this->load->model('List_model', 'lists');
            $datacount = $this->lists->where($where)->between(
                explode('-', $this->input->post('val_range'))
            )->data_count();
            if (intval($this->input->post('max_data')) > $datacount) {
                $this->load->library('Export_library', null, 'export');
                $this->export->set_fields($fields, true);
                $this->export->set_rows(
                    $this->lists->select(array_keys($fields))->order(['nomor'])->data_list()
                );
                $filename = $this->input->post('filename');
                $this->export->file($filename == '' ? 'export_file' : $filename);
            } else {
                echo 'too many data';
            }
        }
    }
}
