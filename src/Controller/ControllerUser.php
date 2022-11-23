<?php

namespace App\Controller;

use App\Model\DataObject\User;
use App\Model\HTTP\Cookie;
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
        $prenom = $_POST['prenom'];
        $nom = $_POST['nom'];

        $user = new User($idUser, $mdp,$prenom,$nom);

        $userRepository = new UserRepository();



        if ($userRepository->checkCmdp($mdp, $cmdp)          //check si aucune contrainte n'a été violée
            && $userRepository->checkId($idUser)
            && $userRepository->checkMdp($mdp) == 'true')
        {
            $userRepository->sauvegarder($user);
            $parametres = array(
                'pagetitle' => 'Inscription validée !',
                'cheminVueBody' => 'user/accueil.php',
            );
        }
        else
        {
            if (!$userRepository->checkCmdp($mdp, $cmdp)) {

                $parametres = array(
                    'pagetitle' => 'Erreur',
                    'cheminVueBody' => 'user/inscription.php',
                    'persistanceValeurs' => array('idUser' => $idUser,
                                                    'nom' => $nom,
                                                    'prenom' => $prenom),
                    'msgErreur' =>  'Les mots de passes doivent être identiques.'
                );

            }
            if (!$userRepository->checkId($idUser)) {
                $parametres = array(
                    'pagetitle' => 'Erreur',
                    'cheminVueBody' => 'user/inscription.php',
                    'persistanceValeurs' => array('nom' => $nom,
                                                    'prenom' => $prenom),
                    'msgErreur' =>  'L\'identifiant '.$idUser.' est déjà utilisé.'
                );
            }
            if ($userRepository->checkMdp($mdp)  != 'true') {

                $parametres = array(
                    'pagetitle' => 'Erreur',
                    'cheminVueBody' => 'user/inscription.php',
                    'persistanceValeurs' => array('idUser' => $idUser,
                                                    'nom' => $nom,
                                                    'prenom' => $prenom),
                    'msgErreurMdp' =>  $userRepository->checkMdp($mdp)
                );



            }

        }

        self::afficheVue('view.php', $parametres);
    }

        /*A FAIRE
        fonctions update et updated
        fonction delete
        fonction readall ?
        */


    /*
    public static function deposerCookie(string $nomCookie, string $valeurCookie,?int $duree = null) : void
    {
        Cookie::enregistrer($nomCookie,$valeurCookie,$duree);
    }

    public static function lireCookie(string $nomCookie) : void
    {
        echo Cookie::lire($nomCookie);
    }
    */









}
