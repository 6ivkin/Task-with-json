<?php

// src/App/Services/DriverService.php
namespace App\Services;

use App\Interfaces\AdditionalServiceInterface;

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