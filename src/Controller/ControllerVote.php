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
    public static function consultation() : void
    {

        $parametres = array(
            'pagetitle' => 'Consultation',
            'cheminVueBody' => 'vote/consultation.php'
        );

        self::afficheVue('view.php', $parametres);
    }
}