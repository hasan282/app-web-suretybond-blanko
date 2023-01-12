<?php
function get_real_id($table, $enkrip)
{
    $ci = &get_instance();
    $ci->load->database();
    $data = $ci->db->query('SELECT id FROM ' . $table . ' WHERE enkripsi = ?', $enkrip)->row();
    return ($data) ? $data->id : '';
}

function id_to_date($id)
{
    $month_list = explode(',', 'Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember');
    $year = '20' . substr($id, 0, 2);
    $month = $month_list[intval(substr($id, 2, 2)) - 1];
    $day = substr($id, 4, 2);
    return $day . ' ' . $month . ' ' . $year;
}

function id_to_time($id, $time_format = 'H:i:s')
{
    $hour = substr($id, 6, 2);
    $minute = substr($id, 8, 2);
    $second = substr($id, 10, 2);
    $str_format = array('H', 'i', 's');
    $str_time = array($hour, $minute, $second);
    return str_replace($str_format, $str_time, $time_format);
}

function id_to_datetime($id)
{
}
