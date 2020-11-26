<?php
/*
Plugin Name: My Currensy Data Plugin
Description: Это мой первый плагин!
Version: 1.0
Author: Имя автора
*/


/**
 * Создаем страницу настроек плагина
 */
add_action('admin_menu', 'add_plugin_page');
function add_plugin_page(){
    add_options_page( 'Настройки Primer', 'Primer', 'administrator', 'primer_slug', 'primer_options_page_output' );
}

function primer_options_page_output(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <form action="options.php" method="POST">
            <?php
            settings_fields( 'option_group' );     // скрытые защитные поля
            do_settings_sections( 'primer_page' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Регистрируем настройки.
 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
 */
add_action('admin_init', 'plugin_settings');
function plugin_settings(){
    // параметры: $option_group, $option_name, $sanitize_callback
    register_setting( 'option_group', 'option_curs_check', 'sanitize_callback' );

    // параметры: $id, $title, $callback, $page
    add_settings_section( 'section_id', 'Основные настройки', '', 'primer_page' );

    // параметры: $id, $title, $callback, $page, $section, $args
    add_settings_field('primer_field1', 'Название опции', 'fill_primer_field1', 'primer_page', 'section_id' );
    add_settings_field('primer_field2', 'Другая опция', 'fill_primer_field2', 'primer_page', 'section_id' );
}

## Заполняем опцию 2
function fill_primer_field2(){

    $file = file_get_contents('https://www.nbrb.by/api/exrates/currencies');  // Открыть файл data.json
    $taskList = json_decode($file,TRUE);        // Декодировать в массив
    unset($file);

////валюты euro=978 usa 643 rus 840
    $arrCurrentCodes = array(978,643,840);
    $arrCurrentValues = array();


    $val = get_option('option_curs_check');
    $val = $val ? $val['checkbox'] : null;

    //валюты euro=978 usa 643 rus 840
    $arrCurrentCodes = array(978,643,840);
    $arrCurrentValues = array();

    foreach ($taskList as $item) {

        if (in_array($item['Cur_Code'],$arrCurrentCodes) ){

            if (! in_array($item['Cur_Code'] ,$arrCurrentValues)) {
                array_push($arrCurrentValues, $item['Cur_Code']);

                $checked = ($item['Cur_Code'] == $val) ? 'checked' : '';
                echo '<input type="checkbox" name="option_curs_check[checkbox]" value="'. $item['Cur_Code'] .'" '.$checked.'/>' . $item['Cur_Name_Bel'] . '<br>';
            }
        }
    }

    ?>
    <!--    <label><input type="checkbox" name="option_curs_check[checkbox]" value="1" --><?php //checked( 1, $val ) ?><!-- /> отметить</label>-->
    <?php
}