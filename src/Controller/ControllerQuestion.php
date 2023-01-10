<?php

namespace App\Controller;

use App\Lib\ConnexionUtilisateur;
use App\Lib\MessageFlash;
use App\Model\DataObject\Demande;
use App\Model\DataObject\Phase;
use App\Model\DataObject\Question;
use App\Model\DataObject\Section;
use App\Model\DataObject\User;
use App\Model\Repository\DemandeUserRepository;
use App\Model\Repository\GroupeRepository;
use App\Model\Repository\PhaseRepository;
use App\Model\Repository\PropositionRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\SectionRepository;
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
            $questions = (new QuestionRepository)->selectAllByDate();
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
        if(!isset($_GET['id']))
        {
            self::redirection('frontController.php?controller=question&action=readAll');
        }
        $idQuestion = $_GET['id'];

        $question = (new QuestionRepository())->select($idQuestion);

        $demandes = (new DemandeUserRepository)->selectAllDemandeVoteQuestion($question);

        $phases=(new PhaseRepository())->getPhasesIdQuestion($idQuestion);

        $roleQuestion='';
        $peutVoter = false;
        $peutPasser = false;
        if(ConnexionUtilisateur::estConnecte()) {
            $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            $roleQuestion = (new UserRepository())->getRoleQuestion($idUser, $idQuestion);
            $peutVoter = UserRepository::peutVoter($idUser, $idQuestion);
            $peutPasser = false;
            if($roleQuestion=='organisateur') {
                $currentDate = date_create("now");
                foreach ($question->getPhases() as $phase) {
                    $dateDebut = $phase->getDateDebut();
                    $dateFin = $phase->getDateFin();
                    $bool1 = ($dateDebut >= $currentDate && date_diff($dateDebut, $currentDate)->d == 0);
                    $bool2 = ($dateFin >= $currentDate && date_diff($dateFin, $currentDate)->d == 0);
                    if(!$peutPasser) {
                        $peutPasser = $bool1 || $bool2;
                    }
                }
            }
        }

        $parametres = array(
            'pagetitle' => 'Détail Question',
            'cheminVueBody' => 'question/detail.php',
            'question' => $question,
            'demandes' => $demandes,
            'phases' => $phases,
            'roleQuestion' => $roleQuestion,
            'peutVoter' => $peutVoter,
            'peutPasser' => $peutPasser
        );


        self::afficheVue('view.php', $parametres);
    }


    public static function create() : void
    {
        self::connexionRedirect('warning', 'Connectez-vous');
        if((new UserRepository())->getPrivilege(ConnexionUtilisateur::getLoginUtilisateurConnecte())=='organisateur'){
            self::afficheVue('view.php',[
                "pagetitle" => "Créer Question",
                "cheminVueBody" => 'question/create.php'
            ]);
        }
        else{
            MessageFlash::ajouter('warning', 'Vous n\'avez pas les droits');
            self::readAll();
        }
    }

    public static function created() : void
    {
        self::connexionRedirect('warning', 'Connectez-vous');
        if(!(new UserRepository())->getPrivilege(ConnexionUtilisateur::getLoginUtilisateurConnecte())=='organisateur'){
            MessageFlash::ajouter('warning', 'Vous n\'avez pas les droits');
            self::readAll();
        }
        else{
            $intitule = $_POST['titreQuestion'];
            $nbSections = $_POST['nbSections'];
            $nbPhases = $_POST['nbPhases']; //Pour ajouter aux phase de votes la phase de redaction
            $dateCreation = date_create();
            $dateFermeture = date_create($_POST['dateFermeture']);
            if(date_create($_POST['dateFermeture']) < $dateCreation)
            {
                MessageFlash::ajouter('info','Date antérieure non valide');
                self::redirection('frontController.php?controller=question&action=create');
            }

            $question = new Question(null, ConnexionUtilisateur::getLoginUtilisateurConnecte() , $intitule, ' ', $dateCreation, $dateFermeture, Phase::emptyPhase());
            $question = (new QuestionRepository())->creerQuestion($question, $nbSections, $nbPhases);

            $parametres = array(
                'pagetitle' => 'Ajuster Question',
                'cheminVueBody' => 'question/update.php',
                'question' => $question
            );

            self::afficheVue('view.php', $parametres);
        }
    }

    public static function update() : void
    {
        self::connexionRedirect('warning', 'Connectez-vous');
        $question = (new QuestionRepository())->select($_GET['id']);
        if($question->getIdOrganisateur() != ConnexionUtilisateur::getLoginUtilisateurConnecte()){
            MessageFlash::ajouter('warning', 'Vous n\'avez pas les droits');
            self::readAll();
        }
        else{
            $parametres = array(
                'pagetitle' => 'Modifier Question',
                'cheminVueBody' => 'question/update.php',
                'question' => $question
            );

            self::afficheVue('view.php', $parametres);
        }
    }

    public static function updated() : void
    {
        self::connexionRedirect('warning', 'Connectez-vous');
        $question = (new QuestionRepository())->select($_GET['id']);
        if($question->getIdOrganisateur() != ConnexionUtilisateur::getLoginUtilisateurConnecte()){
            MessageFlash::ajouter('warning', 'Vous n\'avez pas les droits');
            self::readAll();
        }
        else{ // l'utilisateur est l'oganisateur de la question
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
                $phasesCurrent = (new PhaseRepository())->getPhasesIdQuestion($question->getId());
                foreach ($phases as $id => $tabPhase) {
                    $type = $tabPhase['type'];
                    $nbDePlace = $tabPhase['nbDePlaces'];
                    if($id == $phasesCurrent[0]->getId()){// la premiere phase n'est autre que la phase de rédaction
                        $type='redaction';
                        $nbDePlace = 0;
                    }
                    if($id == $phasesCurrent[sizeof($phases)-1]){// il s'agit de la phase de vote finale
                        if($type!='scrutinMajoritaire' || $type != 'jugementMajoritaire' || $type != 'scrutinMajoritairePlurinominal'){
                            $type = 'scrutinMajoritaire';
                        }
                        $nbDePlace = 1;
                    }
                    $p = new Phase(
                        $id,
                        $type,
                        date_create($tabPhase['dateDebut']),
                        date_create($tabPhase['dateFin']),
                        $nbDePlace);

                    (new PhaseRepository())->update($p);
                }

                MessageFlash::ajouter('success', 'La question : "' . $titreQuestion . '" est désormais à jour.');
                self::redirection('frontController.php?controller=question&action=read&id=' . $question->getId());
            }
        }
    }

    public static function delete() : void
    {
        self::connexionRedirect('warning', 'Connectez-vous');
        $question = (new QuestionRepository())->select($_GET['id']);
        if($question->getIdOrganisateur() != ConnexionUtilisateur::getLoginUtilisateurConnecte()){
            MessageFlash::ajouter('warning', 'Vous n\'avez pas les droits');
            self::readAll();
        }
        else{
            (new QuestionRepository())->delete($_GET['id']);

            MessageFlash::ajouter('success', 'La question a bien été suprimée.');
            self::redirection('frontController.php?controller=question&action=readAll');
        }
    }

    public static function OrganisateursAdded(){
        self::connexionRedirect('warning', 'Connectez-vous');
        if((new UserRepository())->getPrivilege(ConnexionUtilisateur::getLoginUtilisateurConnecte()) != "administrateur"){
            MessageFlash::ajouter('warning', 'Vous n\'avez pas les droits');
            self::readAll();
        }
        else{
            $idUsers = [];
            var_dump($_GET);

            if (isset($_POST['user'])) {
                foreach ($_POST['user'] as $idUser) {
                    $idUsers[] = $idUser;
                }
                (new QuestionRepository())->addOrganisateurs($idUsers);
                MessageFlash::ajouter('success', 'Utilisateur(s) ajouté(s)!');
            }
        }
        self::redirection('frontController.php?controller=question&action=readAll');
    }

    public static function addOrganisateurs(){
        self::connexionRedirect('warning', 'Connectez-vous');

        if((new UserRepository())->getPrivilege(ConnexionUtilisateur::getLoginUtilisateurConnecte()) != "administrateur"){
            MessageFlash::ajouter('warning', 'Vous n\'avez pas les droits');
            self::readAll();
        }
        else{
            if (isset($_POST['title']) AND !empty($_POST['title'])){
                $recherche= strtolower(htmlspecialchars($_POST['title']));
                $arrayUser = (new UserRepository())->search($recherche);
            }
            else{
                $arrayUser = (new UserRepository())->selectAllValide();
            }

            $parametres = array(
                'pagetitle' => 'Ajout Organisateurs',
                'cheminVueBody' => 'question/listAjoutOrganisateurs.php',
                'users' => $arrayUser
            );
            self::afficheVue('view.php', $parametres);
        }
    }

    public static function addUsersToQuestion(){
        self::connexionRedirect('warning', 'Connectez-vous');
        $question = (new QuestionRepository())->select($_GET['id']);
        if($question->getIdOrganisateur() != ConnexionUtilisateur::getLoginUtilisateurConnecte()){
            MessageFlash::ajouter('warning', 'Vous n\'avez pas les droits');
            self::readAll();
        }
        else{
            $idQuestion = $_GET['id'];

            if (isset($_POST['filtre']) AND !empty($_POST['filtre'])){
                $recherche= strtolower(htmlspecialchars($_POST['filtre']));
                $users = (new UserRepository())->search($recherche);
            }
            else{
                $users = (new UserRepository())->selectAll();
            }
            //retirer les membres qui sont deja votant
            foreach ($users as $key=>$user){
                if($user->getId() == ConnexionUtilisateur::getLoginUtilisateurConnecte()){
                    unset($users[$key]);
                }
            }

            $role = isset($_GET['role'])?'&role='.$_GET['role']:'&role=votant';
            $action = 'frontController.php?controller=question&action=usersAdded&id=' . $idQuestion . $role;

            $privilegeUser = (new UserRepository())->getPrivilege(ConnexionUtilisateur::getLoginUtilisateurConnecte());

            $param = [
                'question' => (new QuestionRepository())->select($idQuestion),
                'users' => $users,
                'action' => $action,
                'privilegeUser' => $privilegeUser,
                'pagetitle' => 'test',
                'cheminVueBody' => 'question/listPourAjouter.php'
            ];

            self::afficheVue('view.php', $param);
        }
    }

    public static function usersAdded(){
        self::connexionRedirect('warning', 'Connectez-vous');
        $question = (new QuestionRepository())->select($_GET['id']);
        if($question->getIdOrganisateur() != ConnexionUtilisateur::getLoginUtilisateurConnecte()){
            MessageFlash::ajouter('warning', 'Vous n\'avez pas les droits');
            self::readAll();
        }
        else {
            $idUsers = [];
            $idQuestion = $_GET['id'];
            $role = $_GET['role'];

            if (isset($_POST['user'])) {
                foreach ($_POST['user'] as $idUser) {
                    $idUsers[] = $idUser;
                }
                (new QuestionRepository())->addUsersQuestion($idUsers, $idQuestion, $role);
                MessageFlash::ajouter('success', 'Utilisateur(s) ajouté(s)!');
            }
            self::redirection('frontController.php?controller=question&action=read&id='.$idQuestion);
        }
    }

    public static function readAllArchives(){
        if (isset($_POST['title']) AND !empty($_POST['title'])){
            $recherche= strtolower(htmlspecialchars($_POST['title']));
            $questions = (new QuestionRepository)->searchClosed($recherche);
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
            self::afficheResultPhase($phase, $question);
        }
        else{
            MessageFlash::ajouter('info', 'La question n\'est pas encore finie, revenez plus tard');
            self::redirection('frontController.php?controller=question&action=read&id='.$idQuestion);
        }
    }

    public static function readResultPhase() : void
    {
        self::connexionRedirect('warning', 'Veuillez vous connecter pour accéder aux résultats');

        $idQuestion = $_GET['id'];
        $idPhase = $_GET['idPhase'];

        if($idPhase!=null) {
            $phase = (new PhaseRepository())->select($idPhase);

            if ($phase->estFinie()) {
                $question = (new QuestionRepository())->select($idQuestion);

                self::afficheResultPhase($phase, $question);
            } else {
                MessageFlash::ajouter('info', 'La phase n\'est pas encore finie, revenez plus tard');
                self::redirection('frontController.php?controller=question&action=read&id=' . $idQuestion);
            }
        }
        else{
            MessageFlash::ajouter('info', 'Cette phase est une consultation donc il n\'y a pas de vote');
            self::redirection('frontController.php?controller=question&action=read&id=' . $idQuestion);
        }
    }

    private static function afficheResultPhase(Phase $phase, Question $question){
        $params = [
            'pagetitle' => 'Résultats',
            'question' => $question,
            'phase' => $phase,
            "cheminVueBody" => 'question/results.php'
        ];

        if($phase->getType()=='scrutinMajoritaire' || $phase->getType()=='scrutinMajoritairePlurinominal'){
            $result = (new PropositionRepository())->selectAllWithScore($phase->getId());
            $params["cheminVueBody"] = 'question/results.php';
            $params['propositionsScore'] = $result;
        }
        elseif ($phase->getType()=='jugementMajoritaire'){
            $result = (new PropositionRepository())->selectAllWithScoreJugement($phase->getId());
            $params["cheminVueBody"] = 'question/results.php';
            $params['propositionsScore'] = $result;
        }
        self::afficheVue('view.php', $params);
    }


    public static function changePhase() : void
    {
        self::connexionRedirect('warning', 'Veuillez vous connecter');
        $idQuestion = $_GET['id'];

        $question = (new QuestionRepository())->select($idQuestion);
        $currentPhase = $question->getCurrentPhase();

        $dateFin = $currentPhase->getDateFin();
        $currentDate = date_create("now");
        if($dateFin >= $currentDate && date_diff($dateFin, $currentDate)->d == 0){
            (new PhaseRepository())->endPhase($currentPhase->getId());
        }

        foreach ($question->getPhases() as $phase){
            $dateDebut = $phase->getDateDebut();
            if($dateDebut >= $currentDate && date_diff($dateDebut, $currentDate)->d == 0){
                (new PhaseRepository())->startPhase($phase->getId());
            }
        }

        self::redirection("frontController.php?controller=question&action=read&id=".$question->getId());
    }

    public static function readDemandeVote() : void{
        self::connexionRedirect('warning', 'Connectez-vous');
        $idQuestion = $_GET['id'];

        $question = (new QuestionRepository())->select($idQuestion);
        if($question->getIdOrganisateur()!=ConnexionUtilisateur::getLoginUtilisateurConnecte()){
            MessageFlash::ajouter('danger', 'vous n\'etes pas autorisé à lire les demandes de vote');
            self::redirection('frontController.php?controller=question&action=read&id='.$question->getId());
        }

        $demandes = (new DemandeUserRepository)->selectAllDemandeQuestion($question);

        $action = 'frontController.php?action=demandesAccepted&controller=question&id=' . $idQuestion;

        $parametres = [
            'pagetitle' => 'demandes de votants',
            'cheminVueBody' => 'gestionRoles/readDemandes.php',
            'demandes' => $demandes,
            'action' => $action,
            'privilegeUser' => 'Organisateur'
        ];
        self::afficheVue('view.php', $parametres);
    }

    public static function demandesAccepted(){
        $idQuestion = $_GET['id'];
        $question = (new QuestionRepository())->select($idQuestion);
        $accepteResponsable = [];
        $accepteVotant = [];
        foreach ($_POST['user'] as $idUser) {
            $role = $_POST['role'][$idUser];
            $demande = new Demande($role, $question, (new UserRepository())->select($idUser));
            (new DemandeUserRepository())->delete($demande);
            if($role=='votant'){
                $accepteVotant[] = $idUser;
            }
            elseif ($role=='responsable'){
                $accepteResponsable[] = $idUser;
            }
        }
        (new QuestionRepository())->addUsersQuestion($accepteVotant, $idQuestion, 'votant');
        (new QuestionRepository())->addUsersQuestion($accepteResponsable, $idQuestion, 'responsable');
        MessageFlash::ajouter('success', 'Utilisateurs correctement ajoutés!');
        self::redirection('frontController.php?controller=question&action=read&id='.$idQuestion);
    }

    public static function demandeRoleQuestion(){
        self::connexionRedirect('warning', 'Connectez-vous afin de pouvoir demander');
        $question = (new QuestionRepository())->select($_GET['id']);
        $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $role = $_GET['role'];

        $demande = new Demande($role, $question, (new UserRepository())->select($idUser));
        if(!((new UserRepository())->estOrganisateurSurQuestion($idUser,$question->getId()))
            && !(new DemandeUserRepository())->aDejaDemande($idUser,$question->getId())
            && (new DemandeUserRepository())->sauvegarder(($demande))
            )
        {
            MessageFlash::ajouter('success', 'Votre demande a bien été enregistré');
        }
        else{
            MessageFlash::ajouter('info', 'Une erreur est survenu lors de l\'enregistrement de votre demande');
        }
        self::read();
    }

    public static function addGroupesRoleQuestion()
    {
        self::connexionRedirect('warning', 'Connectez-vous');

        $question = (new QuestionRepository())->select($_GET['id']);
        if(ConnexionUtilisateur::getLoginUtilisateurConnecte()!=$question->getIdOrganisateur()){
            MessageFlash::ajouter('danger', 'Vous n\'êtes pas organisateur de cette question!');
            self::redirection('frontController.php?controller=question&action=read&id=' . htmlspecialchars($question->getId()));
        }
        $role = $_GET['role'];
        $groupes = (new GroupeRepository())->selectAll();
        $action = 'frontController.php?controller=question&action=addedGroupeRoleQuestion&role=' . htmlspecialchars($role) . '&id=' . htmlspecialchars($question->getId());
        $privilegeUser = 'Organisateur';

        $param = [
            'pagetitle' => 'Ajouter groupes',
            'cheminVueBody' => 'question/listPourAjouter.php',
            'groupes' => $groupes,
            'action' => $action,
            'privilegeUser' => $privilegeUser
        ];
        self::afficheVue('view.php', $param);
    }

    public static function addedGroupeRoleQuestion(){
        self::connexionRedirect('warning', 'Connectez-vous');

        $question = (new QuestionRepository())->select($_GET['id']);
        if(ConnexionUtilisateur::getLoginUtilisateurConnecte()!=$question->getIdOrganisateur()){
            MessageFlash::ajouter('danger', 'Vous n\'êtes pas organisateur de cette question!');
            self::redirection('frontController.php?controller=question&action=read&id=' . htmlspecialchars($question->getId()));
        }
        $role = $_GET['role'];
        $groupes = $_POST['groupe'];
        (new QuestionRepository())->addGroupesQuestion($groupes, $question->getId(), $role);
        MessageFlash::ajouter('succes', 'Les groupes ont bien été ajouté!');
        self::redirection('frontController.php?controller=question&action=read&id='.$question->getId());
    }


    public static function likeSection()
    {
        $idSection = $_GET['id'];
        $idQuestion = $_GET['idQuestion'];

        if (ConnexionUtilisateur::estConnecte()) {
            $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            $sectionRepository = new SectionRepository();
            if ($sectionRepository->userALike($idSection, $idUser)) {
                $sectionRepository->deliker($idSection, $idUser);
            } else {
                $sectionRepository->liker($idSection, $idUser);
            }
        }
        self::redirection('frontController.php?controller=question&action=read&id=' . $idQuestion);


    }
}