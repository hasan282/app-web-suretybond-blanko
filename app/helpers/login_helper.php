<?php
function is_login()
{
    $ci = &get_instance();
    $id = $ci->session->has_userdata('id');
    $user = $ci->session->has_userdata('user');
    $nama = $ci->session->has_userdata('nama');
    $foto = $ci->session->has_userdata('foto');
    $role = $ci->session->has_userdata('role');
    return ($id && $user && $nama && $role && $foto);
}

function true_access($uri = null)
{
    if (is_login()) {
        $url = (is_null($uri)) ? uri_string() : $uri;
        $ci = &get_instance();
        $id = $ci->session->userdata('id');
        $ci->load->model('menu_model');
        return $ci->menu_model->menu_access($id, $url);
    } else {
        redirect(login_url());
        return false;
    }
}

function special_access($role_id)
{
    if (is_login()) {
        $ci = &get_instance();
        $ci->load->database();
        $role = (is_array($role_id)) ? implode(', ', $role_id) : $role_id;
        $query = 'SELECT * FROM user WHERE enkripsi = ? AND id_access IN (' . $role . ')';
        $count = $ci->db->query($query, $ci->session->userdata('id'))->num_rows();
        return ($count > 0) ? true : false;
    } else {
        redirect(login_url());
        return false;
    }
}

function get_user_info($id)
{
    $ci = &get_instance();
    $ci->load->database();
    $data = $ci->db->get_where('user', ['enkripsi' => $id])->row();
    return array(
        'nama' => $data->nama,
        'user' => $data->username,
        'foto' => $data->photo
    );
}

function login_url()
{
    $query = ($_SERVER['QUERY_STRING'] == '') ? '' : '?' . $_SERVER['QUERY_STRING'];
    return '?log=' . urlencode(uri_string() . $query);
}
