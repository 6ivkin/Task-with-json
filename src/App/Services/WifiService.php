<?php

class WifiService implements AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int
    {
        // Расчет цены за wifi
        return 15 * round($data['minutes'] / 60);
    }
}

class WifiTariffErrorHandler implements ErrorHandlerInterface
{
    private $nextHandler;

    public function setNext(ErrorHandlerInterface $handler): ErrorHandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(array $data): ?string
    {
        if (in_array('wifi', $data['additionalServices']) and $data['minutes'] < 120) {
            return 'Минимальное время поездки 2 часа.';
        }

        if ($this->nextHandler !== null) {
            return $this->nextHandler->handle($data);
        }

        return null; // Ошибка не обработана
    }
}