<?php

// src/App/Tariffs/StudentTariff.php
namespace App\Tariffs;

use App\Interfaces\TariffInterface;

class StudentTariff implements TariffInterface
{
    public function calculatePrice(array $data): int
    {
        // Проверить количество километров на отрицательное значение
//        if($data['km'] < 0) {
//            throw new \InvalidArgumentException('Количество километров не может быть отрицательным');
//        }

        // Расчет цены
        return (1 * $data['minutes']) + (4 + $data['km']);
    }
}