<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Report_model extends SELF_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function used($select = array())
    {
        $fields = array(
            'id' => 'blanko.id AS id',
            'enkrip' => 'blanko.enkripsi AS enkrip',
            'enkrip_use' => 'blanko_used.enkripsi AS enkrip_use',
            'prefix' => 'blanko.prefix AS prefix',
            'nomor' => 'blanko.nomor AS nomor',
            'jaminan' => 'jaminan.nomor AS jaminan',
            'jaminan_tipe_1' => 'jaminan_tipe.tipe AS jaminan_tipe_1',
            'jaminan_tipe_2' => 'jaminan_tipe.symbol AS jaminan_tipe_2',
            'principal' => 'principal.nama AS principal',
            'obligee' => 'obligee.nama AS obligee',
            'kontrak' => 'jaminan.kontrak AS kontrak',
            'pekerjaan' => 'jaminan.pekerjaan AS pekerjaan',
            'currency_1' => 'currency.nama AS currency_1',
            'currency_2' => 'currency.symbol_1 AS currency_2',
            'jaminan_nilai' => 'jaminan.nilai AS jaminan_nilai',
            'date_from' => 'jaminan.apply_date AS date_from',
            'date_to' => 'jaminan.end_date AS date_to',
            'days' => 'jaminan.apply_days AS days',
            'office' => 'office.nama AS office',
            'office_nick' => 'office.nickname AS office_nick',
            'produksi' => 'blanko_used.produksi AS produksi',
            'rev_status' => 'blanko_status.status AS rev_status',
            'color' => 'blanko_status.color_class AS color',
            'rev_prefix' => 'blanko_rev.prefix AS rev_prefix',
            'rev_nomor' => 'blanko_rev.nomor AS rev_nomor'
        );
        $queries = 'SELECT ' . $this->_fields($fields, $select) . ' FROM ((((((((((blanko_used ';
        $queries .= 'INNER JOIN jaminan ON blanko_used.id_jaminan = jaminan.id) INNER JOIN blanko ON blanko_used.id_blanko = blanko.id) ';
        $queries .= 'INNER JOIN blanko_status ON blanko.id_status = blanko_status.id) INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id) ';
        $queries .= 'INNER JOIN office ON blanko.id_office = office.id) ';
        $queries .= 'INNER JOIN principal ON jaminan.id_principal = principal.id) INNER JOIN obligee ON jaminan.id_obligee = obligee.id) ';
        $queries .= 'INNER JOIN jaminan_tipe ON jaminan.id_tipe = jaminan_tipe.id) INNER JOIN currency ON currency.id = jaminan.id_currency) ';
        $queries .= 'LEFT OUTER JOIN revisi ON blanko.id = revisi.id_from) LEFT OUTER JOIN blanko AS blanko_rev ON revisi.id_to = blanko_rev.id';
        $this->query = $queries;
        return $this;
    }

    public function where(array $where)
    {
        $binds = array();
        $conditions = array(
            'id' => ['blanko.enkripsi = ?', '_val_'],
            'nomor' => ['blanko.nomor LIKE ?', '%_val_%'],
            'office' => ['blanko.id_office = ?', '_val_'],
            'produksi' => ['blanko_used.produksi = ?', '_val_'],
            'asuransi' => ['asuransi.enkripsi = ?', '_val_'],
            'tipe' => ['jaminan_tipe.id = ?', '_val_']
        );
        if (sizeof($where) > 0) {
            foreach ($conditions as $key => $con) {
                if (array_key_exists($key, $where)) {
                    array_push($binds, str_replace('_val_', $where[$key], $con[1]));
                } else {
                    unset($conditions[$key]);
                }
            }
            if (sizeof($conditions) > 0) {
                $this->query .= ' WHERE ' . implode(' AND ', (array_column(array_values($conditions), 0)));
            }
        }
        if (sizeof($binds) > 0) $this->binds = $binds;
        return $this;
    }

    public function wheres(string $where)
    {
        $this->query .= ' WHERE ' . $where;
        return $this;
    }

    public function order(array $field)
    {
        $order = array(
            'asuransi' => 'asuransi.nama ASC',
            'used' => 'blanko_used.id DESC',
            'nomor' => 'blanko.nomor ASC'
        );
        foreach (array_keys($order) as $key) if (!in_array($key, $field)) unset($order[$key]);
        if (!empty($order)) $this->query .= ' ORDER BY ' . implode(', ', array_values($order));
        return $this;
    }
}
