<?php

namespace App\Controller;

use App\Lib\ConnexionUtilisateur;
use App\Lib\MessageFlash;
use App\Model\HTTP\Session;
use App\Model\Repository\PropositionRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\VoteRepository;

class ControllerVote extends GenericController
{
    public static function scrutinMajoritaire() : void{
        $parametres = array(
            'pagetitle' => 'Scrutin Majoritaire',
            'cheminVueBody' => 'vote/scrutinMajoritaire.php',
        );
        self::afficheVue('view.php', $parametres);
    }

    public static function voterScrutinMajoritaire() : void
    {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $propositions = (new PropositionRepository())->selectAllForQuestion($question->getId());
        $parametres = array(
            'pagetitle' => 'Scrutin Majoritaire',
            'cheminVueBody' => 'vote/voter/scrutinMajoritaire.php',
            'question' => $question,
            'propositions' => $propositions
        );
        self::afficheVue('view.php', $parametres);
    }

    public static function scrutinMajoritaireVoted(){
        $user = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if($user == null){
            MessageFlash::ajouter('danger', 'vote refusé, vous n\'êtes pas connecté');
        }
        else if(isset($_POST['idProposition'])){
            $scoreVote = 1;

            VoteRepository::voter($_POST['idProposition'], $user, $scoreVote);

            MessageFlash::ajouter('success', 'Vous avez voté !');
        }
        else{
            MessageFlash::ajouter('danger', "Votre vote n'est pas passé.");
        }
        ControllerQuestion::readAll();
    }
    public static function consultation() : void
    {

        $parametres = array(
            'pagetitle' => 'Consultation',
            'cheminVueBody' => 'vote/consultation.php'
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function demandeAcces() : void{
        $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $idQuestion = $_GET['idQuestion'];

        if((new UserRepository())->demanderAccesVote($idUser, $idQuestion)){
            MessageFlash::ajouter('success', 'votre demande à bien été enregistrée');
        }
        else{
            MessageFlash::ajouter('failure', 'votre demande à échoué');
        }

        ControllerUser::accueil();
    }
}