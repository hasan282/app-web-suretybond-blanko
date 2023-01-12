<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SELF_Model extends CI_Model
{
    /**
     * Query String
     * @var string
     */
    protected $query;
    /**
     * Query Parameter to Bind
     * @var boolean or Array
     */
    protected $binds;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->query = '';
        $this->binds = false;
    }

    /**
     * Setup Query Limit dan Offset
     * @return object return this
     */
    public function limit($limit = 10, $offset = 0)
    {
        if ($limit > 0) $this->query .= ' LIMIT ' . $limit;
        if ($limit > 0 && $offset > 0) $this->query .= ' OFFSET ' . $offset;
        return $this;
    }

    /**
     * Return Data Hasil Query dalam bentuk Array atau Associate Array
     * @return array array empty jika tidak ada data
     */
    public function data()
    {
        if ($this->query == '') {
            return array();
        } else {
            $result = $this->db->query($this->query, $this->binds);
            if ($result->num_rows() === 1) {
                return (array) $result->row();
            } else {
                return $result->result_array();
            }
        }
    }

    /**
     * Return Data Hasil Query dalam bentuk List Array
     * @return array array empty jika tidak ada data
     */
    public function data_list()
    {
        if ($this->query == '') {
            return array();
        } else {
            return $this->db->query($this->query, $this->binds)->result_array();
        }
    }

    /**
     * Jumlah Seluruh Row pada Query
     * @return int total seluruh row
     */
    public function count()
    {
        if ($this->query == '') {
            return 0;
        } else {
            return $this->db->query($this->query, $this->binds)->num_rows();
        }
    }

    /**
     * Return Build Query String
     * @return array query dan bind value
     */
    public function query_string()
    {
        return array(
            'query' => $this->query,
            'bind' => $this->binds
        );
    }

    /**
     * Seleksi Pilihan Field pada Query
     * @param array $fields semua pilihan field
     * @param array $selected field yang dipilih, default array kosong
     * @return string field terpilih,
     * return semua field jika tidak ada yang dipilih
     */
    protected function _fields(array $fields, $selected = array())
    {
        $all_fields = $fields;
        if (sizeof($selected) > 0) {
            foreach (array_keys($all_fields) as $key) {
                if (!in_array($key, $selected)) unset($all_fields[$key]);
            }
        }
        return implode(', ', array_values($all_fields));
    }
}
