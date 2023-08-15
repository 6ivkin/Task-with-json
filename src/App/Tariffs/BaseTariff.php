<?php

// src/App/Tariffs/BaseTariff.php
namespace App\Tariffs;

use App\Interfaces\TariffInterface;

class BaseTariff implements TariffInterface
{
    public function calculatePrice(array $data): int
    {
        // Проверить количество километров на наличие отрицательных значений
//        if ($data['km'] < 0) {
//            throw new \InvalidArgumentException('Количество километров не может быть отрицательным');
//        }

        // Проверить время на отрицательное значение
//        if ($data['minutes'] < 0) {
//            throw new \InvalidArgumentException('Время не может быть отрицательным');
//        }

        // Расчет цены
        return ($data['km'] * $data['rate']) + (3 * $data['minutes']);
    }
}