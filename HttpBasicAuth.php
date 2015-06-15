<?php
 
class HttpBasicAuth extends \Slim\Middleware
{
    /**
     * @var string
     */
    protected $realm;
 
    /**
     * Constructor
     *
     * @param   string  $realm      The HTTP Authentication realm
     */
    public function __construct($realm = 'Area restrita.')
    {
        $this->realm = $realm;
    }
    public function deny_access() {
        $res = $this->app->response();
        $res->status(401);
        $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));        
    }
 
    public function authenticate($username, $password) {
        if(!ctype_alnum($username))
            return false;
         
        if(isset($username) && isset($password)) {
            //$password = crypt($password);
            if($username === 'michael' && $password === 'root') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function call()
    {
        $req = $this->app->request();
        $res = $this->app->response();
        $authUser = $req->headers('PHP_AUTH_USER');
        $authPass = $req->headers('PHP_AUTH_PW');
         
        if ($this->authenticate($authUser, $authPass)) {
            $this->next->call();
        } else {
            $this->deny_access();
        }
    }
}  