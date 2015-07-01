<?php
require_once "../../vendor/autoload.php";
require_once "request_headers.php";
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

//Imports =======================================================
use Flynsarmy\SlimMonolog\Log\MonologWriter;
use Monolog\Handler\StreamHandler;
use App\Helper\Utils;

use App\Middleware\HeaderMiddleware;
use App\Middleware\TokenMiddleware;
use App\Middleware\RequestMiddleware;

//Configs =======================================================
$logger = new MonologWriter(array(
    'handlers' => array(
        new StreamHandler("app/logs/".date('Y-m-d').".log"),
)));

$app = new \Slim\Slim();
$app->config('debug', false);

$app->log->setEnabled(true);
$app->log->setWriter($logger);
$app->log->setLevel(\Slim\Log::WARN);


$app->add(new HeaderMiddleware());
$app->add(new TokenMiddleware());
$app->add(new RequestMiddleware());

$app->map('/', function() use($app){
    $info = array("Ip" => $app->request->getIp(),
                  "Método" => $app->request->getMethod(),
                  "Agente" => $app->request->getUserAgent(),
                  "URL" => $app->request->getUrl(),
                  "Protocolo" => $app->request->getScheme(),
                  "Envio: " => $app->request->getBody()
    );
    $app->response->setBody("{\"CSFidelis\":" . json_encode($info, JSON_PRETTY_PRINT) . "}");
})->via('ANY');



//Error handlers ================================================
$app->hook('errorHandler', function ($e) use ($app) {
    $app->response->headers->set('Content-Type','application/json');

    if($e->getCode() === 401) {
        $app->response->setStatus(401);
    }

    if($e->getCode() >= 500) {
        $errorLog = sprintf("[Mensagem: %s][Arquivo: %s][Linha: %s]", $e->getMessage(), $e->getFile(), $e->getLine());
        $app->log->warn($errorLog);
    }

    $error = array("code" => $e->getCode(),
                   "message" => $e->getMessage());

    $app->response->setBody("{\"error\":" . json_encode($error, JSON_PRETTY_PRINT) . "}");
});

$app->error(function (\Exception $e) use ($app) {
   $app->applyHook('errorHandler', $e);

});

$app->notFound(function() use($app) {
    $error = array("code" => "404", "message" => "Nada encontrado.");
    echo("{\"error\":" . json_encode($error, JSON_PRETTY_PRINT) . "}");

});

//Routes ========================================================
require_once "app/routes/TokenRoutes.php";
require_once "app/routes/UsuarioRoutes.php";
require_once "app/routes/EmpresaRoutes.php";
require_once "app/routes/FuncaoRoutes.php";
require_once "app/routes/FuncionarioRoutes.php";
require_once "app/routes/TreinamentoRoutes.php";
require_once "app/routes/TipoTreinamentoRoutes.php";

$app->run();
?>
