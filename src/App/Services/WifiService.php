<?php

// src/App/Services/WifiService.php
namespace App\Services;

use App\Interfaces\AdditionalServiceInterface;

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