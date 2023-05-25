<?php

$my_config = array(

    'hosting' => false,

    'url_local' => 'http://localhost:8080/kerjaan2/web-blanko/',
    'url_hosting' => 'https://surety.ptjis.com/',

    'db_host' => 'localhost',
    'db_name' => 'ptjis_blanko',
    'db_user' => 'root',
    'db_pass' => ''

);

$my_config['url_now'] = ($my_config['hosting']) ? $my_config['url_hosting'] : $my_config['url_local'];
if (function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Jakarta');
