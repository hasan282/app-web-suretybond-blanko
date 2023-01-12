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
            'tipe' => 'tipe',
            'tipe2' => 'symbol AS tipe2',
            'nomor' => 'nomor',
            'principal' => 'principal.nama AS principal',
            'obligee' => 'obligee.nama AS obligee',
            'currency' => 'symbol_2 AS currency',
            'nilai' => 'nilai',
            'kontrak' => 'kontrak',
            'pekerjaan' => 'pekerjaan',
            'date' => 'apply_date AS date',
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

    public function get_all()
    {
        $queries = $this->query;
        return $this->db->query($queries)->result_array();
    }
}
