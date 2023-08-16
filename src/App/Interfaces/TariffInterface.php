<?php

interface TariffInterface
{
    public function calculatePrice(array $data): int;
}