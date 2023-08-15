<?php

// src/App/Tariffs/DailyTariff.php
namespace App\Tariffs;

use App\Interfaces\TariffInterface;

class DailyTariff implements TariffInterface
{
    public function calculatePrice(array $data): int
    {
        // Проверить количество километров на наличие отрицательных значений
//        if ($data['km'] < 0) {
//            throw new \InvalidArgumentException('Количество километров не может быть отрицательным');
//        }

        // Проверить время на значение, не должно быть меньше 1 дня
//        if(round($data['minutes'] / 60) < 24){
//            throw new \InvalidArgumentException('Время не может быть меньше одного дня');
//        }

        // Расчет цены
        return 1000 * round(($data['minutes'] / 60) / 24);
    }
}