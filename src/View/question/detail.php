<?php
use App\Model\DataObject\Question;
use App\Model\Repository\UserRepository;
/** @var Question $question */

$phases=(new \App\Model\Repository\PhaseRepository())->getPhasesIdQuestion($question->getId());

$typePrecisPhase= $question->getCurrentPhase()->getType();
$typePhase = 'placeHolder';
switch ($typePrecisPhase) {
    case 'consultation':
        $typePhase= 'En cours de consultation';
        break;
    case 'scrutinMajoritaire' || 'scrutinMajoritairePlurinominal' :
        $typePhase= 'Voter juste Ici';
        break;
    case 'termine':
        echo "Vote(s) terminé(s)";
        break;
}


?>

<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <div><a class="optQuestion" id="fleche" href=frontController.php?controller=question&action=readAll>↩</a>
                <h1><?=htmlspecialchars($question->getIntitule())?></h1></div>

                    <?php
                    use \App\Lib\ConnexionUtilisateur;
                    use App\Model\Repository\VoteRepository;
                    if(ConnexionUtilisateur::estConnecte()){
                        $idQuestion = $question->getId();
                        $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                        echo "<div id='col'>";
                        if((new UserRepository())->getRoleQuestion(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $question->getId())!=null){
                            echo "<h3> Vous êtes ".(new UserRepository())->getRoleQuestion($idUser, $idQuestion)." sur cette question.</h3>";
                        }
                        else{
                            echo "<h3>Vous n'avez pas de rôle sur cette question.</h3>";
                        }




                        if(VoteRepository::peutVoter($idUser, $idQuestion) && $typePrecisPhase!="termine" && $typePrecisPhase!="consultation") {
                            $typePrecisPhase = ucfirst($typePrecisPhase);
                            echo "<a href=frontController.php?controller=vote&action=voter$typePrecisPhase&idQuestion=$idQuestion><h2>Vote en cliquant ici</h2></a>";
                        }
                        else if((new UserRepository())->getRoleQuestion(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $question->getId())==null  && $typePhase== 'Voter juste Ici'){
                            echo "<a href=frontController.php?controller=vote&action=demandeAcces&idQuestion=". rawurlencode($idQuestion) .">
                                <h2>Vous souhaitez voter?</h2></a>";
                        }
                        else if($question->getCurrentPhase()->getType()=="termine" || $question->getCurrentPhase()->getType()=="consultation"){
                            echo '<h2>' . $typePhase . '</h2>';
                        }
                        else{
                            echo '<h2>Vote indisponible</h2>';
                        }

                        echo '</div>';


                    }
                    else{
                      echo '<div><h2 id="desc">Aucunes informations disponibles.</h2></div>';
                    }?>

                </div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>


            <?php
            if(ConnexionUtilisateur::estConnecte()) {
                if ((new UserRepository())->getRoleQuestion(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $question->getId()) == "organisateur") {
                    echo '<div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Voir les propositions</a>
    <a class="optQuestion" href=frontController.php?controller=question&action=delete&id=' . rawurlencode($question->getId()) . '>Supprimer</a></div>'

                        . '<div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=create&id=' . rawurlencode($question->getId()) . '>
    Créer proposition</a><a class="optQuestion" href=frontController.php?controller=question&action=update&id=' . rawurlencode($question->getId()) . '>Modifier</a></div>' . '
                        
                        <div class="ligneExt">
                        <div>
                        <div class="col"> <a class="optQuestion" id="addVotants" href="frontController.php?controller=question&action=debutPhase&id=' . $question->getId() .'">Lancer phase</a>
                        <a class="optQuestion" id="addVotants" href="frontController.php?controller=question&action=finPhase&id=' . $question->getId() .'">Finir phase</a>
                        </div>
                    
                        </div>
                            <div id="col">
                         
                                <a class="optQuestion" id="addVotants" href="frontController.php?controller=question&action=addVotantToQuestion&id=' . $question->getId() .'">Ajouter des votants</a>
                                <p id="petit">Il y a n demandes de votes</p>
                                <div class="ligne">
                            </div>
                        </div>
                        </div>';
                } else {
                    echo '<div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Voir les propositions</a></div>';
                }





                //TIMELINE
                if(!(empty($phases))){


                    echo '<h3 id="prog">Progression :</h3><div class="ligneP"></div>';

                    echo '<div class="timeline">
    
                        <p id="pet">'.htmlspecialchars(ucfirst(($question->getCurrentPhase())->getType())) .'</p>';
                    $widthLigne=(80/(sizeof($phases)+1));
                    foreach ($phases as $phase){
                        echo '<style>.ligneTbis{width: '.$widthLigne.'%;}</style>';
                        echo '<div class="ligneT" style="background: transparent"></div><p id="pet">'.htmlspecialchars(ucfirst($phase->getType())).'</p>';
                    }
                    echo '<div class="ligneT" style="background: transparent"></div><p id="pet">Conclusion</p></div>';

                    //DEBUT
                    echo '<div class="timeline"><a href=frontController.php?controller=vote&action=' . rawurlencode($question->getCurrentPhase()->getType()) . ' id="circle"></a>';

                    $widthLigne= 90/(sizeof($phases)+1);

                    foreach ($phases as $phase){
                        echo '<style>.ligneT{width: '.$widthLigne.'%;}</style>';
                        echo '<div class="ligneT"></div><a href=frontController.php?controller=vote&action=' . rawurlencode($phase->getType()) . ' id="circle"></a>';
                    }
                    //FIN
                    echo '<div class="ligneT"></div><a href=frontController.php?controller=vote&action=' . rawurlencode($question->getCurrentPhase()->getType()) . ' id="circle"></a></div>';


                    echo '<div class="timeline" id="margintimeline"><p id="pet">'.htmlspecialchars($question->dateToString($question->getDateCreation())).'</p>';
                    $widthLigne=(70/(sizeof($phases)+1));
                    foreach ($phases as $phase){
                        echo '<style>.ligneTbis{width: '.$widthLigne.'%;}</style>';
                        echo '<div class="ligneTbis" style="background: transparent"></div><p id="pet">Du '.htmlspecialchars($question->dateToString($phase->getDateDebut())).' au
                        '.htmlspecialchars($question->dateToString($phase->getDateFin())).'</p>';
                    }
                    echo '<div class="ligneTbis" style="background: transparent"></div><p id="pet">'.htmlspecialchars($question->dateToString($question->getDateFermeture())).'</p></div>';
                }


                echo '<div class="descP"></div>'
                    . ' <div class="ligneExt"><h2 id="desc">DESCRIPTION</h2></div>
                        <p class="descG">' . htmlspecialchars($question->getDescription()) . '</p>';

        foreach ($question->getSections() as $section) {
            echo '<div class="ligneExt"><h3>' . ucfirst(htmlspecialchars($section->getIntitule())) . "</h3></div>";
            echo "<div class='ligne'></div> <p class='descP'>" . htmlspecialchars($section->getDescription()) . "</p>";
        }
        }
            else{
                echo "<div class='LigneCent'><h3>Vous n'êtes pas connecté. Connectez-vous pour plus d'informations !</h3></div>";
            }
            ?>

    </div>
</div>
