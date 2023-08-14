<?php
require_once('./project/convert_to_json.php');

if (isset($_POST['submit'])) {
    // Initialize variables
    $new_message = array(
        "rate" => filter_input(INPUT_POST, 'product', FILTER_SANITIZE_NUMBER_INT),
        "km" => filter_input(INPUT_POST, 'distance', FILTER_SANITIZE_NUMBER_INT),
        "minutes" => filter_input(INPUT_POST, 'time', FILTER_SANITIZE_NUMBER_INT),
        "driveAge" => filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT),
        "additionalServices" => array(),
    );

    if ($new_message['driveAge'] < 18) {
        $error_driverAge = 'Ваш возраст должен быть не меньше 18 лет.';
    }

    if ($new_message['km'] < 0) {
        $error_km = 'Количество километров не может быть отрицательным.';
    }

    if ($new_message['rate'] == 200 && $new_message['minutes'] < 60) {
        $error_minutes = 'Для почасового тарифа время не может быть меньше 60 минут.';
    }

    if ($new_message['minutes'] < 120) {
        $error_minutes_wifi = 'Для этой опции время должно быть выше 2-х часов.';
    }

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

                <label for="customRange1" class="form-label">Ваш возраст:</label>
                <input type="text" name="age" class="form-control" id="customRange2" min="18">
                <?php if (isset($error_driverAge)) { ?>
                    <p style="color: red;"><?php echo $error_driverAge; ?></p>
                <?php } ?>

                <label for="customRange1" class="form-label">Дополнительно:</label>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="check1" id="flexCheckChecked1">
                    <label class="form-check-label" for="flexCheckChecked1">
                        Дополнительный водитель
                    </label>
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