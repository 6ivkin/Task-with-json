<?php

require_once './src/App/Interfaces/ErrorHandlerInterface.php';

class HourlyTariff implements TariffInterface
{
    public function calculatePrice(array $data): int
    {
        // Проверить количество километров на наличие отрицательных значений
        return 200 * round($data['minutes'] / 60);
    }
}

class HourlyTariffErrorHandler implements ErrorHandlerInterface
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

        if($data['driverAge'] < 18) {
            return 'Минимальный возраст должен быть 18 лет.';
        }

        if (round($data['minutes'] / 60) < 1) {
            return 'Время не может быть меньше одного часа.';
        }

        if ($this->nextHandler !== null) {
            return $this->nextHandler->handle($data);
        }

        return null; // Ошибка не обработана
    }
}