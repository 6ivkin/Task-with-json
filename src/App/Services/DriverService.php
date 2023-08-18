<?php

class DriverService implements AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int
    {
        return 100;
    }
}

class DriverTariffErrorHandler implements ErrorHandlerInterface
{
    private $nextHandler;

    public function setNext(ErrorHandlerInterface $handler): ErrorHandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(array $data): ?string
    {
        if(in_array('driver', $data['additionalServices']) and $data['rate'] == 4) {
            return 'Эта услуга не доступна в этом тарифе.';
        }

        if ($this->nextHandler !== null) {
            return $this->nextHandler->handle($data);
        }

        return null; // Ошибка не обработана
    }
}