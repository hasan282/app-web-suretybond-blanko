<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Blanko_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function blanko_check($asuransi = '', $blanko = ['abcde'])
    {
        $query = 'SELECT id FROM blanko WHERE id_asuransi = ? AND nomor IN (' . implode(', ', preg_filter('/.+/', "'$0'", $blanko)) . ')';
        // return ($this->db->query($query, $asuransi)->num_rows() > 0) ? true : false;
        return $this->db->query($query, $asuransi)->result_array();
    }

    public function blanko_available($asuransi = '', $office = '')
    {
        $query = "SELECT COUNT(id_asuransi) AS total FROM blanko INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id WHERE asuransi.enkripsi = ? AND id_office = ? AND id_status = 1";
        $result = $this->db->query($query, array($asuransi, $office))->row();
        return $result->total;
    }

    public function blanko_send_allow($bind = [], $return_data = false)
    {
        $selector = ($return_data) ? 'id, nomor' : 'COUNT(id_asuransi) AS total';
        $query = "SELECT " . $selector . " FROM blanko WHERE id_asuransi = ? AND id_office = ? AND nomor BETWEEN ? AND ? AND id_status = 1";
        if ($return_data) {
            return $this->db->query($query, $bind)->result_array();
        } else {
            $result = $this->db->query($query, $bind)->row();
            return $result->total;
        }
    }

    public function update_office($office, $data = [''], $key = null, $field = 'id')
    {
        if ($key === null) {
            $where = $data;
        } else {
            $where = [];
            foreach ($data as $dt) array_push($where, $dt[$key]);
        }
        $query = 'UPDATE blanko SET id_office = ? WHERE ' . $field . ' IN (' . implode(', ', preg_filter('/.+/', "'$0'", $where)) . ')';
        $this->db->query($query, $office);
        return $this->db->affected_rows();
    }

    public function get_list($selector = [], $where = [], $limit = 0, $offset = 0, $descend = false)
    {
        $fields = array(
            'id' => 'blanko.enkripsi AS id',
            'asuransi' => 'nama AS asuransi',
            'prefix' => 'prefix',
            'nomor' => 'nomor',
            'tanggal' => 'date_in AS tanggal',
            'status' => 'status',
            'color' => 'color_class AS color'
        );
        $bind = false;
        $query_status = '';
        if (sizeof($selector) > 0) foreach (array_keys($fields) as $key) if (!in_array($key, $selector)) unset($fields[$key]);
        $query = 'SELECT ' . implode(', ', array_values($fields)) . ' FROM blanko INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id INNER JOIN blanko_status ON blanko.id_status = blanko_status.id';
        $query_where = array_merge($where, array('is_active' => 1));
        if (array_key_exists('nomor', $where)) $query_where['nomor'] = '%' . $where['nomor'] . '%';
        if (array_key_exists('id_status', $where)) {
            $status_list = (is_array($where['id_status'])) ? implode(', ', $where['id_status']) : $where['id_status'];
            $query_status = 'id_status IN (' . $status_list . ')';
            unset($query_where['id_status']);
        }
        if (sizeof($query_where) > 0) {
            $query .= ' WHERE ' . implode(' AND ', preg_filter('/.+/', '$0 = ?', array_keys($query_where)));
            $query = str_replace('nomor =', 'nomor LIKE', $query);
            $bind = array_values($query_where);
        }
        if (sizeof($query_where) > 0 && $query_status != '') $query .= ' AND ';
        $query .= $query_status;
        $count_data = $this->db->query($query, $bind)->num_rows();
        $query .= ' ORDER BY nama, date_in, nomor ';
        $query .= ($descend) ? 'DESC' : 'ASC';
        if ($limit > 0) $query .= ' LIMIT ' . $limit;
        if ($limit > 0 && $offset > 0) $query .= ' OFFSET ' . $offset;
        return array(
            'count' => $count_data,
            'list' => $this->db->query($query, $bind)->result_array()
        );
    }

    public function get_used_list($selector = [], $where = [], $limit = 0, $offset = 0)
    {
        $fields = array(
            'id' => 'blanko.enkripsi AS id',
            'asuransi' => 'asuransi.nama AS asuransi',
            'prefix' => 'prefix',
            'nomor' => 'nomor',
            'principal' => 'principal.nama AS principal',
            'jaminan' => 'jaminan'
        );
        $bind = false;
        if (sizeof($selector) > 0) foreach (array_keys($fields) as $key) if (!in_array($key, $selector)) unset($fields[$key]);
        $query = 'SELECT ' . implode(', ', array_values($fields)) . ' FROM ((blanko INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id) LEFT JOIN blanko_used ON blanko.id = blanko_used.id_blanko) LEFT JOIN principal ON blanko_used.id_principal = principal.id';
        $query_where = array_merge($where, array('is_active' => 1, 'id_status' => 2));
        if (array_key_exists('nomor', $where)) $query_where['nomor'] = '%' . $where['nomor'] . '%';
        if (sizeof($query_where) > 0) {
            $query .= ' WHERE ' . implode(' AND ', preg_filter('/.+/', '$0 = ?', array_keys($query_where)));
            $query = str_replace('nomor =', 'nomor LIKE', $query);
            $bind = array_values($query_where);
        }
        $count_data = $this->db->query($query, $bind)->num_rows();
        $query .= ' ORDER BY blanko_used.id DESC';
        if ($limit > 0) $query .= ' LIMIT ' . $limit;
        if ($limit > 0 && $offset > 0) $query .= ' OFFSET ' . $offset;
        return array(
            'count' => $count_data,
            'list' => $this->db->query($query, $bind)->result_array()
        );
    }

    public function get_one($enkrip, $selector = [])
    {
        $fields = array(
            'id' => 'blanko.id AS id',
            'id_asuransi' => 'asuransi.enkripsi AS id_asuransi',
            'asuransi' => 'asuransi.nama AS asuransi',
            'prefix' => 'prefix',
            'nomor' => 'nomor',
            'id_office' => 'id_office',
            'id_status' => 'id_status',
            'status' => 'blanko_status.status AS status',
            'color' => 'color_class AS color',
            'id_use' => 'blanko_used.id AS id_use',
            'id_jaminan' => 'id_jaminan',
            'produksi' => 'blanko.laprod AS produksi',
            'ket_used' => 'blanko_used.keterangan AS ket_used',
            'image_use' => 'blanko_used.image AS image_use',
            'id_crash' => 'blanko_crash.id AS id_crash',
            'ket_crash' => 'blanko_crash.keterangan AS ket_crash',
            'image_crash' => 'blanko_crash.image AS image_crash'
        );
        if (sizeof($selector) > 0) foreach (array_keys($fields) as $key) if (!in_array($key, $selector)) unset($fields[$key]);
        $query = 'SELECT ' . implode(', ', $fields) . ' FROM (((blanko INNER JOIN blanko_status ON blanko.id_status = blanko_status.id)';
        $query .= ' INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id) LEFT JOIN blanko_used ON blanko.id = blanko_used.id_blanko)';
        $query .= ' LEFT JOIN blanko_crash ON blanko.id = blanko_crash.id_blanko';
        $query .= ' WHERE blanko.enkripsi = ?';
        return (array) $this->db->query($query, $enkrip)->row();
    }

    public function used()
    {
    }
}
