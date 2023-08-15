<?php

// Определяет интерфейс базового тарифа с методом расчета цены
interface TariffInterface
{
    public function calculatePrice(array $data): int;
}

// Главные тарифы
class BaseTariff implements TariffInterface
{

    public function calculatePrice(array $data): int
    {
        // Проверить количество километров на наличие отрицательных значений
//        if ($data['km'] < 0) {
//            throw new \InvalidArgumentException('Количество километров не может быть отрицательным');
//        }

        // Проверить время на отрицательное значение
//        if ($data['minutes'] < 0) {
//            throw new \InvalidArgumentException('Время не может быть отрицательным');
//        }

        // Расчет цены
        return ($data['km'] * $data['rate']) + (3 * $data['minutes']);
    }
}

class DailyTariff implements TariffInterface
{

    public function calculatePrice(array $data): int
    {
        // Проверить количество километров на наличие отрицательных значений
//        if ($data['km'] < 0) {
//            throw new \InvalidArgumentException('Количество километров не может быть отрицательным');
//        }

        // Проверить время на значение, не должно быть меньше 1 дня
//        if(round($data['minutes'] / 60) < 24){
//            throw new \InvalidArgumentException('Время не может быть меньше одного дня');
//        }

        // Расчет цены
        return 1000 * round(($data['minutes'] / 60) / 24);
    }
}

class HourlyTariff implements TariffInterface
{

    public function calculatePrice(array $data): int
    {
        // Проверить количество километров на отрицательное значение
//        if($data['km'] < 0) {
//            throw new \InvalidArgumentException('Количество километров не может быть отрицательным');
//        }

        // Проверить время, не может быть меньше 1 часа
//        if($data['minutes'] / 60 < 1) {
//            throw new \InvalidArgumentException('Время не может быть меньше 1 часа');
//        }

        // Проверить количество километров на наличие отрицательных значений
        return 200 * round($data['minutes'] / 60);
    }
}

class StudentTariff implements TariffInterface
{

    public function calculatePrice(array $data): int
    {
        // Проверить количество километров на отрицательное значение
//        if($data['km'] < 0) {
//            throw new \InvalidArgumentException('Количество километров не может быть отрицательным');
//        }

        // Расчет цены
        return (1 * $data['minutes']) + (4 + $data['km']);
    }
}

// интерфейс дополнительной услуги с методом расчета дополнительной стоимости
interface AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int;
}

// Дополнительные тарифы
class DriverService implements AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int
    {
        // Проверка возраста
//        if($data['driverAge'] <= 25) {
//            return 0;
//        }

        // Дополнительная стоимость для тарифа
        return 100;
    }
}

class WifiService implements AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int
    {
        // Проверка на время поездки
//        if($data['minutes'] < 120) {
//            return 0;
//        }

        // Расчет цены за wifi
        return 15 * round($data['minutes'] / 60);
    }
}

if (isset($_POST['submit'])) {
    $new_message = array(
        "rate" => filter_input(INPUT_POST, 'product', FILTER_SANITIZE_NUMBER_INT),
        "km" => filter_input(INPUT_POST, 'distance', FILTER_SANITIZE_NUMBER_INT),
        "minutes" => filter_input(INPUT_POST, 'time', FILTER_SANITIZE_NUMBER_INT),
        "driveAge" => filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT),
        "additionalServices" => array(),
    );

    isset($_POST['check1']) ? $new_message['additionalServices'][] = 'driver' : false;
    isset($_POST['check2']) ? $new_message['additionalServices'][] = 'wifi' : false;

    $tariff = null;

    // Основные тарифы
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
//            if ($new_message['driveAge'] <= 25) {
                $tariff = new StudentTariff();
//            }
            break;
        default:
            break;
    }

    $additionalServices = array();

    // Дополнительные тарифы
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



