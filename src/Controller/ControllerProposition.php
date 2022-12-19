<?php

namespace App\Controller;

use App\Lib\ConnexionUtilisateur;
use App\Lib\MessageFlash;
use App\Model\DataObject\Demande;
use App\Model\DataObject\Proposition;
use App\Model\Repository\CommentaireRepository;
use App\Model\Repository\DatabaseConnection;
use App\Model\Repository\DemandeRepository;
use App\Model\Repository\PropositionRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\UserRepository;

class ControllerProposition extends GenericController
{
    public static function readAll() : void
    {
        self::connexionRedirect('warning', 'Connectez-vous pour voir les propositions');
        $idQuestion = $_GET['id'];

        $listePropositions = (new PropositionRepository())->selectAllForQuestion($idQuestion);

        $parametres = array(
            'pagetitle' => 'Liste Propositions',
            'cheminVueBody' => 'proposition/list.php',
            'propositions' => $listePropositions
        );

        self::afficheVue('view.php',$parametres);
    }

    public static function read() : void
    {
        self::connexionRedirect('warning', 'Connectez-vous pour accéder aux propositions');
        $idProposition = $_GET['id'];

        $proposition = (new PropositionRepository())->select($idProposition);

        $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $roleProposition = '';
        if($proposition->getIdResponsable()==$idUser){
            $roleProposition='responsable';
        }
        else{
            foreach ($proposition->getIdAuteurs() as $idAuteur){
                if($idAuteur==$idUser){
                    $roleProposition='auteur';
                }
            }
        }

        $commentaires = (new CommentaireRepository())->selectAllProp($idProposition);

        $parametres = array(
            'pagetitle' => 'Détail Proposition',
            'cheminVueBody' => 'proposition/detail.php',
            'proposition' => $proposition,
            'commentaires'=>$commentaires,
            'roleProposition' => $roleProposition
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function update(){

        $idProposition = $_GET['id'];

        $proposition = (new PropositionRepository())->select($idProposition);

        $parametres = array(
            'pagetitle' => 'Modifier Proposition',
            'cheminVueBody' => 'proposition/update.php',
            'proposition' => $proposition
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function updated() : void
    {
        self::connexionRedirect('warning', 'Veuillez vous connecter');
        $idProposition = $_GET['id'];
        $proposition = (new PropositionRepository())->select($idProposition);
        if($proposition==null){
            MessageFlash::ajouter('danger', "La question avec l'id suivant : " . $_GET['id'] . "n'existe pas");
            self::redirection('frontController.php?controller=question&action=readAll');
        }
        else {
            $sectionsText = [];

            foreach ($_POST['texte'] as $idSection => $text) {
                $sectionsText[$idSection] = $text;
            }

            $proposition->setSectionsTexte($sectionsText);
            $proposition->setIntitule($_POST['intitule']);

            (new PropositionRepository())->update($proposition);
            self::read();
        }
    }

    public static function create(){
        self::connexionRedirect('warning', 'Veuillez vous connecter');
        $idQuestion = $_GET['id'];

        $proposition = (new PropositionRepository())->sauvegarder(new Proposition(null, $idQuestion, ConnexionUtilisateur::getLoginUtilisateurConnecte(),null, null, false, []));

        $parametres = array(
            'pagetitle' => 'Créer Proposition',
            'cheminVueBody' => 'proposition/update.php',
            'proposition' => $proposition
        );
        self::afficheVue('view.php', $parametres);
    }

    public static function delete(){
        $idProposition = $_GET['id'];
        $proposition = (new PropositionRepository())->select($idProposition);

        if($proposition==null){
            MessageFlash::ajouter('warning', "La proposition n'existe pas");
            self::redirection('frontController.php?controller=question&action=readAll');
        }
        else {
            (new PropositionRepository())->delete($idProposition);
            MessageFlash::ajouter('info', 'La proposition : "' . $proposition . '" a bien été suprimée');
            self::redirection('frontController.php?controller=proposition&action=readAll&id='. $proposition->getIdQuestion());
        }
//        $parametres = array(
//            'pagetitle' => 'Proposition Supprimée',
//            'cheminVueBody' => 'question/detail.php',
//            'question' => (new QuestionRepository())->select($proposition->getIdQuestion())
//        );
//
//        self::afficheVue('view.php', $parametres);
    }

    public static function selectAllWithScore(){
        $idPhase = $_GET['idPhase'];
        $scores = [];
        $propositions = [];

        $propositionsScore = (new PropositionRepository())->selectAllWithScore($idPhase);
        foreach ($propositionsScore as $proposition){
            $propositions[] = $proposition[0];
            $scores[$proposition[0]->getId()] = $proposition[1];
        }

        $param = [
            'pagetitle' => 'Scores',
            'cheminVueBody' => '/proposition/listWithScore.php',
            'propositions' => $propositions,
            'scores' => $scores
        ];

        self::afficheVue('view.php', $param);
    }

    public static function ajtCommentaire()
    {
        $commentaire = $_POST['commentaire'];
        $userRepository = new UserRepository();
        $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $idProposition=$_GET['id'];
        $date = date("'d/m/y G:i:s'");
        $date .=",'dd/mm/yy hh24:mi:ss'";

        $commentaireRepository= new CommentaireRepository();
        $commentaireRepository->commenter($idProposition,$idUser,$commentaire,$date);

        MessageFlash::ajouter('info','Commentaire ajouté');
        self::redirection('frontController.php?controller=proposition&action=read&id='.$idProposition);
    }

    public static function deleteCommentaire():void
    {
        $idCommentaire= $_GET['idCommentaire'];
        $idProposition= $_GET['id'];

        (new CommentaireRepository())->deleteCommentaire($idCommentaire);


        MessageFlash::ajouter('info','Vous n\'avez pas aimé ce commentaire.');
        self::redirection('frontController.php?controller=proposition&action=read&id='.$idProposition);
    }

    public static function likeCommentaire():void
    {
        $idCommentaire= $_GET['idCommentaire'];
        $idProposition= $_GET['id'];

        (new CommentaireRepository())->liker($idCommentaire);

        MessageFlash::ajouter('info','Vous avez aimé ce commentaire');
        self::redirection('frontController.php?controller=proposition&action=read&id='.$idProposition);
    }

    public static function dislikeCommentaire():void
    {
        $idCommentaire= $_GET['idCommentaire'];
        $idProposition= $_GET['id'];

        (new CommentaireRepository())->disliker($idCommentaire);

        MessageFlash::ajouter('info','Commentaire supprimé.');
        self::redirection('frontController.php?controller=proposition&action=read&id='.$idProposition);
    }

    public static function addDemandeAuteur(){
        $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $idProposition =$_GET['id'];

        $proposition = (new PropositionRepository())->select($idProposition);
        $demande = new Demande('auteur', $proposition->getIdQuestion(), $idUser, $proposition->getIdResponsable(), $idProposition);

        DemandeRepository::sauvegarder($demande);

        MessageFlash::ajouter('success', 'demande effectuée');
        ControllerQuestion::readAll();
    }

    public static function readDemandeAuteur() : void{
        $idProposition = $_GET['id'];

        $proposition = (new PropositionRepository())->select($idProposition);
        $demandes = DemandeRepository::getDemandeAuteurProposition($proposition);
        $action = 'frontController.php?action=demandesAccepted&controller=Proposition&id=' . $idProposition;

        $parametres = [
            'pagetitle' => 'demandes en attentes',
            'cheminVueBody' => 'demande/listAccept.php',
            'demandes' => $demandes,
            'action' => $action
        ];
        self::afficheVue('view.php', $parametres);
    }

    public static function demandesAccepted(){
        $idProposition = $_GET['id'];
        $acceptees = [];
        foreach ($_POST['user'] as $idUser) {
            $acceptees[] = $idUser;
        }
        $proposition = (new PropositionRepository())->select($idProposition);
        (new PropositionRepository())->addAuteursProposition($acceptees, $proposition);

        ControllerQuestion::readAll();
    }



}