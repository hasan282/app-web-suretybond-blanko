<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Menu_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function menu_sidebar($id)
    {
        $query = "SELECT menu, icon, link FROM (menu_access INNER JOIN menu ON menu_access.id_menu = menu.id) INNER JOIN user ON menu_access.id_access = user.id_access WHERE enkripsi = ? ORDER BY id_menu ASC";
        return $this->db->query($query, $id)->result_array();
    }

    public function menu_access($id, $uri)
    {
        $query = "SELECT link FROM user INNER JOIN (menu_access INNER JOIN menu ON menu.id = menu_access.id_menu) ON user.id_access = menu_access.id_access WHERE enkripsi = ? AND link = ?";
        return ($this->db->query($query, [$id, $uri])->num_rows() > 0) ? true : false;
    }
}
