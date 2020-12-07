<?php


namespace App\Classes;
use App\Classes\RemoteGetNBRB;

class PluginWidget extends \WP_Widget
{
    public  function __construct()
    {
        parent::__construct(
            'PluginWidget',
            __('Currency data', 'PluginWidget_domain'),
            array('description' => __('Display currency data', 'PluginWidget_domain'),)
        );

    }

      public function widget($args, $instance) {

        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        // This is where you run the code and display the output
        echo __('Актуальные курсы валют на дату: ' . date('d.m.Y'), 'wpb_widget_domain');
        echo $args['after_widget'];

        if (get_option('option_plugin_mode') == 'live') {

            $currencyDataGet = RemoteGetNBRB::GetCurrency();
            $currencyOptions = array_keys(get_option('option_curs_check'));

            foreach ($currencyDataGet as $value) {
                if (in_array(strtolower($value->Cur_Abbreviation), $currencyOptions, true)) {

                    echo '<label>' . $value->Cur_Name . '</label>';
                    echo '<label><strong>' . $value->Cur_OfficialRate . '</strong></label>';
                }

            }

        } elseif (get_option('option_plugin_mode') == 'cron') {
            /**/
            $currencyDataGet = get_option('option_currencyData');
            $currencyOptions = array_keys(get_option('option_curs_check'));

            foreach ($currencyDataGet as $value => $index) {

                if ((isset($index['Cur_Abbreviation'])) && (in_array(strtolower($index['Cur_Abbreviation']), $currencyOptions, true))) {

                    echo '<label>' . $index['Cur_Name'] . '</label>';
                    echo '<label><strong>' . $index['Cur_OfficialRate'] . '</strong></label>';
                }

            }


        }
    }

// Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'PluginWidget_domain');
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
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}