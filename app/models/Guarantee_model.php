<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Guarantee_model extends CI_Model
{
    private $query;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select($selector = [])
    {
        $fields = array(
            'id' => 'enkripsi AS id',
            'real_id' => 'jaminan.id AS real_id',
            'office' => 'principal.id_office AS office',
            'tipe' => 'tipe',
            'tipe_id' => 'jaminan.id_tipe AS tipe_id',
            'tipe2' => 'symbol AS tipe2',
            'nomor' => 'nomor',
            'principal_id' => 'principal.id AS principal_id',
            'principal' => 'principal.nama AS principal',
            'obligee_id' => 'obligee.id AS obligee_id',
            'obligee' => 'obligee.nama AS obligee',
            'currency_id' => 'jaminan.id_currency AS currency_id',
            'currency' => 'symbol_2 AS currency',
            'nilai' => 'nilai',
            'kontrak' => 'kontrak',
            'pekerjaan' => 'pekerjaan',
            'date' => 'apply_date AS date',
            'date_to' => 'end_date AS date_to',
            'day' => 'apply_days AS day'
        );
        if (sizeof($selector) > 0) foreach (array_keys($fields) as $key) if (!in_array($key, $selector)) unset($fields[$key]);
        $queries = 'SELECT ' . implode(', ', array_values($fields)) . ' FROM (((jaminan INNER JOIN jaminan_tipe ON jaminan.id_tipe = jaminan_tipe.id) ';
        $queries .= 'INNER JOIN currency ON jaminan.id_currency = currency.id) INNER JOIN principal ON principal.id = jaminan.id_principal) ';
        $queries .= 'INNER JOIN obligee ON jaminan.id_obligee = obligee.id';
        $this->query = $queries;
        return $this;
    }

    public function where_id($id)
    {
        $this->query .= ' WHERE jaminan.id = ?';
        return (array) $this->db->query($this->query, $id)->row();
    }

    public function where_enkrip($enkrip)
    {
        $this->query .= ' WHERE jaminan.enkripsi = ?';
        return (array) $this->db->query($this->query, $enkrip)->row();
    }

    public function get_all()
    {
        $queries = $this->query;
        return $this->db->query($queries)->result_array();
    }
}
