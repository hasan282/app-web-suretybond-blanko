<?php
defined('BASEPATH') or exit('No direct script access allowed');
class List_model extends SELF_Model
{
    private $select, $from, $where, $order;

    public function __construct()
    {
        parent::__construct();
        $this->where = '';
    }

    public function select(array $field = [])
    {
    }

    public function from(array $table = [])
    {
    }

    private function _tables()
    {
        // blanko query table
        $blanko = '(((blanko INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id)';
        $blanko .= ' INNER JOIN office ON blanko.id_office = office.id)';
        $blanko .= ' INNER JOIN blanko_status ON blanko.id_status = blanko_status.id)';
        // blanko used and jaminan query table
        $jaminan = '(((((blanko_used INNER JOIN jaminan ON blanko_used.id_jaminan = jaminan.id)';
        $jaminan .= ' INNER JOIN jaminan_tipe ON jaminan.id_tipe = jaminan_tipe.id)';
        $jaminan .= ' INNER JOIN principal ON jaminan.id_principal = principal.id)';
        $jaminan .= ' INNER JOIN obligee ON jaminan.id_obligee = obligee.id)';
        $jaminan .= ' INNER JOIN currency ON jaminan.id_currency = currency.id)';
        // all table join
        $queries = $blanko . ' LEFT OUTER JOIN ' . $jaminan . ' ON blanko.id = blanko_used.id_blanko';
        return $queries;
    }

    public function data_list()
    {
        $this->query .= $this->query_where;
        parent::data_list();
    }
}
