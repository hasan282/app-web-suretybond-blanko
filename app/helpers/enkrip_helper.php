<?php
function get_enkrip_options()
{
    return array(
        'cipher' => 'aes128',
        'option' => 0,
        'key' => md5('J4smine1ndah'),
        'iv' => '3579518524567931'
    );
}

function self_encrypt($string)
{
    $option = get_enkrip_options();
    $enkrip = openssl_encrypt($string, $option['cipher'], $option['key'], $option['option'], $option['iv']);
    return rtrim($enkrip, '=');
}

function self_decrypt($string)
{
    $option = get_enkrip_options();
    return openssl_decrypt($string, $option['cipher'], $option['key'], $option['option'], $option['iv']);
}

function self_md5($text, $extra = '')
{
    $firstenkrip = md5(strval($text));
    return md5('3nKryPt_tH15_ch4R_' . $firstenkrip . $extra);
}

function self_hash($text, $extra = '')
{
    $firstenkrip = hash('sha256', strval($text));
    return md5('pLe45E_h45H_tH15_t3Xt_' . $firstenkrip . $extra . '_cH4r');
}
