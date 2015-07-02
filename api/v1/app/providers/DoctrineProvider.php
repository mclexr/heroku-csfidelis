<?php
namespace App\Provider;

    use Doctrine\ORM\Tools\Setup;
    use Doctrine\ORM\EntityManager;

class DoctrineProvider {

    private static $entityManager;

    private static function createEntityManager() {

    //onde irão ficar as entidades do projeto? Defina o caminho aqui
    $entidades = array(
        'Usuario' => 'app/models/Usuario.php',
        'Empresa' => 'app/models/Empresa.php',
        'Funcao' => 'app/models/Funcao.php',
        'Funcionario' => 'app/models/Funcionario.php',
        'Treinamento' => 'app/models/Treinamento.php',
        'TipoTreinamento' => 'app/models/TipoTreinamento.php',
        'FuncionarioTreinamento' => 'app/models/FuncionarioTreinamento.php',
    );

    $isDevMode = true;
    
    $baseParams = array(
        'driver' => 'pdo_pgsql',
        'host' => 'ec2-54-163-238-169.compute-1.amazonaws.com',
        'port' => 5432,
        'user' =>  'anvvejvdyuqesa',
        'password' => 'F4uqeo2nicDkdba1LAvZAhZ2Db',
        'dbname' => 'df9kukvtna79bp'
    );

        //setando as configurações definidas anteriormente
        $config = Setup::createAnnotationMetadataConfiguration($entidades, $isDevMode);

        //Retorna uma instância do EntityManager
        return EntityManager::create($baseParams, $config);
    }

    public static function getEntityManager() {
        if(!isset(self::$entityManager)) {
            self::$entityManager = self::createEntityManager();
        }
        return self::$entityManager;
    }

}

