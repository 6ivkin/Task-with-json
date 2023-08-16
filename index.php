<?php

require_once './src/HtmlOutput.php';
require_once './src/FormProcessor.php';
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

$tariffErrorHandlers = [
    new BaseTariffErrorHandler(),
    new DailyTariffErrorHandler(),
    new HourlyTariffErrorHandler(),
    new StudentTariffErrorHandler(),
];

$errorHandlerChain = null;
foreach ($tariffErrorHandlers as $handler) {
    if ($errorHandlerChain === null) {
        $errorHandlerChain = $handler;
    } else {
        $errorHandlerChain->setNext($handler);
    }
}

$formProcessor = new FormProcessor($errorHandlerChain);

$validationError = null;
if (isset($_POST['submit'])) {
    $validationError = $formProcessor->processForm($_POST);
}

$price_json = file_get_contents("./src/App/Json/price.json");
$price_data = json_decode($price_json, true);
$result = $price_data["result"] ?? '';

$htmlOutput = new HtmlOutput();
$htmlOutput->render($price_data, $validationError);
