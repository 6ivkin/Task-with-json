<?php

require_once './src/App/Interfaces/ErrorHandlerInterface.php';
require_once './src/App/Interfaces/TariffInterface.php';
require_once './src/App/Interfaces/AdditionalServiceInterface.php';
require_once './src/App/Tariffs/BaseTariff.php';
require_once './src/App/Tariffs/DailyTariff.php';
require_once './src/App/Tariffs/HourlyTariff.php';
require_once './src/App/Tariffs/StudentTariff.php';
require_once './src/App/Services/DriverService.php';
require_once './src/App/Services/WifiService.php';
require_once './src/ConvertToJson.php';

class FormProcessor
{
    private $errorHandler;

    public function __construct(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function processForm(array $formData)
    {
        $new_message = array(
            "rate" => filter_input(INPUT_POST, 'product', FILTER_SANITIZE_NUMBER_INT),
            "km" => filter_input(INPUT_POST, 'distance', FILTER_SANITIZE_NUMBER_INT),
            "minutes" => filter_input(INPUT_POST, 'time', FILTER_SANITIZE_NUMBER_INT),
            "driverAge" => filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT),
            "additionalServices" => array(),
        );

        isset($_POST['check1']) ? $new_message['additionalServices'][] = 'driver' : false;
        isset($_POST['check2']) ? $new_message['additionalServices'][] = 'wifi' : false;

        $validationError = $this->errorHandler->handle($new_message);

        if ($validationError !== null) {
            return $validationError;
        }

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
                $tariff = new StudentTariff();
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

        convertToJson($new_message, "./src/App/Json/data.json"); // Сохраняем данные в JSON

        $json = json_encode(array("result" => $price), JSON_PRETTY_PRINT);
        file_put_contents("./src/App/Json/price.json", $json);

        return null; // Возвращаем null, если обработка успешна
    }
}