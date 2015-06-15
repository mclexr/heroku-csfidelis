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

$app->post('/v0/usuario', 'addUsuario');
$app->get('/v0/usuario', 'getUsuarios');
$app->get('/v0/usuario/:id', 'getUsuario');

$app->run();

function getUsuarios() {
    $stmt = getConnection()->query("SELECT * FROM usuario");
    $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo "{usuarios:".json_encode($usuarios)."}";
}

function getUsuario($id) {
    $conn = getConnection();
    $sql = "SELECT * FROM usuario WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $usuario = $stmt->fetchObject();
    echo json_encode($usuario);
}

function addUsuario()
{
    $request = \Slim\Slim::getInstance()->request();
    $usuario = json_decode($request->getBody());
    $sql = "INSERT INTO usuario (nome,email,senha) VALUES (:nome,:email, :senha)";
    $conn = getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bindParam("nome",$usuario->nome);
    $stmt->bindParam("email",$usuario->email);
    $stmt->bindParam("senha",$usuario->senha);
    $stmt->execute();
    $usuario->id = $conn->lastInsertId();
    echo json_encode($usuario);
}

function getConnection() {
    $dbhost= "ec2-54-163-238-169.compute-1.amazonaws.com";
    $dbuser= "anvvejvdyuqesa";
    $dbpass= "F4uqeo2nicDkdba1LAvZAhZ2Db";
    $dbname= "df9kukvtna79bp";
    $dbConnection = new PDO("pgsql:host=$dbhost;port=5432;dbname=$dbname;user=$dbuser;password=$dbpass"); 
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}
?>
