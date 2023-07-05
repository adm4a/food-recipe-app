<?php
require("vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['./rest/routes']);

header('Content-Type: application/json');
echo $openapi->toJson();