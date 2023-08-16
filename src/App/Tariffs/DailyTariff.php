<?php

require_once './src/App/Interfaces/ErrorHandlerInterface.php';

class DailyTariff implements TariffInterface
{
    public function calculatePrice(array $data): int
    {
        // Расчет цены
        return 1000 * round(($data['minutes'] / 60) / 24);
    }
}

class DailyTariffErrorHandler implements ErrorHandlerInterface
{
    private $nextHandler;

    public function setNext(ErrorHandlerInterface $handler): ErrorHandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(array $data): ?string
    {
        if ($data['km'] < 0) {
            // ... проверка ошибки ...
            // если ошибка:
            return 'Количество километров не может быть отрицательным.';
        }

        if ($data['driverAge'] < 18) {
            return 'Минимальный возраст должен быть 18 лет.';
        }

        if (round($data['minutes'] / 60) < 24) {
            return 'Время не может быть меньше одного дня.';
        }

        if (in_array('wifi', $data['additionalServices']) and $data['minutes'] < 120) {
            return 'Минимальное время поездки 2 часа.';
        }

        if ($this->nextHandler !== null) {
            return $this->nextHandler->handle($data);
        }

        return null; // Ошибка не обработана
    }
}