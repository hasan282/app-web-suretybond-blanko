<?php

function api_header($method = ['GET', 'POST', 'DELETE', 'PUT'], $age = false)
{
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Methods: ' . implode(', ', $method));
    if ($age !== false) {
        header('Access-Control-Max-Age: ' . $age);
    }
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
}

function bad_request($code = 400)
{
    http_response_code($code);
    print json_encode(array('status' => false));
}

function print_status($status)
{
    print json_encode(array('status' => $status));
}

function print_data($data)
{
    print json_encode(array('status' => true, 'data' => $data));
}

function method_is($method)
{
    $request = $_SERVER['REQUEST_METHOD'];
    return ($request == $method);
}
