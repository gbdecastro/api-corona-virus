<?php

header("Content-type:application/json");

include("php-cors/src/Cors.php");

new \Lz\PHP\Cors([
    'origin'      => '*',
    'credentials' => true,
    'max-age'     => 86400,
    'headers'     => ['Content-Type', 'Accept', 'Origin', 'Authorization'],
    'methods'     => ['GET']
]);

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(preg_match("/\bindex.php\/(.+)\b/", $_SERVER['PHP_SELF'], $matched)){
        switch ($matched[1]) {
            case 'api/data/world':
                $data = str_replace("var database=","",file_get_contents('http://plataforma.saude.gov.br/novocoronavirus/resources/scripts/database.js'));
                $data[strlen($data)] = "";
                $data = json_decode($data,true);
                echo json_encode($data['world'][count($data['world'])-1]);
                break;
            case 'api/data/brazil':
                $data = str_replace("var database=","",file_get_contents('http://plataforma.saude.gov.br/novocoronavirus/resources/scripts/database.js'));
                $data[strlen($data)] = "";
                $data = json_decode($data,true);
                echo json_encode($data['brazil'][count($data['brazil'])-1]);                
                break;
            default:
                echo json_encode([
                    "code" => "404",
                    "message" => "URL Request not found"
                ]);
                break;
        }  
    }else{
        echo json_encode([
            "code" => "404",
            "message" => "URL Request  not found"
        ]);
    }
}else{
    echo json_encode([
        "code" => "404",
        "message" => "The method ". $_SERVER['REQUEST_METHOD']. "not exists"
    ]);    
}