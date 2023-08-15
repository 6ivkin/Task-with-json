<?php

// src/Interfaces/AdditionalServiceInterface.php
namespace App\Interfaces;

interface AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int;
}