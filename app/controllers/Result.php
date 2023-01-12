<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Result extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $config = array('new_line_remove' => true);
        $this->load->library('Layout_library', $config, 'layout');
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->helper(['login', 'error', 'user', 'format']);
        $this->load->model('Select_model', 'selector');
    }

    public function index()
    {
        if (is_login()) {
            custom_404_admin();
        } else {
            redirect();
        }
    }

    public function add($id = null)
    {
        if (!special_access([1, 2]) || $id === null) {
            custom_404_admin();
        } else {
            $data_add = $this->selector->get_record_add($id);
            if ($data_add) {
                $data['title'] = 'Hasil Tambah Data';
                $data['plugin'] = 'basic|fontawesome|scrollbar';
                $data['bread'] = 'Blanko List,blanko|Tambah Baru,blanko/add|Hasil';
                $data['result'] = $data_add;
                $this->layout->variable($data);
                $this->layout->content('result/add');
                $this->layout->script()->print();
            } else {
                custom_404_admin();
            }
        }
    }

    public function send($id = null)
    {
        if (!true_access('blanko') || $id === null) {
            custom_404_admin();
        } else {
            $data_send = $this->selector->get_record_sent($id);
            if ($data_send) {
                $data['title'] = 'Hasil Kirim Blanko';
                $data['plugin'] = 'basic|fontawesome|scrollbar';
                $data['bread'] = 'Blanko List,blanko|Kirim,blanko/send|Hasil';
                $data['result'] = $data_send;
                $this->load->view('template/head', $data);
                $this->load->view('template/navbar');
                $this->load->view('template/sidebar');
                $this->load->view('result/send');
                $this->load->view('template/footer');
                $this->load->view('template/foot');
            } else {
                custom_404_admin();
            }
        }
    }

    public function failed()
    {
        $failed_data = $this->session->flashdata('flash_data');
        if ($failed_data === null) {
            redirect();
        } else {
            $titles = array(
                'add' => ['Tambah Data', 'Tambah,blanko/add'],
                'send' => ['Kirim Blanko', 'Kirim,blanko/send']
            );
            $data['title'] = 'Hasil ' . $titles[$failed_data['type']][0];
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $data['bread'] = 'Blanko List,blanko|' . $titles[$failed_data['type']][1] . '|Hasil';
            $data['data_type'] = $failed_data['type'];
            $data['data_message'] = $failed_data['message'];
            $this->load->view('template/head', $data);
            $this->load->view('template/navbar');
            $this->load->view('template/sidebar');
            $this->load->view('result/failed');
            $this->load->view('template/footer');
            $this->load->view('template/foot');
        }
    }
}
