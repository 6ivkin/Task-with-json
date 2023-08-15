<?php

// src/Interfaces/TariffInterface.php
namespace App\Interfaces;

interface TariffInterface
{
    public function calculatePrice(array $data): int;
}