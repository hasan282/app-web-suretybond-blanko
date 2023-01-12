<?php
function check_upload_file($form_name)
{
    $file_type = $_FILES[$form_name]['type'];
    $empty_file = empty($_FILES[$form_name]['name']);
    return (strpos($file_type, 'image') !== false && !$empty_file);
}

function create_directory($location, $prefix = 'b_')
{
    $folder_name = $prefix . date('ymd');
    $directory = './asset/img/' . $location . '/' . $folder_name;
    if (!is_dir($directory . '/')) mkdir($directory);
    return array('directory' => $directory, 'folder' => $folder_name);
}
