<?php
namespace App\Controller;
use App\Model\Repository\DatabaseConnection as DataBaseConnection;
use mysql_xdevapi\DatabaseObject;

class Controller{
    public static function test()
    {
        echo "ah";
    }

    public static function readAll(){
        echo "read All";
        DataBaseConnection::getInstance()::getPdo();
    }
}
?>
