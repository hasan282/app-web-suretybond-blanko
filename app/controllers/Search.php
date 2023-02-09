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
            $data['jscript'] = 'functions/table.min|search/funct';
            $this->layout->variable($data);
            $this->layout->content('search/filters');
            $this->layout->content('search/table');
            $this->layout->script()->print();
        } else {
            custom_404_admin();
        }
    }

    public function que()
    {
        $where = array();
        $fields = array(
            'enkripsi', 'asuransi_nick', 'prefix', 'nomor',
            'status', 'color', 'produksi', 'principal', 'office_nick'
        );
        $filter = array('asuransi', 'nomor', 'office', 'status');
        foreach ($filter as $fil) {
            $value = $this->input->get($fil);
            if ($value != '') $where[$fil] = $value;
        }
        $limit = intval($this->input->get('limit'));
        if ($limit < 10 || $limit > 100) $limit = 10;
        $this->load->model('List_model', 'lists');
        $data = $this->lists->select($fields)->where($where)->order(['asuransi', 'nomor'])->query_string();
        var_dump($data);
        echo $data['query'];
    }
}
