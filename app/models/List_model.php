<?php
defined('BASEPATH') or exit('No direct script access allowed');
class List_model extends SELF_Model
{
    private $select, $from, $wheres, $order;

    public function __construct()
    {
        parent::__construct();
        $this->select = null;
        $this->from = null;
        $this->wheres = array();
        $this->order = null;
    }

    public function select(array $field = [])
    {
        $this->_selectors($field);
        $this->_query_assemble();
        return $this;
    }

    public function data_count(string $field = 'blanko.id')
    {
        $this->select = 'SELECT COUNT(' . $field . ') AS total FROM';
        $this->_query_assemble();
        $result = parent::data();
        return intval($result['total']);
    }

    public function where(array $where = [])
    {
        $binds = array();
        $conditions = array(
            'nomor' => ['blanko.nomor LIKE ?', '%value%'],
            'status' => ['blanko.id_status = ?', 'value'],
            'office' => ['blanko.id_office = ?', 'value'],
            'produksi' => ['blanko.laprod = ?', 'value'],
            'asuransi' => ['asuransi.enkripsi = ?', 'value'],
            'tipe' => ['jaminan_tipe.id = ?', 'value']
        );
        if (sizeof($where) > 0) {
            foreach ($conditions as $key => $con) {
                if (array_key_exists($key, $where)) {
                    array_push($binds, str_replace('value', $where[$key], $con[1]));
                } else {
                    unset($conditions[$key]);
                }
            }
            if (sizeof($conditions) > 0) {
                $where_string = implode(' AND ', (array_column(array_values($conditions), 0)));
                array_push($this->wheres, $where_string);
            }
        }
        if (sizeof($binds) > 0) $this->_binds_add($binds);
        $this->_query_assemble();
        return $this;
    }

    public function between(array $value = [], $field = 'blanko.nomor')
    {
        if (sizeof($value) === 2) {
            array_push($this->wheres, '(' . $field . ' BETWEEN ? AND ?)');
            $this->_binds_add($value);
        }
        $this->_query_assemble();
        return $this;
    }

    public function order(array $field = [])
    {
        $orders = array(
            'asuransi' => 'asuransi.nama ASC',
            'used' => 'blanko_used.id DESC',
            'nomor' => 'blanko.nomor ASC'
        );
        foreach (array_keys($orders) as $key) if (!in_array($key, $field)) unset($orders[$key]);
        if (!empty($orders)) $this->order = ' ORDER BY ' . implode(', ', array_values($orders));
        $this->_query_assemble();
        return $this;
    }

    private function _selectors($select)
    {
        $fields = array(
            // table blanko fields
            'id' => 'blanko.id AS id',
            'enkripsi' => 'blanko.enkripsi AS enkripsi',
            'prefix' => 'blanko.prefix AS prefix',
            'nomor' => 'blanko.nomor AS nomor',
            'produksi' => 'blanko.laprod AS produksi',
            'asuransi' => 'asuransi.nama AS asuransi',
            'asuransi_id' => 'asuransi.id AS asuransi_id',
            'asuransi_enkrip' => 'asuransi.enkripsi AS asuransi_enkrip',
            'asuransi_nick' => 'asuransi.nickname AS asuransi_nick',
            'status_id' => 'blanko.id_status AS status_id',
            'status' => 'blanko_status.status AS status',
            'color' => 'blanko_status.color_class AS color',
            'office' => 'office.nama AS office',
            'office_id' => 'office.id AS office_id',
            'office_nick' => 'office.nickname AS office_nick',
            // table jaminan fields
            'jaminan' => 'jaminan.nomor AS jaminan',
            'jaminan_enkrip' => 'jaminan.enkripsi AS jaminan_enkrip',
            'jaminan_tipe1' => 'jaminan_tipe.tipe AS jaminan_tipe1',
            'jaminan_tipe2' => 'jaminan_tipe.symbol AS jaminan_tipe2',
            'principal' => 'principal.nama AS principal',
            'obligee' => 'obligee.nama AS obligee',
            'currency1' => 'currency.nama AS currency1', // ex. Rupiah
            'currency2' => 'currency.symbol_1 AS currency2', // ex. IDR
            'currency3' => 'currency.symbol_2 AS currency3', // ex. Rp.
            'nilai' => 'jaminan.nilai AS nilai',
            'kontrak' => 'jaminan.kontrak AS kontrak',
            'pekerjaan' => 'jaminan.pekerjaan AS pekerjaan',
            'date_from' => 'jaminan.apply_date AS date_from',
            'date_to' => 'jaminan.end_date AS date_to',
            'days' => 'jaminan.apply_days AS days'
        );
        $queries = 'SELECT ' . $this->_fields($fields, $select) . ' FROM';
        $this->select = $queries;
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
        $this->from = $queries;
    }

    private function _binds_add(array $bind)
    {
        if (is_array($this->binds)) $this->binds = array_merge($this->binds, $bind);
        if (is_string($this->binds)) $this->binds = array_merge(array($this->binds), $bind);
        if ($this->binds === false) $this->binds = $bind;
    }

    private function _query_assemble()
    {
        if ($this->select === null) $this->_selectors(array());
        if ($this->from === null) $this->_tables();
        $this->query = $this->select . ' ' . $this->from;
        if (sizeof($this->wheres) > 0) $this->query .= ' WHERE ' . implode(' AND ', $this->wheres);
        if ($this->order !== null) $this->query .= $this->order;
    }
}
