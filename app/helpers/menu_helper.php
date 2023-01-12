<?php 
function get_menu($id){
    $ci=& get_instance();
    $ci->load->model('menu_model');
    return $ci->menu_model->menu_sidebar($id);
}
