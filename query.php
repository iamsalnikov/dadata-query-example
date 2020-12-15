<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

include __DIR__ . "/vendor/autoload.php";

$host = getenv("DADATA_HOST");
if (!$host) {
    echo "Не указан адрес дадаты. Указать его можно с помощью переменной окружения DADATA_HOST\n";
    exit(1);
}

$client = new Client(["base_uri" => $host]);

$options = [
    "headers" => [
        "Content-Type" => "application/json",
        "Accept" => "application/json",
    ],
    "body" => json_encode([
        "locations" => [
            ["country" => "Россия"],
            ["country" => "Казахстан"],
            ["country" => "Армения"]
        ],
        "query" => "Петровка, 2",
        "restrict_value" => false
    ]),
];

$token = getenv("DADATA_TOKEN");
if ($token) {
    $options["headers"]["Authorization"] = sprintf("Token %s", $token);
}

try {
    $response = $client->request("POST", "/suggestions/api/4_1/rs/suggest/address", $options);
} catch (ClientException $e) {
    $response = $e->getResponse();
}

if ($response === null) {
    echo "Не удалось получить ответ от сервиса\n";
    exit(1);
}

echo sprintf("Response status: %d\n", $response->getStatusCode());
$raw = $response->getBody()->getContents();
echo sprintf("Response body:\n%s\n", $raw);
