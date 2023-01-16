<?php
function custom_404_admin()
{
    $ci = &get_instance();
    $data['title'] = '404 Not Found';
    $data['plugin'] = 'basic|fontawesome|scrollbar';
    $ci->load->view('template/head', $data);
    $ci->load->view('template/navbar');
    $ci->load->view('template/sidebar');
    $ci->load->view('errors/admin_body_404');
    $ci->load->view('template/footer');
    $ci->load->view('template/foot');
}

if (!function_exists('under_construct')) {
    function under_construct()
    {
        $ci = &get_instance();
        $data['title'] = 'Under Construction';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $ci->load->view('template/head', $data);
        $ci->load->view('template/navbar');
        $ci->load->view('template/sidebar');
        $ci->load->view('errors/construct');
        $ci->load->view('template/footer');
        $ci->load->view('template/foot');
    }
}
