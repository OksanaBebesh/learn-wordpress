<?php


namespace App\Classes;

use App\Classes\RemoteGetNBRB;
class PluginCron
{
  public  function  __construct()
    {
        add_filter('cron_schedules', [$this,'cron_add_five_sec']);
    }

  public  function cron_add_five_sec($schedules) {

        if (!isset($schedules['five_min'])) {
            $schedules['five_min'] = array(
                'interval' => 60 * 5,
                'display'  => 'Каждые 5 минут'
            );
        }
        return $schedules;
    }


    public  function wppluginGetCourseNbrb() {

        $currencyOptions = array_keys(get_option('option_curs_check'));
        $arrayOptions = [];
        $arrayOptions['Date'] = date('d.m.Y H:i:s');
        $currencyDataGet = RemoteGetNBRB::GetCurrency();
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
       // saveLog('get_wpplagin_geting_course_nbrb');

    }
}