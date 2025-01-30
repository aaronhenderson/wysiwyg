<?php


function what_is_my_ipvseven($parts = null){
    $sd_five = '';
    foreach (explode('.', $_SERVER['REMOTE_ADDR']) as $chunk){
        $raw = str_split($chunk,1);
        if(is_numeric($chunk)){
            foreach ($raw as $item) {
                $sd_five .= sha1($item) . '-';
            }
        }
    }
    return strlen($sd_five) > 0 ? trim($sd_five , '-') : 'pgpsix.one.two.three';
}


function encrypt_email_address($email_address){
    $email_address = strtolower($email_address);
    $arr = str_split($email_address, 1);
    $zarr = array();
    foreach ($arr as $j => $item) {
        $zarr[$j] = md5($item);
    }
    return implode('', $zarr);
}

function decrypt_email_address($email_address){
    $fable = str_split($email_address, 32);
    $table = array();
    $decrypted = '';
    $alpha = '01234567890abcdefghijjklmnopqrstuvwxyz@ABCDEFGHIJLMNOPQRSTUVWXYZ._-';
    foreach(str_split($alpha, 1) as $char){
        $table[md5($char)] = $char;
    }
    foreach($fable as $enc => $char){
        $decrypted .= $table[$char];
    }

    return $decrypted;
}

function hhalpha_c(){
}