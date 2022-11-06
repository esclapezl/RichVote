<?php
namespace App\Model\Repository;
use \App\Config\Conf as Conf;
use \PDO as PDO;

class DatabaseConnection{
    private static ?DatabaseConnection $instance = null;

    private PDO $pdo;

    private function __construct()
    {
        $hostname = Conf::getHostname();
        $databaseName = Conf::getDatabase();
        $login = Conf::getLogin();
        $password = Conf::getPassword();

        // Oracle
        $this->pdo = new PDO("oci:dbname=//orainfo.iutmontp.univ-montp2.fr:1521/IUT;charset=UTF8", $login, $password);

        // Connexion à la base de données
        // Le dernier argument sert à ce que toutes les chaines de caractères
        // en entrée et sortie de MySql soit dans le codage UTF-8
//        $this->pdo = new PDO("mysql:host=$hostname;dbname=$databaseName", $login, $password,
//            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        // On active le mode d'affichage des erreurs, et le lancement d'exception en cas d'erreur
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @return PDO
     */
    public static function getPdo() : PDO
    {
        return static::$instance->pdo;
    }

    public static function getInstance() : ?DatabaseConnection{
        if (is_null(static::$instance))
            static::$instance = new DatabaseConnection();
        return static::$instance;
    }

}
?>