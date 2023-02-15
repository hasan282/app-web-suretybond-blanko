<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Revision extends CI_Controller
{
    private $office, $blankodata;

    public function __construct()
    {
        parent::__construct();
        $config = array('new_line_remove' => true);
        $this->load->library('Layout_library', $config, 'layout');
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->helper(['login', 'error', 'user', 'enkrip', 'format']);
        $this->office = get_user_office($this->session->userdata('id'));
        $this->blankodata = array();
    }

    public function index()
    {
        if (is_login()) {
            $data['title'] = 'Revisi Blanko Terpakai';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $data['jscript'] = 'functions/table|blanko/revision.search';
            $data['status'] = 'used';
            $data['office'] = $this->office->id;
            $this->layout->variable($data);
            $this->layout->content('info/revisi');
            $this->layout->content('revision/filter');
            $this->layout->content('blanko_list/list');
            $this->layout->script()->print();
        } else {
            redirect(login_url());
        }
    }

    public function b($param = null)
    {
        if (is_login()) {
            if ($param === null) {
                custom_404_admin();
            } else {
                $this->_set_blankodata($param);
                if (
                    !empty($this->blankodata) &&
                    $this->blankodata['office'] == $this->office->id
                ) {
                    $this->_revision_view($param);
                } else {
                    custom_404_admin();
                }
            }
        } else {
            redirect(login_url());
        }
    }

    public function in($param = null)
    {
        if (is_login()) {
            $vars = $this->input->get('var');
            if ($param === null || $vars == '') {
                if ($param != null) $this->_set_blankodata($param);
                $this->load->library('form_validation', null, 'forms');
                $this->forms->set_rules('jenis', 'Jenis Jaminan', 'required');
                $this->forms->set_rules('nilai', 'Nilai Jaminan', 'required|regex_match[/^[0-9.,]*$/]');
                $this->forms->set_rules('jaminan_num', 'Nomor Jaminan', 'required');
                $this->forms->set_rules('tanggal_from', 'Tanggal Mulai', 'required');
                $this->forms->set_rules('tanggal_to', 'Tanggal Berakhir', 'required');
                $this->forms->set_rules('days', 'Jumlah Hari', 'required');
                if (empty($this->blankodata) || $this->forms->run() === false) {
                    custom_404_admin();
                } else {
                    $this->_revision_process();
                }
            } else {
                $this->_set_blankodata($param);
                $new_blanko = $this->blankos->get_one($vars, array(
                    'asuransi', 'prefix', 'nomor', 'id_office', 'id_status'
                ));
                if (
                    !empty($this->blankodata) &&
                    !empty($new_blanko) &&
                    $new_blanko['id_status'] == '1' &&
                    sizeof(array_unique(array(
                        $this->blankodata['office'],
                        $this->office->id,
                        $new_blanko['id_office']
                    ))) === 1
                ) {
                    $this->_revision_input($param, $new_blanko);
                } else {
                    custom_404_admin();
                }
            }
        } else {
            redirect(login_url());
        }
    }

    private function _revision_view($param)
    {
        $data['title'] = 'Revisi Blanko';
        $breads = $this->blankodata['nomor'] . ',blanko/detail/' . $param;
        $data['bread'] = 'Blanko Terpakai,blanko/used|' . $breads . '|Revisi';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['blanko'] = $this->blankodata;
        $data['params'] = $param;
        $data['jscript'] = 'functions/table.min|blanko/revision';
        $this->layout->variable($data);
        $this->layout->row(array([
            'info/blanko_use',
            'revision/header',
            'blanko_list/filter'
        ], 'blanko_list/list'));
        $this->layout->script()->print();
    }

    private function _revision_input($param, $blanko_data)
    {
        $this->load->model('Guarantee_model', 'guaranties');
        $data['title'] = 'Gunakan Untuk Revisi';
        $breads = $this->blankodata['nomor'] . ',blanko/detail/' . $param . '|Revisi,revision/b/' . $param;
        $data['bread'] = 'Terpakai,blanko/used|' . $breads . '|Gunakan';
        $data['plugin'] = 'basic|fontawesome|scrollbar|fileinput|dateinput';
        $data['blanko'] = $blanko_data;
        $data['blanko']['old'] = $this->blankodata;
        $data['jscript'] = 'process/used.min';
        $data['jaminan'] = $this->guaranties->select()->where_id($this->blankodata['jaminan']);
        $this->layout->variable($data);
        $this->layout->content('blanko_use/header');
        $this->layout->content('blanko_use/revision');
        $this->layout->content('blanko_use/form');
        $this->layout->script()->print();
    }

    private function _revision_process()
    {
        $this->load->model('Blanko_use_model', 'uses');
        $new_blanko = $this->blankos->get_one(
            $this->input->post('var_value'),
            array('id', 'id_office', 'id_status')
        );
        if (
            !empty($new_blanko) &&
            $new_blanko['id_status'] == '1' &&
            sizeof(array_unique(array(
                $this->blankodata['office'],
                $this->office->id,
                $new_blanko['id_office']
            ))) === 1
        ) {
            if ($this->uses->process($new_blanko['id'], $this->blankodata['id'])) {
                $flash_message = array('status' => 'success');
                $this->session->set_flashdata('flash_message', $flash_message);
                redirect('blanko/detail/' . self_md5($new_blanko['id']));
            } else {
                $flash_message = array('status' => 'danger');
                $this->session->set_flashdata('flash_message', $flash_message);
                redirect('blanko/detail/' . self_md5($new_blanko['id']));
            }
        } else {
            custom_404_admin();
        }
    }

    private function _set_blankodata($enkrip)
    {
        $select = array('id', 'prefix', 'nomor', 'id_office', 'id_status', 'id_jaminan');
        $this->load->model('Blanko_model', 'blankos');
        $data = $this->blankos->get_one($enkrip, $select);
        if (!empty($data) && $data['id_status'] == '2') {
            $this->blankodata = array(
                'id' => $data['id'],
                'prefix' => $data['prefix'],
                'nomor' => $data['nomor'],
                'office' => $data['id_office'],
                'jaminan' => $data['id_jaminan']
            );
        }
    }
}
