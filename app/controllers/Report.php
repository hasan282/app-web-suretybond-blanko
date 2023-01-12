<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Report extends CI_Controller
{
    private $office;
    private $limit;
    private $page;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->model('Recap_model', 'recaps');
        $this->load->helper(['login', 'user', 'error', 'format', 'enkrip']);
        $this->office = get_user_office($this->session->userdata('id'));
        $this->limit = 10;
        $this->page = 1;
    }

    public function index()
    {
        if (special_access([1, 2])) {
            $this->_per_office();
        } else {
            $this->_per_asuransi($this->office->id);
        }
    }

    public function agent()
    {
        $param = $this->input->get('var');
        if (!special_access([1, 2]) || $param == '') {
            custom_404_admin();
        } else {
            $office = self_decrypt($param);
            if ($office === false) {
                custom_404_admin();
            } else {
                $this->_per_asuransi($office, true);
            }
        }
    }

    private function _per_office()
    {
        $data['title'] = 'Laporan';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['report'] = $this->recaps->recap_office()->get_data();
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('recap/switcher');
        $this->load->view('report/per_office');
        $this->load->view('report/export_button');
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }

    private function _per_asuransi($id_office = null, $admin = false)
    {
        $data['report'] = $this->recaps->recap_asuransi($id_office)->get_data();
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        if ($admin) {
            $officedata = $this->db->get_where('office', ['id' => $id_office])->row();
            $data['title'] = 'Rekap Blanko ' . $officedata->nickname;
            $data['bread'] = 'Laporan,report|Rekap ' . $officedata->nickname;
            $data['officename'] = $officedata->nama;
            $data['nickname'] = $officedata->nickname;
        } else {
            $data['title'] = 'Laporan';
        }
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('report/asuransi');
        if ($admin) {
            $this->load->view('report/period_button');
        } else {
            $this->load->view('report/print_button');
        }
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }

    public function insurance($param = null)
    {
        if ($param === null) {
            $this->_errors();
        } else {
            $asuransi = $this->db->get_where('asuransi', array('enkripsi' => $param))->row();
            if (special_access([1, 2])) {
                $office = self_decrypt($this->input->get('var'));
                if ($office === false || $asuransi === null) {
                    custom_404_admin();
                } else {
                    $this->_insurance_view($office, $asuransi, true);
                }
            } else {
                if ($asuransi === null) {
                    custom_404_admin();
                } else {
                    $this->_insurance_view($this->office->id, $asuransi);
                }
            }
        }
    }

    private function _insurance_view($office, $asuransi, $admin = false)
    {
        $str_title = '';
        $str_bread = '';
        $data['params'] = '';
        $officedata = $this->db->get_where('office', ['id' => $office])->row();
        if ($admin) {
            $str_title = $officedata->nickname . ' - ';
            $str_bread = '|' . $officedata->nickname . ',report/agent?' . $_SERVER['QUERY_STRING'];
            $data['params'] = $office . '_and_';
        }
        $data['title'] = 'Rekap Blanko ' . $str_title . $asuransi->nickname;
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['bread'] = 'Laporan,report' . $str_bread . '|Rekap ' . $asuransi->nickname;
        $data['asuransi'] = $asuransi;
        $data['recap'] = array();
        if ($officedata->id_tipe == '1') {
            $data['recap'] = $this->recaps->recap_date($asuransi->id, $office, true)->get_data();
        }
        if ($officedata->id_tipe == '2') {
            $data['recap'] = $this->recaps->recap_date($asuransi->id, $office)->get_data();
        }
        if (empty($data['recap'])) {
            custom_404_admin();
        } else {
            $this->load->view('template/head', $data);
            $this->load->view('template/navbar');
            $this->load->view('template/sidebar');
            $this->load->view('report/per_date');
            $this->load->view('template/footer');
            $this->load->view('template/foot');
        }
    }

    public function list($enkrip = null)
    {
        $this->_list_setup($enkrip);
    }

    public function listall($enkrip = null)
    {
        $this->_list_setup($enkrip, true);
    }

    private function _list_setup($enkrip = null, $get_all = false)
    {
        $page_limit = $this->session->flashdata('page_limit');
        $page_number = $this->session->flashdata('page_number');
        if ($page_limit != null) {
            $this->limit = $page_limit;
        }
        if ($page_number != null) {
            $this->page = $page_number;
        }
        if ($enkrip === null) {
            $this->_errors();
        } else {
            $params = self_decrypt($this->input->get('var'));
            $asuransi = $this->db->get_where('asuransi', array('enkripsi' => $enkrip))->row();
            if ($asuransi === null || $params === false) {
                $this->_errors();
            } else {
                if (special_access([1, 2])) {
                    $par = explode('_and_', $params);
                    $this->_list_view($par[0], $asuransi, $par[1], true, $get_all);
                } else {
                    $this->_list_view($this->office->id, $asuransi, $params, false, $get_all);
                }
            }
        }
    }

    private function _list_view($office, $asuransi, $date, $admin = false, $get_all = false)
    {
        $str_title = '';
        $str_bread = $asuransi->nickname . ',report/insurance/' . $asuransi->enkripsi;
        $officedata = $this->db->get_where('office', ['id' => $office])->row();
        $fields = [];
        if (!$get_all) {
            $fields = array('id', 'prefix', 'nomor', 'status', 'color', 'jaminan', 'principal');
        }
        if ($admin) {
            $query_string = '?var=' . urlencode(self_encrypt($office));
            $str_title = $officedata->nickname . ' - ';
            $str_bread = $officedata->nickname . ',report/agent' . $query_string . '|' . $str_bread . $query_string;
        }
        $data['title'] = 'List Blanko ' . $str_title . $asuransi->nickname;
        $data['plugin'] = 'basic|fontawesome|scrollbar|icheck';
        $data['bread'] = 'Laporan,report|' . $str_bread . '|List';
        $data['asuransi'] = $asuransi;
        $data['office'] = $officedata;
        $data['tanggal'] = $date;
        $data['pagination'] = array('limit' => $this->limit, 'page' => $this->page, 'offset' => ($this->page - 1) * $this->limit);
        $data['recap'] = $this->recaps->list($asuransi->id, $office, $date, ($officedata->id_tipe == '1'), $fields)->get_limit(
            $data['pagination']['limit'],
            $data['pagination']['offset']
        );
        if (empty($data['recap']) || empty($data['recap']['data'])) {
            custom_404_admin();
        } else {
            $config = array('new_line_remove' => false);
            $this->load->library('Layout_library', $config, 'layout');
            $this->layout->variable($data);
            $this->layout->content('report/list_nav');
            if ($get_all) {
                $this->layout->content('report/list_all');
            } else {
                $this->layout->content('report/list');
            }
            if ($data['recap']['count'] > sizeof($data['recap']['data'])) {
                $this->layout->content('report/list_pagin');
            }
            $this->layout->content('report/exportlist_button');
            $this->layout->script()->print();
        }
    }

    public function setlist($limit = 0, $number = 0)
    {
        $url_direct = $this->input->get('log');
        if ($url_direct == null) {
            $this->_errors();
        } else {
            $this->session->set_flashdata('page_limit', $limit);
            $this->session->set_flashdata('page_number', $number);
            redirect($this->input->get('log'));
        }
    }

    public function search()
    {
        if (is_login()) {
            $data['title'] = 'Laporan Penggunaan';
            $data['bread'] = 'Laporan,report|Penggunaan';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $this->load->view('template/head', $data);
            $this->load->view('template/navbar');
            $this->load->view('template/sidebar');
            // $this->load->view('');
            $this->load->view('template/footer');
            $this->load->view('template/foot');
        } else {
            redirect(login_url());
        }
    }

    public function period()
    {
        $param = $this->input->get('var');
        if (!special_access([1, 2]) || $param == '') {
            custom_404_admin();
        } else {
            $office = self_decrypt($param);
            if ($office === false) {
                custom_404_admin();
            } else {
                var_dump($office);
            }
        }
    }

    private function _errors()
    {
        if (is_login()) {
            custom_404_admin();
        } else {
            redirect(login_url());
        }
    }
}
