<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'models/Data_model.php';
class Production_model extends Data_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function select()
    {
        $fields = array('enkripsi', 'prefix', 'nomor', 'status', 'color', 'jaminan', 'principal', 'office_nick');
        $this->blanko($fields);
        $this->query .= ' WHERE blanko_status.id IN (2, 3, 4)';
        return $this;
    }

    public function where($where = null)
    {
        if ($where === null) {
            $this->query .= ' AND blanko.laprod IS NULL';
        } else {
            $this->query .= ' AND blanko.laprod = ?';
            $this->binds = $where;
        }
        return $this;
    }

    public function filter($filter = [])
    {
        $filters = array(
            'asuransi' => '',
            'office' => '',
            'pemakaian' => ''
        );
    }

    public function order()
    {
        $this->query .= ' ORDER BY asuransi.id ASC, blanko.nomor ASC';
        return $this;
    }
}
