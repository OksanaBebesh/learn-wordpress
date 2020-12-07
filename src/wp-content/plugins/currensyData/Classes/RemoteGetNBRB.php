<?php


namespace App\Classes;


class RemoteGetNBRB
{
static function GetCurrency(){
    $fields = array(
        "ondate"      => date('Y-m-d'),
        "periodicity" => 0
    );
    $response = wp_remote_get(
        'https://www.nbrb.by/api/exrates/rates?'.http_build_query($fields),
        array( 'timeout' => 120, 'httpversion' => '1.1')
    );
    $currencyDataGet = wp_remote_retrieve_body( $response );
     return json_decode($currencyDataGet,false );

}
static function GetNamesCurrency(){
    $response = wp_remote_get(
        'https://www.nbrb.by/api/exrates/currencies',
        array( 'timeout' => 120, 'httpversion' => '1.1')
    );
    $currencyDataGet = json_encode(wp_remote_retrieve_body( $response ));
    $currencyDataGet = json_decode($currencyDataGet,true);
     return $response->body->Cur_Abbreviation;
}
}