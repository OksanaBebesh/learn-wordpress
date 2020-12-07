<?php
function saveLog(string $str_to_log = '') {

    $f = fopen(__DIR__ . '/../logs/1.txt', 'a+');
    fwrite($f, date('d.m.Y H:i:s') . '. ' . $str_to_log . "\n");
    fclose($f);

}