<?php
function get_user_id($enkrip)
{
    $ci = &get_instance();
    $ci->load->database();
    $data = $ci->db->query('SELECT id FROM user WHERE enkripsi = ?', $enkrip)->row();
    return ($data) ? $data->id : '';
}

function get_user_office($enkrip)
{
    $ci = &get_instance();
    $ci->load->database();
    $selector = 'office.id AS id, office.nama AS nama, alamat, telpon, id_tipe, tipe';
    $query = 'SELECT ' . $selector . ' FROM (user INNER JOIN office ON user.id_office = office.id) INNER JOIN office_tipe ON office.id_tipe = office_tipe.id';
    $query .= ' WHERE user.enkripsi = ?';
    $data = $ci->db->query($query, $enkrip)->row();
    return $data;
}

function get_user_access($enkrip)
{
    $ci = &get_instance();
    $ci->load->database();
    $data = $ci->db->query('SELECT id_access FROM user WHERE enkripsi = ?', $enkrip)->row();
    return $data->id_access;
}
