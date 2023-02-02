<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Jasmine Indah Servistama">
    <meta name="language" content="English">
    <link rel="canonical" href="https://ptjis.com/">
    <link rel="icon" href="<?= base_url(); ?>asset/img/icon/icon128.png">
    <link rel="icon" href="<?= base_url(); ?>asset/img/icon/icon32.png" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="<?= base_url(); ?>asset/img/icon/icon256.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>JIS Surety Bond<?= (isset($title) && $title != '') ? ' · ' . $title : ''; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;1,400&family=Roboto:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
    <?= (isset($plugin)) ? $this->plugin->get($plugin, 'top') : ''; ?>
    <style>
        .bg-pattern {
            background-image: url("<?= base_url('asset/img/pattern/hexa_pattern_sm.jpg'); ?>");
            background-color: #F4F6F9;
        }
    </style>
</head>
<?php $page_class = array(
    'admin' => array('body' => ' sidebar-mini layout-fixed', 'div' => 'wrapper'),
    'login' => array('body' => ' login-page bg-pattern', 'div' => 'login-box'),
    'blank' => array('body' => ' bg-pattern', 'div' => 'wrapper'),
);
$page_type = (isset($type) && array_key_exists($type, $page_class)) ? $type : 'admin'; ?>

<body class="hold-transition<?= $page_class[$page_type]['body']; ?>">
    <div class="<?= $page_class[$page_type]['div']; ?>">