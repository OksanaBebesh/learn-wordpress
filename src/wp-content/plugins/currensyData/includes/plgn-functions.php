<?php


/**
 * Создаем страницу настроек плагина
 */

// Хук событие 'admin_menu', запуск функции
add_action('admin_menu', 'addAdminLink');

function addAdminLink() {
    add_menu_page(
        'WPPlagin Page', // Название страниц (Title)
        'WPPlugin', // Текст ссылки в меню
        'manage_options', // Требование к возможности видеть ссылку
        'plaginPageSlug',
        'plaginPageSettings'//  - функция выполнит прорисовку страницы настроек
    );
}

add_action('admin_init', 'set_plugin_settings');
function set_plugin_settings() {
    // параметры: $option_group, $option_name, $sanitize_callback
    register_setting(
        'option_group',
        'option_curs_check',
        'sanitize_callback');

    // параметры: $id, $title, $callback, $page
    add_settings_section(
        'section_id',
        'Основные настройки',
        '',
        'plaginPageSlug');

    // параметры: $id, $title, $callback, $page, $section, $args
    add_settings_field('mode', 'Режим работы плагина', 'fillMode', 'plaginPageSlug', 'section_id');
    add_settings_field('currency', 'Валюта', 'fillCurrensy', 'plaginPageSlug', 'section_id');

}


function plaginPageSettings() {

    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <form action="options.php" method="POST">
            <?php
            settings_fields('option_group');     // скрытые защитные поля
            do_settings_sections('plaginPageSlug'); // секции с настройками (опциями). У нас она всего одна 'section_id'
            submit_button();
            ?>
        </form>
    </div>

    <?php
}

function fillCurrensy() {
    $currencyArray = [
        '978' => 'euro',
        '643' => 'usa',
        '840' => 'rus'
    ];
    $getCurrensy = get_option('option_curs_check');
    $getCurrensy = json_encode($getCurrensy);

    if ($getCurrensy) {
        foreach ($currencyArray as $valueCurrency => $index) {
            echo '
            <label>
            <input 
            type="checkbox" 
            name="option_curs_check[' . $valueCurrency . ']" 
            value="1"
            checked( 1, $valueCurrency  ) 
            >' . $index . '</label>';
        }
    }
//    else {
//
//        foreach ($getCurrensy as $value => $index) {
//            echo '<input type="checkbox"
//            name="option_curs_check['.$value.']"
//            value="1"
//            '.checked($value).'
//            />' . $value;
//        }
 //   }


}