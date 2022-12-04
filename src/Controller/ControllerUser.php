<?php

namespace App\Controller;

use App\Lib\ConnexionUtilisateur;
use App\Lib\MessageFlash;
use App\Model\DataObject\User;
use App\Model\HTTP\Cookie;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\UserRepository;
use App\Model\HTTP\Session;


class ControllerUser extends GenericController
{
    public static function accueil()
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Accueil",
            "cheminVueBody" => 'user/accueil.php'
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

    public static function connected()
    {
        $id = $_POST['id'];
        $mdp =  $_POST['mdp'];

        $userRepository = (new UserRepository());
        $user = $userRepository->select($id);
        if($user != null
        && $userRepository->checkCmdp($user->getMdpHache(),$userRepository->setMdpHache($mdp)))
        {
            $connexion = (new ConnexionUtilisateur());
            $connexion->connecter($id);

            MessageFlash::ajouter('info', 'Connecté.');
            self::redirection('frontController.php?controller=user&action=accueil');
        }
        else
        {
            if($user == null)
            {
                $parametres = array(
                    'pagetitle' => 'Erreur',
                    'cheminVueBody' => 'user/connexion.php',
                    'msgErreurId' =>  "Cet utilisateur n'existe pas."
                );
            }
            else if(!$userRepository->checkCmdp($user->getMdpHache(),$userRepository->setMdpHache($mdp)))
            {
                $parametres = array(
                    'pagetitle' => 'Erreur',
                    'cheminVueBody' => 'user/connexion.php',
                    'msgErreurMdp' =>  "Mot de passe incorrect."
                );
            }
        }
        self::afficheVue('view.php', $parametres);


    }

    public static function inscrit() : void
    {
        $idUser = $_POST['identifiant'];
        $mdp = $_POST['motDePasse'];
        $cmdp = $_POST['confirmerMotDePasse'];
        $prenom = $_POST['prenom'];
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $estAdmin = false;

        $userRepository = new UserRepository();
        $user = new User($idUser, $userRepository->setMdpHache($mdp), $prenom, $nom, 'invité', $estAdmin, $email);

        if ($userRepository->checkCmdp($mdp, $cmdp)          //check si aucune contrainte n'a été violée
            && $userRepository->checkId($idUser)
            && $userRepository->checkEmail($email))
        {
            $connexion = new ConnexionUtilisateur();
            $connexion->connecter($idUser);
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
            if(!$userRepository->checkEmail($email))
            {
                $parametres = array(
                    'pagetitle' => 'Erreur',
                    'cheminVueBody' => 'user/inscription.php',
                    'persistanceValeurs' => array('idUser' => $idUser,
                        'nom' => $nom,
                        'prenom' => $prenom),
                    'msgErreur' =>  'L\'email '.$email.' est déjà utilisé.'
                );
            }
            if (!$userRepository->checkId($idUser)) {
                $parametres = array(
                    'pagetitle' => 'Erreur',
                    'cheminVueBody' => 'user/inscription.php',
                    'persistanceValeurs' => array('nom' => $nom,
                                                    'prenom' => $prenom,
                        'email'=> $email),
                    'msgErreur' =>  'L\'identifiant '.$idUser.' est déjà utilisé.'
                );
            }

        }
        self::afficheVue('view.php', $parametres);
    }



