<?php

class DriverService implements AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int
    {
        return 100;
    }
}