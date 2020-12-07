<?php
/*
Plugin Name: Currensy Data Plugin
Description: Плагин получения актуальных курсов валют.
Version: 1.0
Author: Имя автора
*/

use App\Classes\PluginWidget;
use App\Classes\PluginSettings;
use App\Classes\PluginCron;
require_once('vendor/autoload.php');
require_once plugin_dir_path(__FILE__) . 'functions.php';

add_action( 'widgets_init', 'loadPlugin');
function loadPlugin(){
    register_widget(new PluginWidget);
}


//echo "<pre>";
//var_dump($PluginWidget);
//echo "</pre>";


new PluginSettings();
//    $cron = new PluginCron();
//    $cron->Register();




//
//





