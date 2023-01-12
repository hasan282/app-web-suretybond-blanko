<?php
defined('BASEPATH') or exit('No direct script access allowed');
class History_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_list($user_id, $limit = 10)
    {
        $query = "(SELECT record_add.id AS id, record_add.enkripsi AS enkrip, (SELECT 'add') AS tipe, asuransi.nama AS asuransi, COALESCE(numbers, CONCAT(number_from, ' - ', number_to)) AS nomor, (SELECT NULL) AS office FROM record_add INNER JOIN asuransi ON record_add.id_asuransi = asuransi.id WHERE id_user = '" . $user_id . "') ";
        $query .= "UNION ALL (SELECT record_sent.id AS id, record_sent.enkripsi AS enkrip, (SELECT 'send') AS tipe, asuransi.nama AS asuransi, CONCAT(number_from, ' - ', number_to) AS nomor, office.nama AS office FROM (record_sent INNER JOIN asuransi ON record_sent.id_asuransi = asuransi.id) INNER JOIN office ON record_sent.office_to = office.id WHERE id_user = '" . $user_id . "' AND (number_to - number_from > 0)) ";
        $query .= "UNION ALL (SELECT blanko_used.id AS id, blanko.enkripsi AS enkrip, (SELECT 'use') AS tipe, asuransi.nama AS asuransi, blanko.nomor AS nomor, (SELECT NULL) AS office FROM (blanko_used INNER JOIN blanko ON blanko_used.id_blanko = blanko.id) INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id WHERE id_user = '" . $user_id . "') ";
        $query .= "UNION ALL (SELECT blanko_crash.id AS id, blanko.enkripsi AS enkrip, (SELECT 'crash') AS tipe, asuransi.nama AS asuransi, blanko.nomor AS nomor, (SELECT NULL) AS office FROM (blanko_crash INNER JOIN blanko ON blanko_crash.id_blanko = blanko.id) INNER JOIN asuransi ON blanko.id_asuransi = asuransi.id WHERE id_user = '" . $user_id . "') ";
        $query .= "ORDER BY id DESC LIMIT " . $limit;
        return $this->db->query($query)->result_array();
    }
}
