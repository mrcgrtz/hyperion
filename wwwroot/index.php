<?php

namespace Hyperion;

require(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

use ogp\Parser as OgpParser;
use Mf2 as Mf2Parser;

$get = filter_var_array($_GET, FILTER_SANITIZE_STRING);

if (isset($get['url'])) {
    $html = file_get_contents($get['url']);

    if ($html !== false) {
        $ogp = OgpParser::parse($html);
        $mf2 = Mf2Parser\parse($html);

        header('Content-Type: application/json; charset=utf-8');
        if (count($ogp) > 0 || count($mf2['items']) > 0 || count($mf2['rels']) > 0 || count($mf2['rel-urls']) > 0) {
            http_response_code(200);
            print json_encode($metadata);
            exit();
        }
    }
}

http_response_code(400);
