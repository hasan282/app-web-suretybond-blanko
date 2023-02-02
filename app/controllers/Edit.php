<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Edit extends CI_Controller
{
    private $office;

    public function __construct()
    {
        parent::__construct();
        $config = array('new_line_remove' => true);
        $this->load->library('Layout_library', $config, 'layout');
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('form_validation', null, 'forms');
        $this->load->helper(['login', 'error', 'user', 'enkrip']);
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
                $this->load->model('Report_model', 'reports');
                $blankodata = $this->reports->used(
                    array('id', 'enkrip', 'asuransi', 'prefix', 'nomor', 'rev_status', 'color')
                )->where(array('jaminan' => $jaminan['real_id']))->data();
                $blankodata['status'] = $blankodata['rev_status'];
                $this->forms->set_rules('jenis', 'Jenis Jaminan', 'required');
                $this->forms->set_rules('currency', 'Mata Uang', 'required');
                $this->forms->set_rules('nilai', 'Nilai Jaminan', 'required|regex_match[/^[0-9.]*$/]');
                $this->forms->set_rules('jaminan_num', 'Nomor Jaminan', 'required');
                $this->forms->set_rules('tanggal_from', 'Dari Tanggal', 'required');
                $this->forms->set_rules('tanggal_to', 'Sampai Tanggal', 'required');
                $this->forms->set_rules('days', 'Jumlah Hari', 'required');
                if ($this->forms->run() === false) {
                    $this->_jaminan_view($jaminan, $blankodata);
                } else {
                    $this->_jaminan_process($jaminan, $blankodata);
                }
            } else {
                custom_404_admin();
            }
        } else {
            redirect(login_url());
        }
    }

    private function _jaminan_view($jaminan, $blankodata)
    {
        $data['title'] = 'Edit Data Jaminan';
        $data['plugin'] = 'basic|fontawesome|scrollbar|dateinput';
        $data['bread'] = 'Blanko List,blanko/used|' . $blankodata['nomor'] . ',blanko/detail/' . $blankodata['enkrip'] . '|Edit Data';
        $data['jscript'] = 'process/used.min';
        $data['blanko'] = $blankodata;
        $data['jaminan'] = $jaminan;
        $this->layout->variable($data);
        $this->layout->content('blanko/detail');
        $this->layout->content('edit/jaminan');
        $this->layout->script()->print();
    }

    private function _jaminan_process($jaminan, $blankodata)
    {
        $jaminan_data = array(
            'id_tipe' => $jaminan['tipe_id'],
            'nomor' => $jaminan['nomor'],
            'id_principal' => $jaminan['principal_id'],
            'id_obligee' => $jaminan['obligee_id'],
            'id_currency' => $jaminan['currency_id'],
            'nilai' => $jaminan['nilai'],
            'kontrak' => $jaminan['kontrak'],
            'pekerjaan' => $jaminan['pekerjaan'],
            'apply_date' => $jaminan['date'],
            'end_date' => $jaminan['date_to'],
            'apply_days' => $jaminan['day']
        );
        $jaminan_input = array(
            'id_tipe' => $this->input->post('jenis'),
            'nomor' => $this->input->post('jaminan_num'),
            'id_principal' => str_replace('NUM', '', $this->input->post('principal')),
            'id_obligee' => str_replace('NUM', '', $this->input->post('obligee')),
            'id_currency' => $this->input->post('currency'),
            'nilai' => str_replace('.', '', $this->input->post('nilai')),
            'kontrak' => trim($this->input->post('contract')),
            'pekerjaan' => trim($this->input->post('pekerjaan')),
            'apply_date' => $this->input->post('tanggal_from'),
            'end_date' => $this->input->post('tanggal_to'),
            'apply_days' => $this->input->post('days')
        );
        $new_principal = $this->input->post('principal_input');
        if ($new_principal != '') $jaminan_input['id_principal'] = $this->_new_data($new_principal, 'principal');
        $new_obligee = $this->input->post('obligee_input');
        if ($new_obligee != '') $jaminan_input['id_obligee'] = $this->_new_data($new_obligee, 'obligee');
        $jaminan_edit = array_diff_assoc($jaminan_input, $jaminan_data);
        if (empty($jaminan_edit)) {
            // tidak ada update
            redirect('blanko/detail/' . $blankodata['enkrip']);
        } else {
            $result_edit = $this->db->update('jaminan', $jaminan_edit, array('id' => $jaminan['real_id']));
            if ($result_edit) {
                // echo 'success';
                redirect('blanko/detail/' . $blankodata['enkrip']);
            } else {
                // echo 'failed';
                redirect('blanko/detail/' . $blankodata['enkrip']);
            }
        }
    }

    public function guarantee($param = null)
    {
        if (is_login()) {
            $blankodata = array();
            if ($param !== null) {
                $this->load->model('Blanko_model', 'blankos');
                $blankodata = $this->blankos->get_one($param, array(
                    'id', 'asuransi', 'prefix', 'nomor', 'id_office', 'id_status', 'status', 'color', 'id_jaminan'
                ));
            }
            if (
                empty($blankodata) ||
                $this->office['id'] !== $blankodata['id_office'] ||
                $blankodata['id_jaminan'] != null
            ) {
                custom_404_admin();
            } else {
                $this->forms->set_rules('jenis', 'Jenis Jaminan', 'required');
                $this->forms->set_rules('currency', 'Mata Uang', 'required');
                $this->forms->set_rules('nilai', 'Nilai Jaminan', 'required|regex_match[/^[0-9.]*$/]');
                $this->forms->set_rules('jaminan_num', 'Nomor Jaminan', 'required');
                $this->forms->set_rules('tanggal_from', 'Dari Tanggal', 'required');
                $this->forms->set_rules('tanggal_to', 'Sampai Tanggal', 'required');
                $this->forms->set_rules('days', 'Jumlah Hari', 'required');
                if ($this->forms->run() === false) {
                    $this->_guarantee_view($blankodata);
                } else {
                    $this->_guarantee_process($blankodata, $param);
                }
            }
        } else {
            redirect(login_url());
        }
    }

    private function _guarantee_view($blanko)
    {
        $data['title'] = 'Tambah Data Jaminan';
        $data['plugin'] = 'basic|fontawesome|scrollbar|dateinput';
        $data['bread'] = 'Blanko List,blanko/used|' . $blanko['nomor'] . ',blanko/detail/' . self_md5($blanko['id']) . '|Tambah Jaminan';
        $data['blanko'] = $blanko;
        $data['jscript'] = 'process/used.min';
        $this->layout->variable($data);
        $this->layout->content('blanko/detail');
        $this->layout->content('blanko_use/form');
        $this->layout->script()->print();
    }

    private function _guarantee_process($blanko, $enkrip)
    {
        $this->load->model('Blanko_use_model', 'uses');
        $this->uses->status_change(false);
        if ($this->uses->process($blanko['id'])) {
            // echo 'success';
            redirect('blanko/detail/' . $enkrip);
        } else {
            // echo 'failed';
            redirect('blanko/detail/' . $enkrip);
        }
    }

    private function _new_data($name, $table)
    {
        $result = '';
        $data = array(
            'id' => date('ymdHis') . mt_rand(1000, 9999),
            'nama' => strtoupper(trim($name)),
            'id_office' => $this->office['id']
        );
        if ($this->db->insert($table, $data)) $result = $data['id'];
        return $result;
    }
}
