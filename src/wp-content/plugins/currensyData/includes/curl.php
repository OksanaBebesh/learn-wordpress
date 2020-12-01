<?php
function curlGet() {

    $ch = curl_init(); // create cURL handle (ch)
    if (!$ch) {
        die("Couldn't initialize a cURL handle");
    }
    $fields = array(
        "ondate"      => date('Y-m-d'),
        "periodicity" => 0
    );

// set some cURL options
    curl_setopt($ch, CURLOPT_URL, "https://www.nbrb.by/api/exrates/rates?".http_build_query($fields));
    //curl_setopt($ch, CURLOPT_HEADER, array);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);

//Execute the request.
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch); // close cURL handler
var_dump($info['http_code']);
   var_dump(curl_errno($ch));
   var_dump(curl_error($ch));
    if ($info['http_code'] == 200) {
       $response = json_decode($response);
    } else {
       $response = "Data not found: <br />";
    }

    curl_close($ch); // close cURL handler

    return $response;
}