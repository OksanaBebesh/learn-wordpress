<?php


/**
 * Создаем страницу настроек плагина
 */

// Хук событие 'admin_menu', запуск функции
add_action( 'admin_menu','Add_Admin_Link');

function Add_Admin_Link()
{
    add_menu_page(
        'WPPlagin Page', // Название страниц (Title)
        'WPPlugin', // Текст ссылки в меню
        'manage_options', // Требование к возможности видеть ссылку
        __DIR__ .'/plagin-page.php' //  - файл отобразится по нажатию на ссылку
    );
}

