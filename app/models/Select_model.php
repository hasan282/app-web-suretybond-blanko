<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Select_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_asuransi($username)
    {
        $query = "SELECT asuransi.enkripsi AS id, asuransi.nama AS asuransi FROM blanko INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id WHERE id_office = '221002065931' AND id_status = 1 GROUP BY id_asuransi ORDER BY asuransi.nama ASC";
        return $this->db->query($query, $username)->result_array();
    }

    public function get_record_add($id)
    {
        $fields = 'asuransi.nama AS asuransi, tanggal, number_from, number_to, numbers, user.nama AS user';
        $query = 'SELECT ' . $fields . ' FROM (record_add INNER JOIN asuransi ON record_add.id_asuransi = asuransi.id) INNER JOIN user ON record_add.id_user = user.id';
        $query .= ' WHERE record_add.enkripsi = ?';
        return (array) $this->db->query($query, $id)->row();
    }

    public function get_record_sent($id)
    {
        $fields = 'asuransi.nama AS asuransi, tanggal, number_from, number_to, ofrom.nama AS office_from, ofrom.alamat AS alamat_from, oto.nama AS office_to, oto.alamat AS alamat_to';
        $query = 'SELECT ' . $fields . ' FROM ((record_sent INNER JOIN asuransi ON record_sent.id_asuransi = asuransi.id) ';
        $query .= 'INNER JOIN office AS ofrom ON record_sent.office_from = ofrom.id) INNER JOIN office AS oto ON record_sent.office_to = oto.id WHERE record_sent.enkripsi = ?';
        return (array) $this->db->query($query, $id)->row();
    }
}
