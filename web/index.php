<?php
require('../vendor/autoload.php');
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');

$app->get('/', function () {
    echo "Oi, o sistema está funcionando como esperado!";
});

$app->get('/:name', function ($name) {
    echo "Oi $name, o sistema está funcionando como esperado!";
});

$app->get('/base/usuarios', 'getUsuarios');

$app->run();

function getConnection() {
    $dbopts = parse_url(getenv('DATABASE_URL'));
    $dbhost= $dbopts["host"];
    $dbuser= $dbopts["user"];
    $dbpass= $dbopts["pass"];
    $dbname=ltrim($dbopts["path"],'/');
    $dbConnection = new PDO("pgsql:dbname=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}

function getUsuarios() {
    $stmt = getConnection()->query("SELECT * FROM public.'Usuarios'");
    $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo "{usuaios:".json_encode($usuarios)."}";
}


?>

