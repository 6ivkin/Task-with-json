<?php


if (isset($_POST['submit'])) {
    $new_message = array(
        "rate" => $_POST['product'],
        "km" => $_POST['distance'],
        "minutes" => $_POST['time'],
        "driveAge" => $_POST['age'],
        "additionalServices" => array(),
    );

    isset($_POST['check1']) ? $new_message['additionalServices'][] = 'driver' : false;
    isset($_POST['check2']) ? $new_message['additionalServices'][] = 'wifi' : false;

    $price = 0;
    if ($new_message['rate'] == 10) { // Базовый тариф
        $price = ($new_message['km'] * $new_message['rate']) + (3 * $new_message['minutes']);
    }
    if ($new_message['rate'] == 1000 and round($new_message['minutes'] / 60) >= 24) { // Дневной тариф
        $price = 1000 * round(($new_message['minutes'] / 60) / 24);
    }
    if ($new_message['rate'] == 200) { // почасовой тариф
        $price = 200 * round($new_message['minutes'] / 60);
    }
    if ($new_message['rate'] == 4 and $new_message['driveAge'] <= 25) { // Студенческий тариф
        $price = (1 * $new_message['minutes']) + (4 * $new_message['km']);
    }

    if (!empty($new_message['additionalServices'])) { // Check for additional services
        if (in_array('driver', $new_message['additionalServices']) and $new_message['rate'] != 4) {
            $price += 100;
        }
        if (in_array('wifi', $new_message['additionalServices']) and $new_message['rate'] != 10 && $new_message['minutes'] >= 120) {
            $price += 15 * round($new_message['minutes'] / 60);
        }
    }

    $json = json_encode($new_message, JSON_PRETTY_PRINT);
    file_put_contents("./project/data.json", $json);

    $json = json_encode(array("result" => $price), JSON_PRETTY_PRINT);
    file_put_contents("./project/price.json", $json);
}



