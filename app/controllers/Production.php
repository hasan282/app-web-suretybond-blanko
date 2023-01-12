<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Production extends SELF_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['login', 'error', 'user', 'format', 'id', 'enkrip']);
        $this->load->model('Report_model', 'report');
    }

    public function index()
    {
        if (true_access()) {
            $this->load->library('form_validation', null, 'forms');
            $this->forms->set_rules('month', 'Bulan', 'required|regex_match[/^[0-9-]*$/]');
            if (empty($_POST) || $this->forms->run() === false) {
                $this->_index_view();
            } else {
                $this->_index_process();
            }
        } else {
            custom_404_admin();
        }
    }

    private function _index_view()
    {
        $selected = array('enkrip_use', 'prefix', 'nomor', 'jaminan', 'principal', 'office_nick', 'rev_status', 'color');
        $data['title'] = 'Produksi';
        $data['plugin'] = 'basic|fontawesome|scrollbar|icheck';
        $data['blankodata'] = $this->report->used($selected)
            ->wheres('blanko_used.produksi IS NULL')->order(['used'])->data_list();
        $data['jscript'] = 'functions/check|produksi/main';
        $this->layout->variable($data);
        $this->layout->content('produksi/buttons');
        $this->layout->content('produksi/used_list');
        $this->layout->script()->print();
    }

    private function _index_process()
    {
        $usedlist = array();
        $month = $this->input->post('month');
        $pref = 'check_';
        foreach ($_POST as $key => $val) {
            if (substr($key, 0, 6) == $pref && $val == 'on') {
                array_push($usedlist, "'" . str_replace($pref, '', $key) . "'");
            }
        }
        $query = 'UPDATE blanko_used SET produksi = ? WHERE enkripsi IN (' . implode(', ', $usedlist) . ')';
        $result = $this->db->query($query, $month);

        redirect('production');
    }

    public function report()
    {
        if (true_access('production')) {
            $params = $this->input->get('val');
            if ($params == '') {
                $this->_report_view();
            } else {
                $month = self_decrypt($params);
                if ($month === false) {
                    custom_404_admin();
                } else {
                    $this->_report_view($month);
                }
            }
        } else {
            custom_404_admin();
        }
    }

    private function _report_view($param = null)
    {
        $month = date('Y-m');
        if ($param != null) $month = $param;
        $data['title'] = 'Laporan Bulanan';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['bread'] = 'Produksi,production|Laporan Bulanan';
        $data['report'] = $this->_report_data($month);
        $this->layout->variable($data);
        $this->layout->content('produksi/selector');
        $this->layout->content('produksi/table');
        $this->layout->script()->print();
    }

    private function _report_data($param)
    {
        $fields = array('enkrip', 'prefix', 'nomor', 'jaminan', 'jaminan_tipe_2', 'principal', 'obligee', 'kontrak', 'pekerjaan', 'currency_2', 'jaminan_nilai', 'date_from', 'date_to', 'days');
        $month = array();
        $report = array();
        $select = null;
        $monthdata = $this->db->query('SELECT produksi FROM blanko_used WHERE produksi IS NOT NULL GROUP BY produksi ORDER BY produksi DESC')->result_array();
        if (!empty($monthdata)) foreach ($monthdata as $md) array_push($month, $md['produksi']);
        if (!empty($month)) {
            $select = (in_array($param, $month)) ? $param : $month[0];
            $report = $this->report->used()->where(array('produksi' => $select))->order(array('asuransi', 'used'))->data_list();
        }
        return array('list' => $month, 'report' => $report, 'select' => $select);
    }
}
