<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Search extends SELF_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['login', 'error', 'user']);
    }

    public function index()
    {
        if (true_access()) {
            $data['title'] = 'Pencarian';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $this->layout->variable($data);
            $this->layout->content('search/filters');
            $this->layout->script()->print();
        } else {
            custom_404_admin();
        }
    }
}
