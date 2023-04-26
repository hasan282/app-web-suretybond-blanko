<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_user($username)
    {
        $query = "SELECT enkripsi, username, password, nama, photo, access, is_active FROM user INNER JOIN access ON user.id_access = access.id WHERE username = ?";
        return $this->db->query($query, $username)->row();
    }

    public function get_list($selector = [])
    {
        $fields = array(
            'id' => 'user.enkripsi AS id',
            'username' => 'username',
            'password' => 'password',
            'nama' => 'user.nama AS nama',
            'office' => 'office.nama AS office',
            'role' => 'access.access AS role',
            'active' => 'user.is_active AS active'
        );
        if (sizeof($selector) > 0) foreach (array_keys($fields) as $key) if (!in_array($key, $selector)) unset($fields[$key]);
        $query = 'SELECT ' . implode(', ', $fields) . ' FROM (user INNER JOIN office ON user.id_office = office.id) INNER JOIN access ON user.id_access = access.id ';
        $query .= 'ORDER BY user.is_active DESC, user.id_access, office.nama ASC';
        return $this->db->query($query)->result_array();
    }

    public function api_user(?string $username, ?string $password)
    {
        $binds = array($username, $password);
        $select = 'user.enkripsi AS id, user.username AS user, user.password AS pass, user.nama AS name, user.photo AS foto, office.id AS office_id, office.nama AS office, access.id AS role_id, access.access AS role';
        $query = 'SELECT ' . $select . ' FROM (user INNER JOIN office ON user.id_office = office.id) INNER JOIN access ON user.id_access = access.id';
        $where = ' WHERE user.is_active = 1 AND user.username = ? AND user.password = ?';
        return $this->db->query($query . $where, $binds)->row();
    }
}
