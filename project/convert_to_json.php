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
        return ($data['km'] * $data['rate']) + (3 * $data['minutes']);
    }
}

class DailyTariff implements TariffInterface
{

    public function calculatePrice(array $data): int
    {
        return 1000 * round(($data['minutes'] / 60) / 24);
    }
}

class HourlyTariff implements TariffInterface
{

    public function calculatePrice(array $data): int
    {
        return 200 * round($data['minutes'] / 60);
    }
}

class StudentTariff implements TariffInterface
{

    public function calculatePrice(array $data): int
    {
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
        // TODO: Implement calculateAdditionalCost() method.
        return 100;
    }
}

class WifiService implements AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int
    {
        return 15 * round($data['minutes'] / 60);
    }
}

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
            if ($new_message['driveAge'] <= 25) {
                $tariff = new StudentTariff();
            }
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



