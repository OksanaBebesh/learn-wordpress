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

    register_setting(
        'option_group',
        'option_plugin_mode',
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

function fillMode() {
    $optionMode = get_option('option_plugin_mode');

    echo '<input type="radio" name="option_plugin_mode" ' . checked($optionMode, 'live', false) . ' value="live">live';
    echo '<input type="radio" name="option_plugin_mode" ' . checked($optionMode, 'cron', false) . '  value="cron">cron';
}

function fillCurrensy() {
    $currencyArray = [
        'eur',
        'usd',
        'rub'
    ];
    $getCurrensy = array_keys(get_option('option_curs_check'));
    $checkElement = '';
    if ($getCurrensy) {
        foreach ($currencyArray as $valueCurrency) {
            $checkElement = (in_array($valueCurrency, $getCurrensy)) ? "checked" : '';

            echo '
            <label>
            <input 
            type="checkbox" 
            name="option_curs_check[' . $valueCurrency . ']" 
            value="1"
            ' . $checkElement . '
            >' . $valueCurrency . '</label>';
        }
    } else {

        foreach ($currencyArray as $value => $index) {
            echo '<label><input type="checkbox"
            name="option_curs_check[' . $value . ']"
            value="1"
            />' . $index . '</label>';
        }
    }


}


// Register and load the widget
function wpb_load_widget() {
    register_widget('wpb_widget');
}

add_action('widgets_init', 'wpb_load_widget');

// Creating the widget
class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(

// Base ID of your widget
            'wpb_widget',

// Widget name will appear in UI
            __('Currency data', 'wpb_widget_domain'),

// Widget description
            array('description' => __('Display currency data', 'wpb_widget_domain'),)
        );
    }

// Creating widget front-end

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);

// before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
        echo __('Актуальные курсы валют на дату: ' . date('d.m.Y'), 'wpb_widget_domain');

        echo $args['after_widget'];

        require_once plugin_dir_path(__FILE__) . '/curl.php';
        if (get_option('option_plugin_mode') == 'live') {
            $currencyDataGet = curlGet();
            $currencyOptions = array_keys(get_option('option_curs_check'));
            foreach ($currencyDataGet as $value) {
                if (in_array(strtolower($value->Cur_Abbreviation), $currencyOptions)) {

                    echo '<label>' . $value->Cur_Name . '</label>';
                    echo '<label><strong>' . $value->Cur_OfficialRate . '</strong></label>';
                }

            }
        }
    }

// Widget Backend
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'wpb_widget_domain');
        }
// Widget admin form
        ?>


        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
               name="<?php echo $this->get_field_name('title'); ?>" type="text"
               value="<?php echo esc_attr($title); ?>"/>


        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
} // Class wpb_widget ends here