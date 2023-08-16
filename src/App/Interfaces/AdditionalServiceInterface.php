<?php


interface AdditionalServiceInterface
{
    public function calculateAdditionalCost(array $data): int;
}