<?php

require_once './src/App/Interfaces/ErrorHandlerInterface.php';

class StudentTariff implements TariffInterface
{
    public function calculatePrice(array $data): int
    {
        // Расчет цены
        return (1 * $data['minutes']) + (4 + $data['km']);
    }
}

class StudentTariffErrorHandler implements ErrorHandlerInterface
{
    private $nextHandler;

    public function setNext(ErrorHandlerInterface $handler): ErrorHandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(array $data): ?string
    {
        if (empty($data['km'])) {
            return 'Введите количество километров.';
        }

        if (empty($data['driverAge'])) {
            return 'Введите возраст водителя.';
        }

        if (!is_numeric($data['km'])) {
            return 'Количество километров должно быть числовым значением.';
        }

        if (!is_numeric($data['driverAge'])) {
            return 'Возраст водителя должен быть числовым значением.';
        }

        if (!is_numeric($data['minutes'])) {
            return 'Количество времени должно быть числовым значением.';
        }

        if ($data['km'] < 0) {
            // ... проверка ошибки ...
            // если ошибка:
            return 'Количество километров не может быть отрицательным.';
        }

        if ($data['driverAge'] > 110) {
            return 'Введите корректный возраст.';
        }

        if (($data['driverAge'] < 26) and ($data['driverAge'] < 18)) {
            return 'Этот тариф доступен лицам от 18 до 25 лет.';
        }

        if ($this->nextHandler !== null) {
            return $this->nextHandler->handle($data);
        }

        return null; // Ошибка не обработана
    }
}