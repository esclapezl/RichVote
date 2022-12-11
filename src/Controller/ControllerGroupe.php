<?php

namespace App\Controller;

use App\Model\Repository\GroupeRepository;

class ControllerGroupe extends GenericController
{
    public static function test(){
        $groupe = (new GroupeRepository())->select('test');

        var_dump($groupe->getIdMembres());
    }
}