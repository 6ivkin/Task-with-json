<?php

if (isset($_POST['submit'])) {
    $new_message = array(
        "rate" => $_POST['product'],
        "km" => $_POST['distance'],
        "minutes" => $_POST['time'],
        "driveAge" => $_POST['age'],
        "additionalServices" => array(),
    );

    isset($_POST['check1']) ? $new_message['additionalServices'][] = 'wifi' : false;
    isset($_POST['check2']) ? $new_message['additionalServices'][] = 'driver' : false;

    $price = 0;
    $new_message['rate'] = 10 + $new_message[' minutes'] * 3; // Базовый тариф


    if (!empty($new_message['additionalServices'])) {
        if(isset($new_message['additionalServices']['wifi']) and $new_message['rate'] != 10 and $new_message['minutes'] >= 120){
            $price += 15 * round($new_message['minutes']/60);
        }
    }



    $json = json_encode($new_message, JSON_PRETTY_PRINT);
    file_put_contents("./data.json", $json);
//    $tariffPrice = json_decode($json, JSON_OBJECT_AS_ARRAY);

//    echo $tariffPrice['price'];
}