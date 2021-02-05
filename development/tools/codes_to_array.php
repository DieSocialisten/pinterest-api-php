<?php

declare(strict_types=1);

$source = fopen('./codes.csv', 'r');

$data = [];
while (($line = fgetcsv($source)) != null) {
    $lineTrimmed = array_map('trim', $line);
    list($message, $code, $http) = $lineTrimmed;

    $data["{$code}_{$http}"] = $message;
}

var_export($data);

fclose($source);
