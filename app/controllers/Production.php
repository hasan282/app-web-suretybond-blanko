<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Production extends SELF_Controller
{
    private $limit, $page, $filter;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['login', 'error', 'user', 'format', 'id', 'enkrip']);
        $this->load->model('Report_model', 'report');
        $this->load->model('Production_model', 'production');
        $page_limit = $this->session->flashdata('page_limit');
        $page_number = $this->session->flashdata('page_number');
        $page_filter = $this->session->flashdata('page_filter');
        $this->limit = $page_limit ?: 10;
        $this->page = $page_number ?: 1;
        $this->filter = $page_filter ?: array();
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
        $blankos = $this->production->select()->where(null)->filter($this->filter)->order();
        $data['title'] = 'Produksi';
        $data['plugin'] = 'basic|fontawesome|scrollbar|icheck';
        $data['pagination'] = array(
            'limit' => $this->limit,
            'page' => $this->page,
            'offset' => ($this->page - 1) * $this->limit
        );
        $data['blankodata'] = array(
            'page' => 0,
            // 'page' => $blankos->count(),
            'data' => $blankos->limit(
                $data['pagination']['limit'],
                $data['pagination']['offset']
            )->data_list()
            // )->query_string()
        );
        $data['jscript'] = 'functions/check|produksi/main';
        $this->layout->variable($data);
        $this->layout->content('produksi/filters');
        $this->layout->content('produksi/buttons');
        if (empty($data['blankodata']['data'])) {
            $this->layout->content('produksi/empty');
        } else {
            $this->layout->content('produksi/used_list');
        }
        $this->layout->script()->print();

        // var_dump($data['blankodata']['data']);
        // echo '<textarea>' . $data['blankodata']['data']['query'] . '</textarea>';
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
        $query = 'UPDATE blanko SET laprod = ? WHERE enkripsi IN (' . implode(', ', $usedlist) . ')';
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
        if (empty($data['report']['list'])) {
            $this->layout->content('produksi/null');
        } else {
            $this->layout->content('produksi/selector');
            $this->layout->content('produksi/table');
        }
        $this->layout->script()->print();
    }

    private function _report_data($param)
    {
        $month = array();
        $report = array();
        $select = null;
        $monthdata = $this->db->query(
            'SELECT laprod FROM blanko WHERE laprod IS NOT NULL GROUP BY laprod ORDER BY laprod DESC'
        )->result_array();
        if (!empty($monthdata)) foreach ($monthdata as $md) array_push($month, $md['laprod']);
        if (!empty($month)) {
            $select = (in_array($param, $month)) ? $param : $month[0];
            $report = $this->production->select()->where($select)->order()->data_list();
        }
        return array('list' => $month, 'report' => $report, 'select' => $select);
    }

    public function setlist($limit = null, $number = null)
    {
        $filters = explode('-and-', $this->input->get('filter'));
        if (sizeof($filters) === 3) {
            $filname = array('asuransi', 'office', 'pemakaian');
            $fil = array();
            foreach ($filname as $ky => $fn) if ($filters[$ky] != '') $fil[$fn] = $filters[$ky];
            if (!empty($fil)) $this->session->set_flashdata('page_filter', $fil);
        }

        if ($limit != null)
            $this->session->set_flashdata('page_limit', $limit);
        if ($number != null)
            $this->session->set_flashdata('page_number', $number);
        redirect($this->input->get('log'));

        // var_dump($filters);
    }

    public function setmonth()
    {
        $blanko = $this->input->post('blanko');
        $month = $this->input->post('month');
        if (true_access('production')) {
            if (empty($_POST) || $blanko == '' || $month == '') {
                custom_404_admin();
            } else {
                $this->db->update('blanko', array('laprod' => $month), array('enkripsi' => $blanko));
                redirect('blanko/detail/' . $blanko);
            }
        } else {
            custom_404_admin();
        }
    }

    public function sub()
    {
        if (is_login()) {
            $blanko = $this->db->get_where(
                'blanko',
                ['enkripsi' => $this->input->post('blanko')]
            )->row();
            if ($blanko === null) {
                redirect('blanko/used');
            } else {
                $result = false;
                $month = $this->db->get_where('pemakaian', ['id_blanko' => $blanko->id])->row();
                if ($month === null) {
                    $result = $this->db->insert('pemakaian', array(
                        'id_blanko' => $blanko->id,
                        'bulan' => $this->input->post('month')
                    ));
                } else {
                    $result = $this->db->update('pemakaian', array(
                        'bulan' => $this->input->post('month')
                    ), array(
                        'id_blanko' => $blanko->id
                    ));
                }
                if ($result) {
                    // success
                    redirect('blanko/detail/' . $blanko->enkripsi);
                } else {
                    // failed
                    redirect('blanko');
                }
            }
        } else {
            redirect();
        }
    }
}
