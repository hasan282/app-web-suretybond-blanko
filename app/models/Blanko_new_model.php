<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Blanko_new_model extends SELF_Model
{
    private $select, $where, $bindval, $order;

    public function __construct()
    {
        parent::__construct();
        $this->select = null;
        $this->where = array();
        $this->bindval = array();
    }

    public function refresh()
    {
        $this->select = null;
        $this->where = array();
        $this->bindval = array();
        return $this;
    }

    public function getdata(array $select = [])
    {
        $fields = array(
            'id' => 'blanko.id AS id',
            'enkrip' => 'blanko.enkripsi AS enkrip',
            'asuransi_id' => 'blanko.id_asuransi AS asuransi_id',
            'prefix' => 'blanko.prefix AS prefix',
            'nomor' => 'blanko.nomor AS nomor',
            'office_id' => 'blanko.id_office AS office_id',
            'status_id' => 'blanko.id_status AS status_id',
            'date' => 'blanko.date_in AS date'
        );
        $table = 'blanko';
        $this->select = 'SELECT ' . $this->_fields($fields, $select) . ' FROM ' . $table;
        $this->_create_query();
        return $this;
    }

    public function where($where)
    {
        $fields = array(
            'id' => 'blanko.id',
            'enkrip' => 'blanko.enkripsi',
            'status' => 'blanko.id_status',
            'asuransi' => 'blanko.id_asuransi',
            'office' => 'blanko.id_office'
        );
        $this->_where($where, $fields);
        return $this;
    }

    private function _create_query()
    {
        if ($this->select !== null) {
            $query = $this->select;
            if (!empty($this->where)) {
                $query = $query . ' WHERE ' . implode(' AND ', $this->where);
                if (!empty($this->bindval)) $this->binds = $this->bindval;
            }
            $this->query = $query;
        }
    }

    private function _where($where, $fields)
    {
        $whereQuery = array();
        if (is_string($where)) array_push($whereQuery, $where);
        if (is_array($where)) {
            foreach ($where as $key => $val) {
                if (array_key_exists($key, $fields)) {
                    $query = $fields[$key];
                    if (is_integer($val)) $query .= ' = ?';
                    if (is_string($val)) {
                        if (substr($val, 0, 1) == '%' || substr($val, -1) == '%') {
                            $query .= ' LIKE ?';
                        } else {
                            $query .= ' = ?';
                        }
                    }
                    if (is_array($val)) $query .= ' IN ?';
                    if (substr($query, -1) == '?') {
                        array_push($whereQuery, $query);
                        array_push($this->bindval, $val);
                    }
                }
            }
        }
        if (!empty($whereQuery)) {
            if ($this->where === null) {
                $this->where = $whereQuery;
            } else {
                $this->where = array_merge($this->where, $whereQuery);
            }
        }
        $this->_create_query();
    }

    public function all($select = [], $where = [])
    {
        $fields = array(
            'id' => 'blanko.id AS id',
            'enkrip' => 'blanko.enkripsi AS enkrip',
            'prefix' => 'blanko.prefix AS prefix',
            'nomor' => 'blanko.nomor AS nomor',
            'asuransi_id' => 'asuransi.id AS asuransi_id',
            'asuransi_enkrip' => 'asuransi.enkripsi AS asuransi_enkrip',
            'asuransi' => 'asuransi.nama AS asuransi',
            'asuransi_nick' => 'asuransi.nickname AS asuransi_nick',
            'status_id' => 'blanko.id_status AS status_id',
            'status' => 'blanko_status.status AS status',
            'color' => 'blanko_status.color_class AS color'
        );
        $queries = 'SELECT ' . $this->_fields($fields, $select) . ' FROM (blanko ';
        $queries .= 'INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id) ';
        $queries .= 'INNER JOIN blanko_status ON blanko.id_status = blanko_status.id ';
        $queries .= 'WHERE asuransi.is_active = 1';
        $this->query = $queries;
        return $this;
    }

    public function used($select = [], $where = [])
    {
        $bind = array();
        $fields = array(
            'id' => 'blanko.id AS id',
            'enkrip' => 'blanko.enkripsi AS enkrip',
            'prefix' => 'blanko.prefix AS prefix',
            'nomor' => 'blanko.nomor AS nomor',
            'asuransi_id' => 'asuransi.enkripsi AS asuransi_id',
            'asuransi' => 'asuransi.nama AS asuransi',
            'office_id' => 'blanko.id_office AS office_id',
            'tipe' => 'jaminan_tipe.tipe AS tipe',
            'tipe_symbol' => 'jaminan_tipe.symbol AS tipe_symbol',
            'jaminan' => 'jaminan.nomor AS jaminan',
            'principal_id' => 'principal.id AS principal_id',
            'principal' => 'principal.nama AS principal',
            'obligee_id' => 'obligee.id AS obligee_id',
            'obligee' => 'obligee.nama AS obligee',
            'currency' => 'currency.nama AS currency',
            'symbol1' => 'currency.symbol_1 AS symbol1',
            'symbol2' => 'currency.symbol_2 AS symbol2',
            'nilai' => 'nilai',
            'kontrak' => 'kontrak',
            'pekerjaan' => 'pekerjaan',
            'date_from' => 'apply_date AS date_from',
            'date_to' => 'end_date AS date_to',
            'days' => 'apply_days AS days',
            'image' => 'image'
        );
        $queries = 'SELECT ' . $this->_fields($fields, $select) . ' FROM ((((((blanko_used ';
        $queries .= 'INNER JOIN blanko ON blanko_used.id_blanko = blanko.id) INNER JOIN jaminan ON blanko_used.id_jaminan = jaminan.id) ';
        $queries .= 'INNER JOIN principal ON principal.id = jaminan.id_principal) INNER JOIN obligee ON obligee.id = jaminan.id_obligee) ';
        $queries .= 'INNER JOIN jaminan_tipe ON jaminan.id_tipe = jaminan_tipe.id) INNER JOIN currency ON jaminan.id_currency = currency.id) ';
        $queries .= 'INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id ';
        $queries .= 'WHERE blanko.id_status = 2';
        $conditions = array(
            'id' => ['blanko.enkripsi = ?', '?val?'],
            'jaminan' => ['jaminan.enkripsi = ?', '?val?'],
            'office' => ['blanko.id_office = ?', '?val?'],
            'asuransi' => ['asuransi.enkripsi = ?', '?val?'],
            'nomor' => ['blanko.nomor LIKE ?', '%?val?%'],
            'principal' => ['jaminan.id_principal = ?', '?val?']
        );
        if (sizeof($where) > 0) {
            foreach ($where as $key => $val) {
                if (array_key_exists($key, $conditions)) {
                    $queries .= ' AND ' . $conditions[$key][0];
                    array_push($bind, str_replace('?val?', $val, $conditions[$key][1]));
                }
            }
            if (!empty($bind)) $this->binds = $bind;
        }
        $this->query = $queries;
        return $this;
    }

    public function order(array $field, $role = 'ASC')
    {
        $fields = array(
            'asuransi' => 'asuransi.nama',
            'nomor' => 'blanko.nomor'
        );
        foreach (array_keys($fields) as $key) if (!in_array($key, $field)) unset($fields[$key]);
        if (!empty($fields)) $this->query .= ' ORDER BY ' . implode(', ', array_values($fields)) . ' ' . $role;
        return $this;
    }
}
