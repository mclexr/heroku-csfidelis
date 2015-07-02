<?php
namespace App\Provider;

    use App\Provider\DoctrineProvider;
    use App\Model\Usuario;

class TokenProvider {

    private $tokenKey;
    private $entityManager;

	public function __construct() {
        $this->entityManager = DoctrineProvider::getEntityManager();
        $this->tokenKey = getenv("TOKEN_KEY");
    }

    public function getToken($email, $password) {
        if(!isset($email) || !isset($password)) {
            throw new \Exception("Problemas na autenticação.", 401);
        }

        $usuario = $this->entityManager
        ->getRepository("App\Model\Usuario")
        ->findOneBy(array('email' => $email));

        if(!isset($usuario)) {
            throw new \Exception("Problemas na autenticação.", 401);
        }

        if(password_verify($password, $usuario->getSenha())) {
            return $this->gerarToken($usuario);
        }
         throw new \Exception("Problemas na autenticação.", 401);
    }

    public function verificarToken() {
        $headers = apache_request_headers();

        //Caso nao encontre o header "Authorization não continua
        if(!isset($headers["AUTHORIZATION"])) {
            throw new \Exception("Token não informado.", 401);
        }

        //Retiro a palavra Bearer do header
        $token = str_replace(array("Bearer", " "), "", $headers["AUTHORIZATION"]);

        //Verifico o token
        $decoded = \JWT::decode($token,  $this->tokenKey, array('HS256'));
        return $this->tokenResponse($token, $decoded->exp);

    }
    private function gerarToken($usuario){
        $dataToken = time();
        $token = array(
            "iss" => $usuario->getEmail(),
            "iat" => $dataToken,
            "exp" => $dataToken + 10800,
            "aud" => "http://csfidelis.com.br/api/v1/auth/verify",
        );
        $tokenCode = \JWT::encode($token,  $this->tokenKey);

        return $this->tokenResponse($tokenCode, $token["exp"]);
    }

    private function tokenResponse($tokenCode, $expiration) {
         $tokenResponse = array(
                "token_type" => "bearer",
                "access_token" => $tokenCode,
                "expiration" => $expiration
            );
        return "{\"token\":" . json_encode($tokenResponse, JSON_PRETTY_PRINT) . "}";
    }


}
?>
