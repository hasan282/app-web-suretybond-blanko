<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Blanko extends CI_Controller
{
    private $numbers;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('form_validation');
        $this->load->helper(['login', 'user', 'error', 'id', 'enkrip', 'format']);
    }

    public function index()
    {
        $this->_blanko_view('available');
    }

    public function used()
    {
        $this->_blanko_view('used');
    }

    public function crash()
    {
        $this->_blanko_view('crash');
    }

    private function _blanko_view($head)
    {
        if (true_access('blanko')) {
            $config = array('new_line_remove' => true);
            $this->load->library('Layout_library', $config, 'layout');
            $data['title'] = 'Blanko List';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $data['jscript'] = 'blanko/blanko.list.min';
            $data['status'] = $head;
            $this->layout->variable($data);
            $this->layout->row(array(
                ['blanko_list/filter', 'blanko_list/buttons'],
                ['blanko_list/navigation', 'blanko_list/list']
            ));
            $this->layout->script()->print();
        } else {
            custom_404_admin();
        }
    }

    public function detail($id = null)
    {
        if (true_access('blanko')) {
            if ($id === null) {
                custom_404_admin();
            } else {
                $this->_detail_process($id);
            }
        } else {
            custom_404_admin();
        }
    }

    private function _detail_process($id)
    {
        $this->load->model('Blanko_model', 'blankos');
        $blanko = $this->blankos->get_one($id);
        if (empty($blanko)) {
            custom_404_admin();
        } else {
            $office = get_user_office($this->session->userdata('id'));
            if ($blanko['id_office'] == $office->id || $office->id_tipe == '1') {
                $this->_detail_view($blanko, $office);
            } else {
                custom_404_admin();
            }
        }
    }

    private function _detail_view($blanko_data, $officedata)
    {
        $data['title'] = 'Detail Blanko';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $breads = array('1' => '', '2' => '/used', '3' => '/crash', '4' => '/crash');
        $data['bread'] = 'Blanko List,blanko' . $breads[$blanko_data['id_status']] . '|Detail';
        $data['blanko'] = $blanko_data;
        $data['true_office'] = ($blanko_data['id_office'] == $officedata->id);
        $config = array('new_line_remove' => true);
        $this->load->library('Layout_library', $config, 'layout');
        $this->layout->variable($data);
        $this->layout->content('blanko/detail');
        if ($blanko_data['id_jaminan'] != null) {
            $this->load->model('Guarantee_model', 'guaranties');
            $data['jaminan'] = $this->guaranties->select()->where_id($blanko_data['id_jaminan']);
            if (!empty($data['jaminan'])) {
                $this->layout->variable($data);
                $this->layout->content('blanko/detail_used');
            }
        } else {
            if ($blanko_data['id_status'] != '1') $this->layout->content('info/add_jaminan');
        }
        if ($blanko_data['id_crash'] != null)
            $this->layout->content('blanko/detail_crash');
        if ($blanko_data['id_status'] != '1' && $officedata->id_tipe == '1' && special_access([1, 2]))
            $this->layout->content('produksi/detail');
        if ($blanko_data['id_office'] == $officedata->id)
            $this->layout->content('blanko/detail_buttons');
        $this->layout->script()->print();
    }

    public function add()
    {
        if (special_access([1, 2])) {
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('number_from', 'Dari', 'required|regex_match[/^[0-9]*$/]');
            $this->form_validation->set_rules('number_to', 'Sampai', 'required|regex_match[/^[0-9]*$/]');
            $from = $this->input->post('number_from');
            $to = $this->input->post('number_to');
            if (
                strlen($from) === strlen($to) &&
                intval($to) - intval($from) > 0 &&
                $this->form_validation->run() === true
            ) {
                $number = [];
                for ($int = intval($from); $int <= intval($to); $int++) array_push($number, str_pad($int, strlen($to), '0', STR_PAD_LEFT));
                $this->numbers = $number;
                $this->_add_data();
            } else {
                $this->_add_view('add', 'blanko_add');
            }
        } else {
            custom_404_admin();
        }
    }

    public function tambah()
    {
        if (special_access([1, 2])) {
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('numbers', 'Nomor Blanko', 'required');
            if ($this->form_validation->run() === false) {
                $this->_add_view('tambah', 'blanko_tambah');
            } else {
                $this->numbers = explode(',', $this->input->post('numbers'));
                $this->_add_data(false);
            }
        } else {
            custom_404_admin();
        }
    }

    private function _add_view($body, $js = null)
    {
        $data['title'] = 'Tambah Blanko';
        $data['plugin'] = 'basic|fontawesome|icheck|scrollbar|dateinput';
        $data['bread'] = 'Blanko List,blanko|Tambah Baru';
        if ($js != null) $data['jscript'] = $js;
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('blanko/form_head');
        $this->load->view('blanko/form_body_' . $body);
        $this->load->view('blanko/form_foot');
        $this->load->view('blanko/form_foot_' . $body);
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }

    private function _add_data($automatic = true)
    {
        $id = date('ymdHis') . mt_rand(1000, 9999);
        $user = get_real_id('user', $this->session->userdata('id'));
        $asuransi = get_real_id('asuransi', $this->input->post('asuransi'));
        $office = get_user_office($this->session->userdata('id'));
        $data_blanko = [];
        $data_record = array(
            'id' => $id,
            'enkripsi' => self_md5($id),
            'id_asuransi' => $asuransi,
            'tanggal' => $this->input->post('tanggal'),
            'id_user' => $user
        );
        if ($automatic) {
            $data_record['number_from'] = $this->numbers[0];
            $data_record['number_to'] = end($this->numbers);
        } else {
            $data_record['numbers'] = implode(', ', $this->numbers);
        }
        foreach ($this->numbers as $num) {
            $replace = (strlen($num) < 6) ? strlen($num) : 6;
            $blanko['id'] = substr($id, 0, -$replace) . substr($num, -$replace);
            $blanko['enkripsi'] = self_md5($blanko['id']);
            $blanko['id_asuransi'] = $asuransi;
            $blanko['prefix'] = $this->input->post('prefix');
            $blanko['nomor'] = $num;
            $blanko['id_office'] = $office->id;
            $blanko['id_status'] = 1;
            $blanko['date_in'] = $this->input->post('tanggal');
            $blanko['keterangan'] = trim($this->input->post('keterangan'));
            foreach ($blanko as $k => $v) if ($v == '') unset($blanko[$k]);
            array_push($data_blanko, $blanko);
        }
        $result_blanko = false;
        $result_record = false;
        $this->load->model('Blanko_model', 'blankos');
        $data_check = $this->blankos->blanko_check($asuransi, $this->numbers);
        if (!empty($data_check)) {
            $this->session->set_flashdata('flash_data', array(
                'status' => 'failed',
                'type' => 'add',
                'message' => 'Ditemukan ' . sizeof($data_check) . ' Data Blanko dengan Nomor yang sama'
            ));
            redirect('result/failed');
        } else {
            $result_blanko = $this->db->insert_batch('blanko', $data_blanko, null, sizeof($data_blanko));
            $result_record = $this->db->insert('record_add', $data_record);
            if ($result_blanko && $result_record) {
                $flash_message = sizeof($data_blanko) . ' Data Blanko Baru telah ditambahkan.';
                $this->session->set_flashdata('flash_message', $flash_message);
                redirect('result/add/' . $data_record['enkripsi']);
            } else {
                $this->session->set_flashdata('flash_data', array(
                    'status' => 'failed',
                    'type' => 'add',
                    'message' => 'Terjadi kesalahan pada sistem'
                ));
                redirect('result/failed');
            }
        }
    }

    public function send()
    {
        if (is_login()) {
            $from = $this->input->post('number_from');
            $to = $this->input->post('number_to');
            $this->form_validation->set_rules('number_from', 'Dari', 'required|regex_match[/^[0-9]*$/]');
            $this->form_validation->set_rules('number_to', 'Sampai', 'required|regex_match[/^[0-9]*$/]');
            if ($this->form_validation->run() === true) {
                $this->_send_process($from, $to);
            } else {
                $this->_send_view();
            }
        } else {
            redirect(login_url());
        }
    }

    private function _send_view()
    {
        $data['title'] = 'Kirim Blanko';
        $data['plugin'] = 'basic|fontawesome|scrollbar|dateinput';
        $data['bread'] = 'Blanko List,blanko|Kirim Blanko';
        $data['jscript'] = 'blanko_send';
        $this->load->database();
        $config = array('new_line_remove' => true);
        $this->load->library('Layout_library', $config, 'layout');
        $this->layout->variable($data);
        $this->layout->content('blanko/send');
        $this->layout->script()->print();
    }

    private function _send_process($from, $to)
    {
        $this->load->model('Blanko_model', 'blankos');
        $office = get_user_office($this->session->userdata('id'));
        $user = get_real_id('user', $this->session->userdata('id'));
        $asuransi = get_real_id('asuransi', $this->input->post('asuransi'));
        $blanko = $this->blankos->blanko_send_allow(array($asuransi, $office->id, $from, $to), true);
        if (intval($to) - intval($from) + 1 === sizeof($blanko)) {
            $data_send = [];
            $data_record = array(
                'id' => date('ymdHis') . mt_rand(1000, 9999),
                'id_asuransi' => $asuransi,
                'number_from' => $from,
                'number_to' => $to,
                'office_from' => $this->input->post('office_from'),
                'office_to' => $this->input->post('office_to'),
                'tanggal' => $this->input->post('tanggal'),
                'id_user' => $user
            );
            $data_record['enkripsi'] = self_md5($data_record['id']);
            foreach ($blanko as $bl) array_push($data_send, array(
                'id_blanko' => $bl['id'],
                'office_from' => $this->input->post('office_from'),
                'office_to' => $this->input->post('office_to'),
                'date' => $this->input->post('tanggal')
            ));
            $result_send = $this->db->insert_batch('blanko_sent', $data_send, null, sizeof($data_send));
            $result_record = $this->db->insert('record_sent', $data_record);
            $result_update = $this->blankos->update_office($this->input->post('office_to'), $data_send, 'id_blanko');
            if ($result_send === $result_update && $result_record) {
                $flash_message = sizeof($data_send) . ' Blanko telah dikirim.';
                $this->session->set_flashdata('flash_message', $flash_message);
                redirect('result/send/' . $data_record['enkripsi']);
            } else {
                $this->session->set_flashdata('flash_data', array(
                    'status' => 'failed',
                    'type' => 'send',
                    'message' => 'Terjadi kesalahan pada sistem'
                ));
                redirect('result/failed');
            }
        } else {
            $this->session->set_flashdata('flash_data', array(
                'status' => 'failed',
                'type' => 'send',
                'message' => 'Tidak ditemukan Data Blanko yang akan dikirim'
            ));
            redirect('result/failed');
        }
    }
}
