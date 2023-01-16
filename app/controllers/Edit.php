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
                var_dump($jaminan);
                var_dump($this->office);
            } else {
                custom_404_admin();
            }
        } else {
            redirect(login_url());
        }
    }

    private function _jaminan_view()
    {
    }
}
