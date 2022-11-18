<?php

namespace App\Controller;

use App\Model\DataObject\User;
use App\Model\Repository\UserRepository;

class ControllerUser
{

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }

    public static function accueil()
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Accueil",
            "cheminVueBody" => 'user/accueil.php'
        ]);
    }

    public static function error()
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Erreur",
            "cheminVueBody" => 'error.php'
        ]);
    }

    public static function inscription()
    {

        self::afficheVue('view.php',[
            "pagetitle" => "Inscription",
            "cheminVueBody" => 'user/inscription.php'
        ]);
    }

    public static function connexion()
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Connexion",
            "cheminVueBody" => 'user/connexion.php'
        ]);
    }

    public static function inscrit() : void
    {
        $idUser = $_POST['identifiant'];
        $mdp = $_POST['motDePasse'];
        $cmdp = $_POST['confirmerMotDePasse'];

        $user = new User($idUser, $mdp);


        if ((new UserRepository())->check($mdp, $cmdp)) {
            (new UserRepository())->sauvegarder($user);

            $parametres = array(
                'pagetitle' => 'Utilisateur Inscrit',
                'cheminVueBody' => 'user/accueil.php',
            );

            self::afficheVue('view.php', $parametres);
        } else {
            $parametres = array(
                'pagetitle' => 'Erreur',
                'cheminVueBody' => 'user/inscription.php',
                'persistanceId' => $idUser,
                'msgErreur' => 'Les mots de passes doivent être identiques.'
            );

            self::afficheVue('view.php', $parametres);
        }
    }

        /*A FAIRE
        fonctions update et updated
        fonction delete
        fonction readall ?
        */

    public static function deposerCookie(string $nomCookie, string $ValeurCookie,int $duree) : void
    {
        setcookie($nomCookie, $nomCookie, time() + $duree);
    }

    public static function lireCookie(string $nomCookie) : void
    {
        echo $_COOKIE[$nomCookie];
    }









}
