<?php

namespace App\Controller;

use App\Lib\ConnexionUtilisateur;
use App\Model\DataObject\Demande;
use App\Model\DataObject\Phase;
use App\Model\DataObject\Question;
use App\Model\DataObject\Section;
use App\Model\Repository\DemandeRepository;
use App\Model\Repository\PhaseRepository;
use App\Model\Repository\PropositionRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\SectionRepository;
use App\Lib\MessageFlash;
use App\Model\Repository\UserRepository;
use App\Model\Repository\VoteRepository;

class ControllerQuestion extends GenericController
{

    public static function readAll() : void
    {
        if (isset($_POST['title']) AND !empty($_POST['title'])){
            $recherche= strtolower(htmlspecialchars($_POST['title']));
            $questions = (new QuestionRepository)->search($recherche);
        }
        else{
            $questions = (new QuestionRepository)->selectAll();
        }

        $privilegeUser='';
        if(ConnexionUtilisateur::estConnecte()){
            $privilegeUser = (new UserRepository())->getPrivilege(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        }

        $parametres = array(
            'pagetitle' => 'Liste Questions',
            'cheminVueBody' => 'question/list.php',
            'questions' => $questions,
            'privilegeUser' => $privilegeUser
        );
        self::afficheVue('view.php', $parametres);
    }

    public static function read() : void
    {
        $idQuestion = $_GET['id'];

        $question = (new QuestionRepository())->select($idQuestion);

        $demandes = DemandeRepository::getDemandeVoteQuestion($question);

        $phases=(new PhaseRepository())->getPhasesIdQuestion($idQuestion);

        $roleQuestion='';
        $peutVoter = false;
        if(ConnexionUtilisateur::estConnecte()) {
            $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            $roleQuestion = (new UserRepository())->getRoleQuestion($idUser, $idQuestion);
            $peutVoter = VoteRepository::peutVoter($idUser, $idQuestion);
        }

        $parametres = array(
            'pagetitle' => 'Détail Question',
            'cheminVueBody' => 'question/detail.php',
            'question' => $question,
            'demandes' => $demandes,
            'phases' => $phases,
            'roleQuestion' => $roleQuestion,
            'peutVoter' => $peutVoter
        );

        self::afficheVue('view.php', $parametres);
    }


    public static function create() : void
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Créer Question",
            "cheminVueBody" => 'question/create.php'
        ]);
    }

    public static function created() : void
    {
        $intitule = $_POST['titreQuestion'];
        $nbSections = $_POST['nbSections'];
        $nbPhases = $_POST['nbPhases'];
        $dateCreation = date_create();
        $dateFermeture = date_create($_POST['dateFermeture']);
        $question = new Question(null, ConnexionUtilisateur::getLoginUtilisateurConnecte() , $intitule, 'description', $dateCreation, $dateFermeture, Phase::emptyPhase());
        $question = (new QuestionRepository())->creerQuestion($question, $nbSections, $nbPhases);

        $parametres = array(
            'pagetitle' => 'Ajuster Question',
            'cheminVueBody' => 'question/update.php',
            'question' => $question
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function update() : void
    {
        $idQuestion = $_GET['id'];

        $parametres = array(
            'pagetitle' => 'Modifier Question',
            'cheminVueBody' => 'question/update.php',
            'question' => (new QuestionRepository())->select($idQuestion)
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function updated() : void
    {
        if($_POST['titreQuestion']==null || $_POST['descriptionQuestion']==null) {
            MessageFlash::ajouter('danger', 'Veuillez remplir les éléments manquants');
            self::redirection('frontController.php?controller=question&action=update&id='. $_GET['id']);
        }
        else {
            $titreQuestion = $_POST['titreQuestion'];
            $descriptionQuestion = $_POST['descriptionQuestion'];

            $question = (new QuestionRepository())->select($_GET['id']);
            $question->setIntitule($titreQuestion);
            $question->setDescription($descriptionQuestion);
            (new QuestionRepository())->update($question);

            $sections = array();
            foreach ($_POST['intitule'] as $key => $intitule) {
                $sections[$key]['intitule'] = $intitule;
            }

            foreach ($_POST['description'] as $key => $description) {
                $sections[$key]['description'] = $description;
            }

            foreach ($sections as $key => $tabSection) {
                $section = new Section($key, $_GET['id'], $tabSection['intitule'], $tabSection['description']);
                (new SectionRepository())->update($section);
            }

            $phases = [];
            foreach ($_POST['dateDebut'] as $key => $dateDebut) {
                $phases[$key]['dateDebut'] = $dateDebut;
            }
            foreach ($_POST['dateFin'] as $key => $dateFin) {
                $phases[$key]['dateFin'] = $dateFin;
            }
            foreach ($_POST['type'] as $key => $type) {
                $phases[$key]['type'] = $type;
            }
            foreach ($_POST['nbDePlaces'] as $key => $nbDePlace) {
                $phases[$key]['nbDePlaces'] = $nbDePlace;
            }
            foreach ($phases as $id => $tabPhase) {
                $p = new Phase(
                    $id,
                    $tabPhase['type'],
                    date_create($tabPhase['dateDebut']),
                    date_create($tabPhase['dateFin']),
                    $tabPhase['nbDePlaces']);
                (new PhaseRepository())->update($p);
            }

            MessageFlash::ajouter('success', 'La question : "' . $titreQuestion . '" est désormais à jour.');
            self::redirection('frontController.php?controller=question&action=readAll');
        }
    }

    public static function delete() : void
    {
        $question = (new QuestionRepository())->select($_GET['id']);
        if($question==null){
            MessageFlash::ajouter('warning', "La question avec l'id suivant : " . $_GET['id'] . "n'existe pas.");
            self::redirection('frontController.php?controller=question&action=readAll');
        }
        else{
            (new QuestionRepository())->delete($_GET['id']);

            MessageFlash::ajouter('info', 'La question a bien été suprimée.');
            self::redirection('frontController.php?controller=question&action=readAll');
        }
    }

//    public static function voter():void{
//        $question = (new QuestionRepository())->select($_GET['id']);
//        if($question->getCurrentPhase()->getType() == 'vote'){
//            $parametres = [
//                'pagetitle' => 'vote',
//                'cheminVueBody' => 'vote/vote.php',
//                'question' => $question
//            ];
//            self::afficheVue('view.php', $parametres);
//        }
//    }

    public static function addVotantToQuestion(){
        $idQuestion = $_GET['id'];

        if (isset($_POST['title']) AND !empty($_POST['title'])){
            $recherche= strtolower(htmlspecialchars($_POST['title']));
            $users = (new UserRepository())->search($recherche);
        }
        else{
            $users = (new UserRepository())->selectAll();
        }

        $action = 'frontController.php?controller=question&action=votantAdded&idQuestion=' . $idQuestion;

        $privilegeUser = '';
        if(ConnexionUtilisateur::estConnecte()){
            $privilegeUser = (new UserRepository())->getPrivilege(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        }

        $param = [
            'question' => (new QuestionRepository())->select($idQuestion),
            'users' => $users,
            'action' => $action,
            'privilegeUser' => $privilegeUser,
            'pagetitle' => 'test',
            'cheminVueBody' => '/user/listPourAjouter.php'
        ];

        self::afficheVue('view.php', $param);
    }

    public static function votantAdded(){
        $idUsers = [];
        $idQuestion = $_GET['idQuestion'];
        if(isset($_POST['user'])){
            foreach ($_POST['user'] as $idUser){
                $idUsers[] = $idUser;
            }
            (new QuestionRepository())->addUsersQuestion($idUsers, $idQuestion);
        }
        self::readAll();
    }

    public static function readAllArchives(){
        if (isset($_POST['title']) AND !empty($_POST['title'])){
            $recherche= strtolower(htmlspecialchars($_POST['title']));
            $questions = (new QuestionRepository)->search($recherche);
        }
        else{
            $questions = (new QuestionRepository())->selectAllClosed();
        }

        $privilegeUser = '';
        if(ConnexionUtilisateur::estConnecte()){
            $privilegeUser = (new UserRepository())->getPrivilege(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        }

        $param = [
            'pagetitle' => 'Questions fermées',
            'cheminVueBody' => 'archives/list.php',
            'questions' => $questions,
            'privilegeUser' => $privilegeUser
        ];
        self::afficheVue('view.php', $param);
    }


    public static function readResult() : void
    {
        self::connexionRedirect('warning', 'Veuillez vous connecter pour accéder aux résultats');

        $idQuestion = $_GET['id'];
        if((new QuestionRepository())->estFini($idQuestion)){
            $question = (new QuestionRepository())->select($idQuestion);

            $phases = $question->getPhases();
            $phase = $phases[sizeof($phases)-1];
            $propositionsScore = (new PropositionRepository())->selectAllWithScore($phase->getId());

            self::afficheVue('view.php',[
                "pagetitle" => "Resultat Question",
                "cheminVueBody" => 'question/results.php',
                'question' => $question,
                'phase' => $phase,
                'propositionsScore' => $propositionsScore
            ]);
        }
        else{
            MessageFlash::ajouter('info', 'La question n\'est pas encore finie, revenez plus tard');
            self::redirection('frontController.php?controller=question&action=read&id='.$idQuestion);
        }

    }

    public static function finPhase() : void
    {
        self::connexionRedirect('warning', 'Veuillez vous connecter');
        $idQuestion = $_GET['id'];

        $question = (new QuestionRepository())->select($idQuestion);
        $currentPhase=(new PhaseRepository())->getCurrentPhase($idQuestion);
        if($currentPhase->isEmpty()){
            MessageFlash::ajouter('info', 'il n\'y a pas de phase en cours');
        }
        else if(ConnexionUtilisateur::getLoginUtilisateurConnecte()==$question->getIdOrganisateur()){
            $currentPhase=(new PhaseRepository())->getCurrentPhase($idQuestion);

            (new PhaseRepository())->endPhase($currentPhase->getId());
        }
        else{
            MessageFlash::ajouter('warning', 'Vous ne disposez pas des droits');
        }

        $parametres = array(
            'pagetitle' => 'Détail Question',
            'cheminVueBody' => 'question/detail.php',
            'question' => $question
        );

        self::afficheVue('view.php', $parametres);
    }


    public static function debutPhase() : void
    {
        $idQuestion = $_GET['id'];

        $question = (new QuestionRepository())->select($idQuestion);
        $currentPhase=(new PhaseRepository())->getCurrentPhase($idQuestion);

        (new PhaseRepository())->startPhase($currentPhase->getId());

        $parametres = array(
            'pagetitle' => 'Détail Question',
            'cheminVueBody' => 'question/detail.php',
            'question' => $question
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function readDemandeVote() : void{
        $idQuestion = $_GET['id'];

        $question = (new QuestionRepository())->select($idQuestion);
        $demandes = DemandeRepository::getDemandeVoteQuestion($question);
        $action = 'frontController.php?action=demandesAccepted&controller=question&id=' . $idQuestion;

        $parametres = [
            'pagetitle' => 'demandes en attentes',
            'cheminVueBody' => 'demande/listAccept.php',
            'demandes' => $demandes,
            'action' => $action
        ];
        self::afficheVue('view.php', $parametres);
    }

    public static function demandesAccepted(){
        $idQuestion = $_GET['id'];
        $acceptees = [];
        foreach ($_POST['user'] as $idUser) {
            $acceptees[] = $idUser;
        }
        (new QuestionRepository())->addUsersQuestion($acceptees, $idQuestion);
    }
}