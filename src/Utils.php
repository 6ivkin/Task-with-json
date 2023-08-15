<?php

// src/Utils.php
namespace App;

class Utils
{
    public static function sanitizeNumber($inputName)
    {
        return filter_input(INPUT_POST, $inputName, FILTER_SANITIZE_NUMBER_INT);
    }

    // Add other utility functions here
}