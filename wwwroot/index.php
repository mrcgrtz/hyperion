<?php

require(dirname(__FILE__) . '/../vendor/autoload.php');

use Marcgoertz\Hyperion\Parser as Hyperion;

header('Content-Type: application/json; charset=utf-8');
$get = filter_var_array($_GET, FILTER_SANITIZE_STRING);
if (isset($get['url'])) {
    $hyperion = new Hyperion($get['url']);
    if ($hyperion->hasMetadata()) {
        http_response_code(200);
        print $hyperion->toJSON();
        exit();
    }
}

http_response_code(400);
