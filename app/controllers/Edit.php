<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Edit extends CI_Controller
{
    private $office;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('form_validation');
        $this->load->helper(['login', 'error', 'user']);
        $this->office = (array) get_user_office(
            $this->session->userdata('id')
        );
    }

    public function index()
    {
        if (is_login()) {
            custom_404_admin();
        } else {
            redirect();
        }
    }

    public function jaminan($param = null)
    {
        if (is_login()) {
            $jaminan = array();
            if ($param != null) {
                $this->load->model('Guarantee_model', 'guaranties');
                $jaminan = $this->guaranties->select()->where_enkrip($param);
            }
            if (!empty($jaminan) && $this->office['id'] == $jaminan['office']) {
                $this->_jaminan_view($jaminan);
            } else {
                custom_404_admin();
            }
        } else {
            redirect(login_url());
        }
    }

    private function _jaminan_view($jaminan)
    {
        $this->load->model('Report_model', 'reports');
        $blankodata = $this->reports->used(
            array('id', 'enkrip', 'asuransi', 'prefix', 'nomor', 'rev_status', 'color')
        )->where(array('jaminan' => $jaminan['real_id']))->data();
        $blankodata['status'] = $blankodata['rev_status'];
        $data['title'] = 'Edit Data Jaminan';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['bread'] = 'Blanko List,blanko/used|' . $blankodata['nomor'] . ',blanko/detail/' . $blankodata['enkrip'] . '|Edit Data';
        $data['blanko'] = $blankodata;
        $data['jaminan'] = $jaminan;
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('blanko/detail');
        $this->load->view('edit/jaminan');
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }
}
