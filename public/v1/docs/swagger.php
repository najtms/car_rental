<?php

require __DIR__ . '/../../../vendor/autoload.php';

define('BASE_URL', 'http://localhost:8888/car_rental/');

error_reporting(0);

$openapi = \OpenApi\Generator::scan(['../../../rest/routes', './'], ['pattern' => '*.php']);
// $openapi = \OpenApi\Util::finder(['../../../rest/routes', './'], NULL, '*.php');
// $openapi = \OpenApi\scan(['../../../rest', './'], ['pattern' => '*.php']);

header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
