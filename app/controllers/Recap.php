<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Recap extends SELF_Controller
{
    private $limit, $page;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['login', 'user', 'error', 'format', 'enkrip']);
        $this->load->model('Recap_model', 'recaps');
        $this->limit = 10;
        $this->page = 1;
    }

    public function index()
    {
        if (special_access([1, 2])) {
            $this->_index_view();
        } else {
            custom_404_admin();
        }
    }

    private function _index_view()
    {
        $data['title'] = 'Laporan';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['report'] = $this->recaps->recap_asuransi()->get_data();
        $this->layout->variable($data);
        $this->layout->content('recap/switcher');
        $this->layout->content('recap/asuransi');
        $this->layout->script()->print();
    }

    public function ins($param = null)
    {
        if (special_access([1, 2])) {
            if ($param === null) {
                custom_404_admin();
            } else {
                $asuransi = $this->db->get_where('asuransi', array('enkripsi' => $param))->row();
                if ($asuransi === null) {
                    custom_404_admin();
                } else {
                    $this->_ins_view($asuransi);
                }
            }
        } else {
            custom_404_admin();
        }
    }

    private function _ins_view($asuransi)
    {
        $data['title'] = 'Rekap Blanko ' . $asuransi->nickname;
        $data['bread'] = 'Laporan,recap|Rekap ' . $asuransi->nickname;
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['direct'] = 'recap/agent/' . $asuransi->enkripsi . '?var=';
        $data['report'] = $this->recaps->recap_office($asuransi->id)->get_data();
        $data['asuransi'] = $asuransi;
        $this->layout->variable($data);
        $this->layout->content('report/per_office');
        $this->layout->content('recap/arrive');
        $this->layout->script()->print();
    }

    public function setup()
    {
    }

    public function b($param = null)
    {
        $page_limit = $this->session->flashdata('page_limit');
        $page_number = $this->session->flashdata('page_number');
        if ($page_limit != null) {
            $this->limit = $page_limit;
        }
        if ($page_number != null) {
            $this->page = $page_number;
        }
        if (special_access([1, 2])) {
            if ($param === null) {
                custom_404_admin();
            } else {
                $vars = self_decrypt($this->input->get('var'));
                $asuransi = $this->db->get_where('asuransi', array('enkripsi' => $param))->row();
                if ($asuransi === null || $vars === false) {
                    custom_404_admin();
                } else {
                    $this->_b_view($asuransi, $vars);
                }
            }
        } else {
            custom_404_admin();
        }
    }

    private function _b_view($asuransi, $numbers)
    {
        $bread = $asuransi->nickname . ',recap/ins/' . $asuransi->enkripsi;
        $number = explode('_and_', $numbers);
        $data['title'] = 'List Blanko ' . $asuransi->nickname;
        $data['bread'] = 'Laporan,recap|' . $bread . '|List Blanko';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['report'] = $this->recaps
            ->list_between($asuransi->id, $number[0], $number[1])
            ->get_limit(0);
        $data['header'] = ($number[0] == $number[1]) ? $number[0] : $number[0] . ' - ' . $number[1];
        $data['enkrip'] = $this->db->query("SELECT enkripsi FROM record_add WHERE id_asuransi = '" . $asuransi->id . "' AND (number_from = '" . $number[0] . "' OR number_to = '" . $number[1] . "' OR numbers LIKE '%" . $number[0] . "%')")->result_array();
        $this->layout->variable($data);
        $this->layout->content('recap/list');
        $this->layout->script()->print();
    }

    public function agent($param = null)
    {
        if (special_access([1, 2])) {
            if ($param === null) {
                custom_404_admin();
            } else {
                $vars = self_decrypt($this->input->get('var'));
                $asuransi = $this->db->get_where('asuransi', array('enkripsi' => $param))->row();
                if ($asuransi === null || $vars === false) {
                    custom_404_admin();
                } else {
                    $this->_agent_view($asuransi, $vars);
                }
            }
        } else {
            custom_404_admin();
        }
    }

    private function _agent_view($asuransi, $office)
    {
        $officedata = $this->db->get_where('office', ['id' => $office])->row();
        $bread = 'Laporan,recap|';
        $bread .= $asuransi->nickname . ',recap/ins/' . $asuransi->enkripsi;
        $bread .= '|Rekap ' . $officedata->nickname;
        $data['params'] = $office . '_and_';
        $data['title'] = 'Rekap Blanko ' . $asuransi->nickname . ' - ' . $officedata->nickname;
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['bread'] = $bread;
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
}
