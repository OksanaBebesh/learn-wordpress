
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <form action="options.php" method="POST">
            <?php
            settings_fields('option_group');     // скрытые защитные поля
            do_settings_sections('primer_page'); // секции с настройками (опциями). У нас она всего одна 'section_id'
            submit_button();
            ?>
        </form>
    </div>

    <?php


    $file = file_get_contents('https://www.nbrb.by/api/exrates/currencies');  // Открыть файл data.json
    $taskList = json_decode($file, TRUE);        // Декодировать в массив
    unset($file);

////валюты euro=978 usa 643 rus 840
    $arrCurrentCodes = array(978, 643, 840);
    $arrCurrentValues = array();


    $val = get_option('option_curs_check');
    $val = $val ? $val['checkbox'] : null;

    //валюты euro=978 usa 643 rus 840
    $arrCurrentCodes = array(978, 643, 840);
    $arrCurrentValues = array();

    foreach ($taskList as $item) {

        if (in_array($item['Cur_Code'], $arrCurrentCodes)) {

            if (!in_array($item['Cur_Code'], $arrCurrentValues)) {
                array_push($arrCurrentValues, $item['Cur_Code']);

                $checked = ($item['Cur_Code'] == $val) ? 'checked' : '';
                echo '<input type="checkbox" name="option_curs_check[checkbox]" value="' . $item['Cur_Code'] . '" ' . $checked . '/>' . $item['Cur_Name_Bel'] . '<br>';
            }
        }
    }
