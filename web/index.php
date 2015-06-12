<?php
require('../vendor/autoload.php');
$app = new \Slim\Slim();

$app->get('/', function () {
    echo "Oi, o sistema está funcionando como esperado!";
});

$app->get('/:name', function ($name) {
    echo "Oi $name, o sistema está funcionando como esperado!";
});

$app->run();
?>

