<?php

namespace App\Controller;

use App\Model\DataObject\User;
use App\Model\HTTP\Cookie;
use App\Model\Repository\UserRepository;
use App\Model\HTTP\Session;


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

        //$identifiant = $_POST['identifiant'];
        //$mdp = $_POST['motDePasse'];
        self::afficheVue('view.php',[
            "pagetitle" => "Inscription",
            "cheminVueBody" => 'user/inscription.php'
        ]);
    }

       public static function created() : void
    {
        $intitule = $_POST['titreQuestion'];
        $nbSections = $_POST['nbSections'];

        $question = new Question(null, $intitule, 'description');
        $question = (new QuestionRepository)->sauvegarder($question);

        for($i=0; $i<$nbSections; $i++){
            $section = new Section(null, $question->getId(), "section n°$i", 'description');
            (new SectionRepository())->sauvegarder($section);
        }

        $parametres = array(
            'pagetitle' => 'continuer la création de la question',
            'cheminVueBody' => 'question/update.php',
            'question' => (new QuestionRepository())->select($question->getId())
        );

        self::afficheVue('view.php', $parametres);
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

        $user = new User($idUser, $mdp,$prenom,$nom,'invité');

        $userRepository = new UserRepository();



        if ($userRepository->checkCmdp($mdp, $cmdp)          //check si aucune contrainte n'a été violée
            && $userRepository->checkId($idUser))
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
            /*
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
            */

        }

        self::afficheVue('view.php', $parametres);
    }

    public static function readAll() : void
    {
        $arrayUser = (new UserRepository())->selectAll();

        $parametres = array(
            'pagetitle' => 'Liste Utilisateurs',
            'cheminVueBody' => 'user/list.php',
            'users' => $arrayUser
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function read():void
    {

        $user = (new UserRepository())->select($_GET['id']);

        $parametres = array(
            'pagetitle' => 'Détails user',
            'cheminVueBody' => 'user/details.php',
            'user' => $user
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function update():void
    {
        $user = (new UserRepository())->select($_GET['id']);
        $parametres = array(
            'pagetitle' => 'Mettre à jour un utilisateur',
            'cheminVueBody' => 'user/update.php',
            'user' => $user
        );

        self::afficheVue('view.php', $parametres);
    }






    /*A FAIRE
    fonctions update et updated
    fonction delete
    fonction readall ? Pour Admin et Organisateur quand il souhaite choisir ses votants
    */












}
