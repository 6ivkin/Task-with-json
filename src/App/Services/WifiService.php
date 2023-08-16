<?php

class WifiService implements AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int
    {
        // Расчет цены за wifi
        return 15 * round($data['minutes'] / 60);
    }
}