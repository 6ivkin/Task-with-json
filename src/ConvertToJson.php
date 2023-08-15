<?php

// src/ConvertToJson.php
namespace App;

class ConvertToJson
{
    public static function toJson(array $data)
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}