    public static function readAll() : void
    {
        if (isset($_POST['title']) AND !empty($_POST['title'])){
            $recherche= strtolower(htmlspecialchars($_POST['title']));
            $arrayUser = (new UserRepository())->search($recherche);
        }
        else{
            $arrayUser = (new UserRepository())->selectAll();
        }


        $parametres = array(
            'pagetitle' => 'Liste Utilisateurs',
            'cheminVueBody' => 'user/list.php',
            'users' => $arrayUser
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function readAllSelect() : void
    {
        if (isset($_POST['title']) AND !empty($_POST['title'])){
            $recherche= strtolower(htmlspecialchars($_POST['title']));
            $arrayUser = (new UserRepository())->search($recherche);
        }
        else{
            $arrayUser = (new UserRepository())->selectAll();
        }


        $parametres = array(
            'pagetitle' => 'Liste Utilisateurs',
            'cheminVueBody' => 'user/listOrganisateur.php',
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
        if((new UserRepository())->select($_GET['id']) != null)
        {
            $user = (new UserRepository())->select($_GET['id']);


            if(!(new ConnexionUtilisateur())->estUtilisateur($user->getId()) && !(new ConnexionUtilisateur())->estAdministrateur())
            {
                MessageFlash::ajouter('danger', "Vous n'êtes pas autorisé à acceder à cette page.");
                self::redirection('frontController.php?controller=user&action=readAll');
            }

            $parametres = array(
                'pagetitle' => 'Mettre à jour un utilisateur',
                'cheminVueBody' => 'user/update.php',
                'user' => $user
            );

            self::afficheVue('view.php', $parametres);
        }
        else
        {
            MessageFlash::ajouter('warning', "Cet utilisateur n'existe pas.");
            self::redirection('frontController.php?controller=user&action=readAll');
        }
    }

    public static function updated() : void
    {
        $userRepository = new UserRepository();
        $user = $userRepository->select($_GET['id']);

        if(isset($_POST['aMdp']))
        {
            $aMdp = $_POST['aMdp'];
            $nMdp = $_POST['nMdp'];
            $cNMdp = $_POST['cNMdp'];

            if ($userRepository->checkCmdp($nMdp, $cNMdp) &&
                $userRepository->checkCmdp($userRepository->setMdpHache($aMdp), $user->getMdpHache()))
            {
                $user->setMdp($nMdp);
                $userRepository->update($user);
                $parametres = array(
                    'pagetitle' => 'Mot de passe mis à jour.',
                    'cheminVueBody' => 'user/accueil.php',
                );
            }
            else
            {
                if (!$userRepository->checkCmdp($nMdp, $cNMdp)) {

                    $parametres = array(
                        'pagetitle' => 'Erreur',
                        'cheminVueBody' => 'user/update.php',
                        'msgErreur' =>  'Les mots de passes doivent être identiques.',
                        'user' => $user
                    );

                }
                if (!$userRepository->checkCmdp($userRepository->setMdpHache($aMdp), $user->getMdpHache())) {
                    $parametres = array(
                        'pagetitle' => 'Erreur',
                        'cheminVueBody' => 'user/update.php',
                        'msgErreur' =>  'L\'ancien mot de passe ne correspond pas.',
                        'user' => $user);
                }
            }
        }
        else if(isset($_POST['identifiant']))
        {
            if($userRepository->checkId($_POST['identifiant']))
            {

                $user->setId($_POST['identifiant']);

                $userRepository->update($user);
                MessageFlash::ajouter('info', 'Identifiant mis à jour.');
                self::redirection('frontController.php?controller=user&action=read&id='.$user->getId());
            }
            else
            {
                MessageFlash::ajouter('danger', 'Identifiant déjà utilisé.');
                self::redirection('frontController.php?controller=user&action=read&id='.$user->getId().'&modif=identifiant');
            }
        }
        else if(isset($_POST['nom']))
        {
            $user->setNom($_POST['nom']);
            $userRepository->update($user);
            MessageFlash::ajouter('info', 'Nom mis à jour.');
            self::redirection('frontController.php?controller=user&action=read&id='.$user->getId());
        }
        else if(isset($_POST['prenom']))
        {
            $user->setPrenom($_POST['prenom']);
            $userRepository->update($user);
            MessageFlash::ajouter('info', 'Prenom mis à jour.');
            self::redirection('frontController.php?controller=user&action=read&id='.$user->getId());
        }
        else if(isset($_POST['email']))
        {
            $user->setEmail($_POST['email']);
            $userRepository->update($user);
            MessageFlash::ajouter('info', 'Prenom mis à jour.');
            self::redirection('frontController.php?controller=user&action=read&id='.$user->getId());
        }
        self::afficheVue('view.php', $parametres);
    }

    public static function deconnexion()
    {
        (new ConnexionUtilisateur())->deconnecter();
        MessageFlash::ajouter('info', 'Deconnecté.');
        self::redirection('frontController.php?controller=user&action=accueil');
    }

    public static function delete()
    {
        if((new UserRepository())->select($_GET['id']) != null)
        {
            $user = (new UserRepository())->select($_GET['id']);
            if(!(new ConnexionUtilisateur())->estUtilisateur($user->getId()) && !(new ConnexionUtilisateur())->estAdministrateur())
            {
                MessageFlash::ajouter('danger', "Vous n'êtes pas autorisé à acceder à cette page.");
                self::redirection('frontController.php?controller=user&action=readAll');
            }

            $parametres = array(
                'pagetitle' => 'Suppression du compte',
                'cheminVueBody' => 'user/delete.php',
                'user' => $user);
            self::afficheVue('view.php', $parametres);
        }
        else
        {
            MessageFlash::ajouter('warning', "Cet utilisateur n'existe pas.");
            self::redirection('frontController.php?controller=user&action=readAll');
        }
    }

    public static function deleted()
    {
        if(!(new ConnexionUtilisateur())->estAdministrateur())
        {
            $mdp = $_POST['mdp'];
            $cMdp = $_POST['cMdp'];
        }
        $userRepository = new UserRepository();
        $user = $userRepository->select($_GET['id']);

        if ((new ConnexionUtilisateur())->estAdministrateur()||($userRepository->checkCmdp($mdp, $cMdp)
        &&$userRepository->checkCmdp($user->getMdpHache(), $userRepository->setMdpHache($mdp))))
        {
            $userRepository->delete($user->getId());
            if((new ConnexionUtilisateur())->getLoginUtilisateurConnecte() == $user->getId())
            (new ConnexionUtilisateur())->deconnecter();
            MessageFlash::ajouter('info', 'Profil supprimé.');
            self::redirection('frontController.php?controller=user&action=accueil');
        }
        else
        {
            if(!$userRepository->checkCmdp($mdp, $cMdp))
            {
                MessageFlash::ajouter('danger', 'Les mots de passe ne correspondent pas.');
                self::redirection('frontController.php?controller=user&action=delete&id='.$user->getId());
            }
            else if(!$userRepository->checkCmdp($user->getMdpHache(), $userRepository->setMdpHache($mdp)))
            {
                MessageFlash::ajouter('danger', 'Mot de passe incorrect.');
                self::redirection('frontController.php?controller=user&action=delete&id='.$user->getId());
            }

        }
    }


}
