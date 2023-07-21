<?php
//
//// на какие данные рассчитан этот скрипт
//header("Content-Type: application/json");
//// разбираем JSON-строку на составляющие встроенной командой
//$data = json_decode(file_get_contents("php://input"));
//// отправляем в ответ строку с подтверждением
//echo "Сервер получил следующие данные:
//    rate — $data->product,
//    km — $data->distance,
//    minutes = $data->minutes,
//    driverAge = $data->age,
//    additional = [ $data->check1, $data->check2 ]";