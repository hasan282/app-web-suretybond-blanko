<?php
defined('BASEPATH') or exit('No direct script access allowed');
class History extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->helper(['login', 'error', 'user']);
    }

    public function index()
    {
        if (is_login()) {
            $limit = 10;
            $show = intval($this->input->get('show'));
            if ($show > 0 && $show < 100) $limit += $show;
            $this->load->model('History_model', 'histories');
            $user_id = get_user_id($this->session->userdata('id'));
            $data['title'] = 'Histori';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $data['history'] = $this->histories->get_list($user_id, $limit);
            $this->load->view('template/head', $data);
            $this->load->view('template/navbar');
            $this->load->view('template/sidebar');
            if (empty($data['history'])) {
                $this->load->view('history/empty');
            } else {
                $this->load->view('history/main');
            }
            $this->load->view('template/footer');
            $this->load->view('template/foot');
        } else {
            redirect(login_url());
        }
    }
}
