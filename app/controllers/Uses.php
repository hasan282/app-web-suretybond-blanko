<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Uses extends SELF_Controller
{
    private $office;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['login', 'error', 'user', 'id', 'enkrip']);
        $this->office = get_user_office($this->session->userdata('id'));
    }

    public function index()
    {
        if (true_access()) {
            $subagent = null;
            $id = self_decrypt($this->input->get('var'));
            if ($id != false) $subagent = $this->db->get_where('office', ['id' => $id])->row();
            if ($subagent === null) {
                $this->_use_view();
            } else {
                $this->_use_blanko((array) $subagent);
            }
        } else {
            custom_404_admin();
        }
    }

    public function rev($param = null)
    {
        if (true_access('uses')) {
            $subagent = null;
            $id = self_decrypt($this->input->get('var'));
            if ($id != false) $subagent = $this->db->get_where('office', ['id' => $id])->row();
            if ($subagent === null) {
                custom_404_admin();
            } else {
                if ($param === null) {
                    $this->_revision_view((array) $subagent);
                } else {
                    $blankodata = $this->_blankodata($param);
                    if (empty($blankodata)) {
                        custom_404_admin();
                    } else {
                        var_dump($blankodata);
                    }
                }
            }
        } else {
            custom_404_admin();
        }
    }

    private function _use_view()
    {
        $data['title'] = 'Penggunaan';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['office'] = $this->office;
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('blanko_use/select');
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }

    private function _revision_view($agent)
    {
        $data['title'] = 'Revisi Blanko ' . $agent['nickname'];
        $data['bread'] = 'Penggunaan,uses|Revisi ' . $agent['nickname'];
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['jscript'] = 'functions/table|blanko/revision.for';
        $data['status'] = 'used';
        $data['officedata'] = $agent;
        $this->layout->variable($data);
        $this->layout->content('revision/for_office');
        $this->layout->content('revision/filter');
        $this->layout->content('blanko_list/list');
        $this->layout->script()->print();
    }

    private function _use_blanko($office)
    {
        $data['title'] = 'Blanko Tersedia';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['bread'] = 'Penggunaan,uses|Blanko Tersedia';
        $data['jscript'] = 'functions/table.min|blanko/revision';
        $data['office'] = $office;
        $this->layout->variable($data);
        $this->layout->content('info/for_office');
        $this->layout->row(array(
            ['info/blanko_use', 'blanko_list/filter'],
            'blanko_list/list'
        ));
        $this->layout->script()->print();
    }

    public function in($param = null)
    {
        if (true_access('uses')) {
            $id_office = self_decrypt($this->input->get('var'));
            if ($param === null || $id_office === false) {
                if ($param === null) {
                    custom_404_admin();
                } else {
                    $blankodata = $this->_blankodata($param);
                    $this->load->library('form_validation', null, 'forms');
                    $this->forms->set_rules('jenis', 'Jenis Jaminan', 'required');
                    $this->forms->set_rules('nilai', 'Nilai Jaminan', 'required|regex_match[/^[0-9.,]*$/]');
                    $this->forms->set_rules('jaminan_num', 'Nomor Jaminan', 'required');
                    $this->forms->set_rules('tanggal_from', 'Tanggal Mulai', 'required');
                    $this->forms->set_rules('tanggal_to', 'Tanggal Berakhir', 'required');
                    $this->forms->set_rules('days', 'Jumlah Hari', 'required');
                    if (empty($blankodata) || $this->forms->run() === false) {
                        custom_404_admin();
                    } else {
                        $this->_use_process($blankodata);
                    }
                }
            } else {
                $officedata = $this->db->get_where('office', ['id' => $id_office])->row();
                $blankodata = $this->_blankodata($param);
                if ($officedata === null || empty($blankodata)) {
                    custom_404_admin();
                } else {
                    $this->_input($officedata, $blankodata);
                }
            }
        } else {
            custom_404_admin();
        }
    }

    private function _input($office, $blanko)
    {
        $data['title'] = 'Gunakan Blanko';
        $office_link = $office->nickname . ',uses?var=' . urlencode(self_encrypt($office->id));
        $data['bread'] = 'Penggunaan,uses|' . $office_link . '|Gunakan';
        $data['plugin'] = 'basic|fontawesome|scrollbar|fileinput|dateinput';
        $data['blanko'] = $blanko;
        $data['office'] = (array) $office;
        $data['jscript'] = 'process/used.min';
        $this->layout->variable($data);
        $this->layout->content(array(
            'blanko_use/header',
            'info/for_office',
            'blanko_use/form'
        ));
        $this->layout->script()->print();
    }

    private function _use_process($blanko)
    {
        $this->load->model('Blanko_use_model', 'blankouse');
        $user = get_real_id('user', $this->session->userdata('id'));
        $asuransi = get_real_id('asuransi', $blanko['id_asuransi']);
        $id_office = self_decrypt($this->input->post('var_value'));
        if ($id_office === false || $blanko['id_status'] != '1') {
            custom_404_admin();
        } else {
            $this->blankouse->set_office($id_office);
            $this->blankouse->set_change(['id_office' => $id_office]);
            $data_send = array(
                'id_blanko' => $blanko['id'],
                'office_from' => $blanko['id_office'],
                'office_to' => $id_office,
                'date' => date('Y-m-d')
            );
            $data_record = array(
                'id' => date('ymdHis'),
                'id_asuransi' => $asuransi,
                'number_from' => $blanko['nomor'],
                'number_to' => $blanko['nomor'],
                'office_from' => $blanko['id_office'],
                'office_to' => $id_office,
                'tanggal' => date('Y-m-d'),
                'id_user' => $user
            );
            $data_record['enkripsi'] = self_md5($data_record['id']);
            if (
                $this->blankouse->process($blanko['id']) &&
                $this->db->insert('blanko_sent', $data_send) &&
                $this->db->insert('record_sent', $data_record)
            ) {
                $flash_message = array('status' => 'success');
                $this->session->set_flashdata('flash_message', $flash_message);
                redirect('blanko/detail/' . self_md5($blanko['id']));
            } else {
                $flash_message = array('status' => 'danger');
                $this->session->set_flashdata('flash_message', $flash_message);
                redirect('blanko/detail/' . self_md5($blanko['id']));
            }
        }
    }

    private function _blankodata($enkrip)
    {
        $select = array('id', 'prefix', 'nomor', 'id_asuransi', 'asuransi', 'id_office', 'id_status', 'id_jaminan');
        $this->load->model('Blanko_model', 'blankos');
        $data = $this->blankos->get_one($enkrip, $select);
        return $data;
    }
}
