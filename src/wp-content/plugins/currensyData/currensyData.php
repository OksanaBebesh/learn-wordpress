<?php
/*
Plugin Name: Currensy Data Plugin
Description: Плагин получения актуальных курсов валют.
Version: 1.0
Author: Имя автора
*/

// Подключаем mfp-functions.php, используя require_once, чтобы остановить скрипт, если mfp-functions.php не найден
require_once plugin_dir_path(__FILE__) . 'includes/plgn-functions.php';


add_action('wpplagin_geting_course', 'get_wpplugin_geting_course_nbrb');
function get_wpplugin_geting_course_nbrb() {
    $fields = array(
        "ondate"      => date('Y-m-d'),
        "periodicity" => 0
    );
    $response = wp_remote_get(
        'https://www.nbrb.by/api/exrates/rates?'.http_build_query($fields),
        array( 'timeout' => 120, 'httpversion' => '1.1')
    );
    $currencyDataGet = wp_remote_retrieve_body( $response );
    $currencyDataGet =json_decode($currencyDataGet,false );
    $currencyOptions = array_keys(get_option('option_curs_check'));
    $arrayOptions = [];
    $arrayOptions['Date'] = date('d.m.Y H:i:s');

    foreach ($currencyDataGet as $value) {

        if (in_array(strtolower($value->Cur_Abbreviation), $currencyOptions)) {

            $arrayOptions[] = [
                'Cur_Abbreviation' => $value->Cur_Abbreviation,
                'Cur_Name'         => $value->Cur_Name,
                'Cur_OfficialRate' => $value->Cur_OfficialRate
            ];
        }

    }
    update_option('option_currencyData',$arrayOptions,'no');
    saveLog('get_wpplagin_geting_course_nbrb');

}

