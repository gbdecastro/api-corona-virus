<?php

header("Content-type:application/json");

include "php-cors/src/Cors.php";

new \Lz\PHP\Cors([
    'origin' => '*',
    'credentials' => true,
    'max-age' => 86400,
    'headers' => ['Content-Type', 'Accept', 'Origin', 'Authorization'],
    'methods' => ['GET'],
]);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $path = $_SERVER['REQUEST_URI'];

    if (preg_match("/\b(\/.+)\??(.+)?\b/", $path, $m)) {
        switch ($path) {
            case '/api/data/world':
                $data = file_get_contents('https://services1.arcgis.com/0MSEUqKaxRlEPj5g/arcgis/rest/services/ncov_cases/FeatureServer/1/query?f=json&where=Confirmed%20%3E%200&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outSR=102100&cacheHint=true');
                $data = json_decode($data, true);
                echo json_encode($data['features']);
                break;
            case '/api/data/brazil':
                $data = str_replace("var dados = ", "", file_get_contents('https://sigageomarketing.com.br/coronavirus/coronavirus.js'));
                $data = json_decode($data, true);
                echo json_encode($data['features']);
                break;
            default:
                echo json_encode([
                    "code" => "404",
                    "message" => "URL Request not found",
                ]);
                break;
        }        
    }
} else {
    echo json_encode([
        "code" => "404",
        "message" => "The method " . $_SERVER['REQUEST_METHOD'] . "not exists",
    ]);
}
