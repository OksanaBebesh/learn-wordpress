<?php
/*
Plugin Name: Currensy Data Plugin
Description: Плагин получения актуальных курсов валют.
Version: 1.0
Author: Имя автора
*/

// Подключаем mfp-functions.php, используя require_once, чтобы остановить скрипт, если mfp-functions.php не найден
require_once plugin_dir_path(__FILE__) . 'includes/plgn-functions.php';

//function showDataPlugin() {
//
//
//    $file = file_get_contents('https://www.nbrb.by/api/exrates/rates/840?parammode=1');
//    var_dump($file);
//    $taskList = explode(',', $file);        // Декодировать в массив
//    unset($file);
//    var_dump($taskList);
//
//    $showCurrency = array_keys(get_option('option_curs_check'));
//    var_dump($showCurrency);
//    foreach ($taskList as $item) {
//
//        if (in_array($item['NumCode'], $showCurrency)) {
//
//            echo $item['Name'];
//            echo $item['Rate'];
//
//        }
//    }
//
//}
//
//add_action('init', 'showDataPlugin');


function my_awesome_func( WP_REST_Request $request ){

    $posts = get_posts( array(
        'author' => (int) $request['id'],
    ) );

    if ( empty( $posts ) )
        return new WP_Error( 'no_author_posts', 'Записей не найдено', [ 'status' => 404 ] );

    return $posts;
}

add_action( 'rest_api_init', function(){

    register_rest_route( 'myplugin/v1', '/author-posts/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'my_awesome_func',
    ] );

} );