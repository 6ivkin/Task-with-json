<?php

function convertToJson(array $data, $filename): void
{
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($filename, $json);
}