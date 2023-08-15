<?php

// src/App/Tariffs/HourlyTariff.php
namespace App\Tariffs;

use App\Interfaces\TariffInterface;

class HourlyTariff implements TariffInterface
{
    public function calculatePrice(array $data): int
    {
        // Проверить количество километров на отрицательное значение
//        if($data['km'] < 0) {
//            throw new \InvalidArgumentException('Количество километров не может быть отрицательным');
//        }

        // Проверить время, не может быть меньше 1 часа
//        if($data['minutes'] / 60 < 1) {
//            throw new \InvalidArgumentException('Время не может быть меньше 1 часа');
//        }

        // Проверить количество километров на наличие отрицательных значений
        return 200 * round($data['minutes'] / 60);
    }
}