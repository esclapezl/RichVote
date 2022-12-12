<?php

namespace App\Controller;

use App\Lib\ConnexionUtilisateur;
use App\Lib\MessageFlash;
use App\Model\DataObject\Groupe;
use App\Model\Repository\GroupeRepository;
use App\Model\Repository\UserRepository;

class ControllerGroupe extends GenericController
{
    public static function test(){
        $groupe = (new GroupeRepository())->select('test');

        var_dump($groupe->getIdMembres());
    }

    public static function select(){
        $nomGroupe = $_GET['nomGroupe'];
        $groupe = (new GroupeRepository())->select($nomGroupe);

        $params=[
            'pagetitle' => 'Details groupe',
            'cheminVueBody' => '/groupe/detail.php',
            'groupe' => $groupe
        ];
        self::afficheVue('view.php', $params);
    }

    public static  function create(){
        $params = [
            'pagetitle' => 'créez votre groupe',
            'cheminVueBody' => '/groupe/create.php'
        ];
        self::afficheVue('view.php', $params);
    }

    public static function created(){
        if(ConnexionUtilisateur::estConnecte()){
            $nomGroup = $_POST['nomGroupe'];
            $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            $groupe = new Groupe($nomGroup, $idUser);
            (new GroupeRepository())->sauvegarder($groupe);

            $params = [
                'pagetitle' => 'detail groupe',
                'cheminVueBody' => '/groupe/detail.php',
                'groupe' => $groupe
            ];
            self::afficheVue('view.php', $params);
        }
        else{
            MessageFlash::ajouter('warning', 'veuillez vous connecter');

            $params = [
                'pagetitle' => 'accueil',
                'cheminVueBody' => '/user/accueil.php'
            ];
            self::afficheVue('view.php', $params);
        }
    }

    public static function addUserToGroupe(){
        $nomGroupe = $_GET['nomGroupe'];
        $groupe = (new GroupeRepository())->select($nomGroupe);
        if (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $groupe->getIdResponsable()) {
            $action = 'frontController.php?controller=groupe&action=usersAddedToGroupe&nomGroupe='.$nomGroupe;
            $users = (new UserRepository())->selectAll();
            $params = [
                'pagetitle' => 'ajouter membres groupe',
                'cheminVueBody' => '/user/listPourAjouter.php',
                'action' => $action,
                'users' => $users
            ];
            self::afficheVue('view.php', $params);
        }
    }

    public static function usersAddedToGroupe()
    {
        $nomGroupe = $_GET['nomGroupe'];
        $groupe = (new GroupeRepository())->select($nomGroupe);
        if (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $groupe->getIdResponsable()) {
            if (isset($_POST['user'])) {
                foreach ($_POST['user'] as $idUser) {
                    $groupe->addUser($idUser);
                }
            }
        }

        (new GroupeRepository())->update($groupe);

        $params = [
            'pagetitle' => 'detail groupe',
            'cheminVueBody' => '/groupe/detail.php',
            'groupe' => $groupe
        ];
        self::afficheVue('view.php', $params);
    }
}