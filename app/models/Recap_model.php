<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Recap_model extends CI_Model
{
    private $query, $binds;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->binds = false;
    }

    public function recap_date($asuransi, $office, $head_office = false)
    {
        $this->binds = array($asuransi, $office);
        $fields = array(
            'tanggal' => 'date_sent AS tanggal',
            'mins' => 'MIN(nomor) AS mins',
            'maxs' => 'MAX(nomor) AS maxs',
            'available' => 'SUM(CASE WHEN id_status = 1 THEN 1 ELSE 0 END) AS available',
            'used' => 'SUM(CASE WHEN id_status = 2 THEN 1 ELSE 0 END) AS used',
            'crash' => 'SUM(CASE WHEN id_status = 3 OR id_status = 4 THEN 1 ELSE 0 END) AS crash'
        );
        $core_table = 'SELECT blanko.id AS id, enkripsi, prefix, nomor, id_status, blanko_sent.date AS date_sent FROM blanko INNER JOIN blanko_sent ON blanko.id = blanko_sent.id_blanko AND blanko.id_office = blanko_sent.office_to WHERE id_asuransi = ? AND id_office = ? ORDER BY nomor ASC';
        if ($head_office) $core_table = 'SELECT id, enkripsi, prefix, nomor, id_status, date_in AS date_sent FROM blanko WHERE id_asuransi = ? AND id_office = ? ORDER BY nomor ASC';
        $end_field = ', (CASE WHEN (@prev - @prev := nomor) = -1 THEN @group ELSE @group := @group + 1 END) AS grps ';
        $queries = 'SELECT ' . implode(', ', array_values($fields)) . $end_field . ' FROM ';
        $queries .= '(SELECT @prev := 0) AS vars, (SELECT @group := 1) AS groups, (' . $core_table . ') AS blankos GROUP BY grps, date_sent ORDER BY date_sent DESC';
        $this->query = $queries;
        return $this;
    }

    public function recap_office($asuransi = null)
    {
        $fields = array(
            'id' => 'id_office AS id',
            'office' => 'office.nama AS office',
            'available' => 'SUM(CASE WHEN id_status = 1 THEN 1 ELSE 0 END) AS available',
            'used' => 'SUM(CASE WHEN id_status = 2 THEN 1 ELSE 0 END) AS used',
            'crash' => 'SUM(CASE WHEN id_status = 3 OR id_status = 4 THEN 1 ELSE 0 END) AS crash'
        );
        $queries = 'SELECT ' . implode(', ', array_values($fields)) . ' FROM ';
        $queries .= 'blanko INNER JOIN office ON blanko.id_office = office.id ';
        if ($asuransi !== null) {
            $this->binds = $asuransi;
            $queries .= 'WHERE blanko.id_asuransi = ? ';
        }
        $queries .= 'GROUP BY id_office ORDER BY id_tipe, office.nama';
        $this->query = $queries;
        return $this;
    }

    public function list($asuransi, $office, $date, $head_office = false, $selector = [])
    {
        $this->binds = array($asuransi, $office, $date);
        $fields = array(
            'id' => 'blankos.id AS id',
            'prefix' => 'prefix',
            'nomor' => 'blankos.nomor AS nomor',
            'status' => 'blanko_status.status AS status',
            'color' => 'color_class AS color',
            'tipe' => 'tipe',
            'symbol' => 'symbol',
            'jaminan' => 'jaminan.nomor AS jaminan',
            'principal' => 'principal.nama AS principal',
            'obligee' => 'obligee.nama AS obligee',
            'nilai' => 'nilai',
            'produksi' => 'blankos.laprod AS produksi',
            'tanggal' => 'apply_date AS tanggal',
            'tanggal_to' => 'end_date AS tanggal_to',
            'days' => 'apply_days AS days'
        );
        if (sizeof($selector) > 0) foreach (array_keys($fields) as $key) if (!in_array($key, $selector)) unset($fields[$key]);
        $core_table = 'SELECT enkripsi AS id, blanko.id AS real_id, id_asuransi, id_office, prefix, nomor, laprod, id_status, blanko_sent.date AS tanggal, keterangan FROM blanko INNER JOIN blanko_sent ON blanko.id = blanko_sent.id_blanko AND blanko.id_office = blanko_sent.office_to';
        if ($head_office) $core_table = 'SELECT enkripsi AS id, id AS real_id, id_asuransi, id_office, prefix, nomor, laprod, id_status, date_in AS tanggal, keterangan FROM blanko';
        $queries = 'SELECT ' . implode(', ', array_values($fields)) . ' FROM ((((((' . $core_table . ') AS blankos LEFT JOIN blanko_used ON blankos.real_id = blanko_used.id_blanko) ';
        $queries .= 'LEFT JOIN jaminan ON blanko_used.id_jaminan = jaminan.id) LEFT JOIN jaminan_tipe ON jaminan.id_tipe = jaminan_tipe.id) ';
        $queries .= 'INNER JOIN blanko_status ON blankos.id_status = blanko_status.id) LEFT JOIN principal ON jaminan.id_principal = principal.id) LEFT JOIN obligee ON jaminan.id_obligee = obligee.id ';
        $queries .= 'WHERE id_asuransi = ? AND blankos.id_office = ? AND blankos.tanggal = ? ORDER BY blankos.nomor ASC';
        $this->query = $queries;
        return $this;
    }

    public function list_between($asuransi, $from, $to = null)
    {
        $binds = array($asuransi, $from);
        array_push($binds, ($to === null) ? $from : $to);
        $fields = 'blanko.enkripsi AS id, blanko.prefix AS prefix, blanko.nomor AS nomor, blanko_status.status AS status, blanko_status.color_class AS color, jaminan.nomor AS jaminan, principal.nama AS principal, office.nickname AS office, blanko.laprod AS produksi';
        $queries = 'SELECT ' . $fields . ' FROM ((((blanko INNER JOIN blanko_status ON blanko.id_status = blanko_status.id) INNER JOIN office ON blanko.id_office = office.id) LEFT OUTER JOIN blanko_used ON blanko.id = blanko_used.id_blanko) LEFT OUTER JOIN jaminan ON blanko_used.id_jaminan = jaminan.id) LEFT OUTER JOIN principal ON jaminan.id_principal = principal.id';
        $queries .= ' WHERE blanko.id_asuransi = ? AND blanko.nomor BETWEEN ? AND ? ORDER BY blanko.nomor ASC';
        $this->binds = $binds;
        $this->query = $queries;
        return $this;
    }

    public function recap_asuransi($office = null)
    {
        $fields = array(
            'id' => 'asuransi.enkripsi AS id',
            'asuransi' => 'asuransi.nama AS asuransi',
            'available' => 'SUM(CASE WHEN id_status = 1 THEN 1 ELSE 0 END) AS available',
            'used' => 'SUM(CASE WHEN id_status = 2 THEN 1 ELSE 0 END) AS used',
            'crash' => 'SUM(CASE WHEN id_status = 3 OR id_status = 4 THEN 1 ELSE 0 END) AS crash'
        );
        $queries = 'SELECT ' . implode(', ', array_values($fields)) . ' FROM blanko INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id ';
        if ($office !== null) {
            $this->binds = $office;
            $queries .= 'WHERE id_office = ? ';
        }
        $queries .= 'GROUP BY asuransi.id ORDER BY asuransi.nama ASC';
        $this->query = $queries;
        return $this;
    }

    public function get_data()
    {
        return $this->db->query($this->query, $this->binds)->result_array();
    }

    public function get_query()
    {
        return $this->query;
    }

    public function get_limit($limit = 0, $offset = 0)
    {
        $countdata = $this->db->query($this->query, $this->binds)->num_rows();
        if ($limit > 0) $this->query .= ' LIMIT ' . $limit;
        if ($limit > 0 && $offset > 0) $this->query .= ' OFFSET ' . $offset;
        return array(
            'count' => $countdata,
            'data' => $this->db->query($this->query, $this->binds)->result_array()
        );
    }
}
