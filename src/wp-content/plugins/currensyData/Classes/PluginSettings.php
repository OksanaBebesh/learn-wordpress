<?php


namespace App\Classes;

use App\Classes\PluginCron;
class PluginSettings
{
    /**
     * @var \App\Classes\PluginCron
     */
    private $cron;

    public function __construct()
    {
        add_action('admin_menu', [$this, 'addAdminLink']);
        add_action('admin_init', [$this, 'setPluginSettings']);
        add_action('update_option_option_plugin_mode',[$this, 'chooseMode'],10,2);
        $this->cron = new PluginCron();
        add_action('wpplagin_geting_course', [$this->cron,'wppluginGetCourseNbrb']);

    }

    public  function addAdminLink()
    {

        add_menu_page(
            'WPPlagin Page',
            'WPPlugin',
            'manage_options',
            'plaginPageSlug',
            [$this,'pluginPageSettings']
        );
    }


    public function setPluginSettings()
    {

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
            'pluginPageSlug');

        // параметры: $id, $title, $callback, $page, $section, $args
        add_settings_field('mode', 'Режим работы плагина', [$this,'fillMode'], 'pluginPageSlug', 'section_id');
        add_settings_field('currency', 'Валюта', [$this,'fillCurrency'], 'pluginPageSlug', 'section_id');
    }


    public function pluginPageSettings()
    {

        ?>
        <div class="wrap">
            <h2><?php echo get_admin_page_title() ?></h2>

            <form action="options.php" method="POST">
                <?php
                settings_fields('option_group');     // скрытые защитные поля
                do_settings_sections('pluginPageSlug'); // секции с настройками (опциями). У нас она всего одна 'section_id'
                submit_button();
                ?>
            </form>
        </div>

        <?php
    }


    public function fillMode()
    {
        $optionMode = get_option('option_plugin_mode');

        echo '<input type="radio" name="option_plugin_mode" ' . checked($optionMode, 'live', false) . ' value="live">live';
        echo '<input type="radio" name="option_plugin_mode" ' . checked($optionMode, 'cron', false) . '  value="cron">cron';
    }

    public function fillCurrency()
    {
        $currencyArray = [
            'eur',
            'usd',
            'rub'
        ];

        $getCurrency = array_keys(get_option('option_curs_check'));
        $checkElement = '';
        if ($getCurrency) {
            foreach ($currencyArray as $valueCurrency) {
                $checkElement = (in_array($valueCurrency, $getCurrency,false)) ? "checked" : '';

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

    public function chooseMode($old_value, $value){
        switch ($value) {
            case 'cron':
                if (!wp_next_scheduled('wpplagin_geting_course')) {
                    wp_schedule_event(time(), 'five_min', 'wpplagin_geting_course');
                }
               // saveLog('update_option_option_plugin_mode-cron');

                break;
            case 'live':
                wp_clear_scheduled_hook('wpplagin_geting_course');
                //saveLog('update_option_option_plugin_mode-live');
                break;
        }
    }
}