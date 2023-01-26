<?php
defined('BASEPATH') or exit('No direct script access allowed');
class History extends SELF_Controller
{
    public function __construct()
    {
        parent::__construct();
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
            $this->layout->variable($data);
            if (empty($data['history'])) {
                $this->layout->content('history/empty');
            } else {
                $this->layout->content('history/main');
            }
            $this->layout->script()->print();
        } else {
            redirect(login_url());
        }
    }
}
