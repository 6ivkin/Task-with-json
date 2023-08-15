<?php

// public/index.php
require_once '../vendor/autoload.php';
require_once './src/ConvertToJson.php';
require_once './src/Utils.php';

use App\Tariffs\BaseTariff;
use App\Tariffs\DailyTariff;
use App\Tariffs\HourlyTariff;
use App\Tariffs\StudentTariff;
use App\Services\DriverService;
use App\Services\WifiService;
use App\Utils;
use App\ConvertToJson;

if (isset($_POST['submit'])) {
    $new_message = array(
        "rate" => Utils::sanitizeNumber(INPUT_POST, 'product'),
        "km" => Utils::sanitizeNumber(INPUT_POST, 'distance'),
        "minutes" => Utils::sanitizeNumber(INPUT_POST, 'time'),
        "driveAge" => Utils::sanitizeNumber(INPUT_POST, 'age'),
        "additionalServices" => array(),
    );

    $tariff = null;

    switch ($new_message['rate']) {
        case 10:
            $tariff = new BaseTariff();
            break;
        case 1000:
            if (round($new_message['minutes'] / 60) >= 24) {
                $tariff = new DailyTariff();
            }
            break;
        case 200:
            $tariff = new HourlyTariff();
            break;
        case 4:
            $tariff = new StudentTariff();
            break;
        default:
            break;
    }

    // Additional services logic
    $additionalServices = array();

    if (!empty($new_message['additionalServices'])) {
        foreach ($new_message['additionalServices'] as $service) {
            switch ($service) {
                case 'driver':
                    $additionalServices[] = new DriverService();
                    break;
                case 'wifi':
                    $additionalServices[] = new WifiService();
                    break;
                default:
                    break;
            }
        }
    }

    $price = 0;

    if ($tariff !== null) {
        // Рассчитать цену с использованием соответствующего тарифного объекта
        $price = $tariff->calculatePrice($new_message);

        // Рассчитать дополнительные затраты на выбранные услуги
        foreach ($additionalServices as $service) {
            $price += $service->calculateAdditionalCost($new_message);
        }
    }

    $json = json_encode($new_message, JSON_PRETTY_PRINT);
    file_put_contents("./project/data.json", $json);

    $json = json_encode(array("result" => $price), JSON_PRETTY_PRINT);
    file_put_contents("./project/price.json", $json);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous">
    <link href="assets/css/style_form.min.css" rel="stylesheet"/>
    <link href="assets/css/site.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row row-body">
        <div class="col-3">
            <span style="text-align: center">Форма обратной связи</span>
            <i class="bi bi-activity"></i>
        </div>
        <div class="col-9">
            <form action="" id="form" method="post">
                <label class="form-label" for="product">Выберите тариф:</label>
                <select class="form-select" name="product" id="product">
                    <option value="10">Базовый тариф</option>
                    <option value="1000">Дневной тариф</option>
                    <option value="200">Почасовой тариф</option>
                    <option value="4">Студенческий</option>
                </select>

                <label for="customRange1" class="form-label">Количество километров:</label>
                <input type="text" name="distance" class="form-control" id="customRange1" min="1">
                <?php if (isset($error_km)) { ?>
                    <p style="color: red;"><?php echo $error_km; ?></p>
                <?php } ?>

                <label for="customRange1" class="form-label">Сколько планируете времени:</label>
                <input type="text" name="time" class="form-control" id="customRange2" min="1">
                <?php if (isset($error_minutes)) { ?>
                    <p style="color: red;"><?php echo $error_minutes; ?></p>
                <?php } ?>
                <?php if (isset($error_minutes_daily)) { ?>
                    <p style="color: red;"><?php echo $error_minutes_daily; ?></p>
                <?php } ?>

                <label for="customRange1" class="form-label">Ваш возраст:</label>
                <input type="text" name="age" class="form-control" id="customRange2" min="18">
                <?php if (isset($error_driverAge)) { ?>
                    <p style="color: red;"><?php echo $error_driverAge; ?></p>
                <?php } ?>
                <?php if (isset($error_driverRate_student)) { ?>
                    <p style="color: red;"><?php echo $error_driverRate_student; ?></p>
                <?php } ?>

                <label for="customRange1" class="form-label">Дополнительно:</label>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="check1" id="flexCheckChecked1">
                    <label class="form-check-label" for="flexCheckChecked1">
                        Дополнительный водитель
                    </label>
                    <?php if (isset($error_additional_driver)) { ?>
                        <p style="color: red;"><?php echo $error_additional_driver; ?></p>
                    <?php } ?>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="check2" id="flexCheckChecked2">
                    <label class="form-check-label" for="flexCheckChecked1">
                        Дополнительный WIFI
                    </label>
                    <?php if (isset($error_minutes_wifi)) { ?>
                        <p style="color: red;"><?php echo $error_minutes_wifi; ?></p>
                    <?php } ?>
                </div>

                <button type="submit" name="submit" class="btn btn-primary">Рассчитать</button>
                <p class="result" style="color:blue"></p>
                <div class="col-6">
                    <p class="form-label">Итоговая цена:
                        <?php
                        $price_json = file_get_contents("./project/price.json");
                        $price_data = json_decode($price_json, true);
                        if (isset($price_data["result"])) {
                            $result = $price_data["result"];
                            echo htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
                        }
                        ?>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>