<?php

namespace App\Controller;

class ControllerVote extends GenericController
{
    public static function scrutinMajoritaire() : void
    {

        $parametres = array(
            'pagetitle' => 'Scrutin Majoritaire',
            'cheminVueBody' => 'vote/scrutinMajoritaire.php'
        );

        self::afficheVue('view.php', $parametres);
    }
}