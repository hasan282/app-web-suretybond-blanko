<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Data_model extends SELF_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function blanko($select = array())
    {
        $fields = array(
            'id' => 'blanko.id AS id',
            'enkripsi' => 'blanko.enkripsi AS enkripsi',
            'asuransi' => 'asuransi.nama AS asuransi',
            'asuransi_id' => 'asuransi.enkripsi AS asuransi_id',
            'asuransi_nick' => 'asuransi.nickname AS asuransi_nick',
            'prefix' => 'blanko.prefix AS prefix',
            'nomor' => 'blanko.nomor AS nomor',
            'status' => 'blanko_status.status AS status',
            'color' => 'blanko_status.color_class AS color',
            'office_id' => 'office.id AS office_id',
            'office' => 'office.nama AS office',
            'office_nick' => 'office.nickname AS office_nick',
            'produksi' => 'blanko.laprod AS produksi',
            'crash' => 'blanko_crash.enkripsi AS crash',
            'used' => 'blanko_used.enkripsi AS used',
            'principal' => 'principal.nama AS principal',
            'obligee' => 'obligee.nama AS obligee',
            'jaminan' => 'jaminan.nomor AS jaminan',
            'nilai' => 'jaminan.nilai AS nilai'
        );
        $query = 'SELECT ' . $this->_fields($fields, $select) . ' ';
        $query .= 'FROM (((((((((blanko INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id) ';
        $query .= 'INNER JOIN office ON blanko.id_office = office.id) ';
        $query .= 'INNER JOIN blanko_status ON blanko.id_status = blanko_status.id) ';
        $query .= 'LEFT OUTER JOIN blanko_used ON blanko.id = blanko_used.id_blanko) ';
        $query .= 'LEFT OUTER JOIN blanko_crash ON blanko.id = blanko_crash.id_blanko) ';
        $query .= 'LEFT OUTER JOIN jaminan ON blanko_used.id_jaminan = jaminan.id) ';
        $query .= 'LEFT OUTER JOIN jaminan_tipe ON jaminan.id_tipe = jaminan_tipe.id) ';
        $query .= 'LEFT OUTER JOIN principal ON jaminan.id_principal = principal.id) ';
        $query .= 'LEFT OUTER JOIN obligee ON jaminan.id_obligee = obligee.id) ';
        $query .= 'LEFT OUTER JOIN currency ON jaminan.id_currency = currency.id';
        $this->query = $query;
        return $this;
    }
}
