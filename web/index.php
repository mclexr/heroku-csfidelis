<?php
require('../vendor/autoload.php');
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');

$app->get('/', function () {
    echo "Oi, o sistema está funcionando como esperado! =]";
});

$app->get('/:name', function ($name) {
    echo "Oi $name, o sistema está funcionando como esperado! =]";
});

$app->get('/v0/usuario', 'getUsuarios');

$app->run();

function getConnection() {
    $dbhost= "ec2-54-163-238-169.compute-1.amazonaws.com";
    $dbuser= "anvvejvdyuqesa";
    $dbpass= "F4uqeo2nicDkdba1LAvZAhZ2Db";
    $dbname= "df9kukvtna79bp";
    $dbConnection = new PDO(getenv('DATABASE_URL')); 
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}

function getUsuarios() {
    $stmt = getConnection()->query("SELECT * FROM usuario");
    $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo "{usuarios:".json_encode($usuarios)."}";
}


?>
