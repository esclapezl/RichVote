<?php
use App\Model\DataObject\Question;
use App\Model\DataObject\Demande;
use App\Model\DataObject\Phase;
use \App\Lib\ConnexionUtilisateur;

/** @var Question $question
 * @var Demande[] $demandes
 * @var Phase[] $phases
 * @var string $roleQuestion
 * @var bool $peutVoter
 */
if(!isset($demandes)){
    $demandes=[];
}

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
                    if(ConnexionUtilisateur::estConnecte()){
                        $idQuestion = $question->getId();
                        echo "<div id='col'>";
                        if($roleQuestion!=null){
                            echo "<h3> Vous êtes ".$roleQuestion." sur cette question.</h3>";
                        }
                        else{
                            echo "<h3>Vous n'avez pas de rôle sur cette question.</h3>";
                        }





                        if($peutVoter && $typePrecisPhase!="termine" && $typePrecisPhase!="consultation") {
                            $typePrecisPhase = ucfirst($typePrecisPhase);
                            echo "<a href=frontController.php?controller=vote&action=voter$typePrecisPhase&idQuestion=$idQuestion><h2>Vote en cliquant ici</h2></a>";
                        }
                        else if($roleQuestion==null){
                            echo "<a href=frontController.php?controller=question&action=demandeRoleQuestion&role=votant&id=". rawurlencode($idQuestion) .">
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
                if ($roleQuestion == "organisateur") {
                    echo '<div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Voir les propositions</a>
     <a class="optQuestion" href=frontController.php?controller=question&action=readResult&id=' . rawurlencode($question->getId()) . '>Résultats du Tirage</a>
</div>'


                        . '<div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=create&id=' . rawurlencode($question->getId()) . '>
    Créer proposition</a>
    <div class="ligneAlign"><a href=frontController.php?controller=question&action=update&id=' . rawurlencode($question->getId()) . '><img class="icons" alt="modifier" src="../assets/img/icons8-crayon-48.png"></a> <a href=frontController.php?controller=question&action=delete&id=' . rawurlencode($question->getId()) . '><img class="icons" id="poubelle" alt="supprimer question" src="../assets/img/icons8-poubelle.svg"></a>
    </div>
    </div>' . '
                        
                        <div class="ligneExt">
                        <div>
                        <div class="ligneAlign"> <a class="optQuestion" id="phases" href="frontController.php?controller=question&action=debutPhase&id=' . $question->getId() .'">Debut phase</a>
                        <a class="optQuestion" id="phases" href="frontController.php?controller=question&action=finPhase&id=' . $question->getId() .'">Fin phase</a>
                        </div>
                    
                        </div>
                            <div id="col">
                         
                                <a class="optQuestion" id="addVotants" href="frontController.php?controller=question&action=addVotantToQuestion&id=' . $question->getId() .'">Ajouter des votants</a>
                                <p id="petit">Il y a ' . sizeof($demandes) . ' demande(s) de vote(s)</p>
                                <a class="optQuestion" id="askVotants" href=frontController.php?controller=question&action=readDemandeVote&id='.$question->getId().'> Voir les demandes de votes</a>
                        
                                <div class="ligne">
                            </div>
                        </div>
                        </div>';
                } else {
                    echo '<div class="ligneExt">
                            <a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Voir les propositions</a>
                            <a class="optQuestion" href=frontController.php?controller=question&action=readResult&id=' . rawurlencode($question->getId()) . '>Résultats de la précédente phase de vote</a>
                        </div>';

                    $action = 'frontController.php?controller=question&action=demandeRoleQuestion&id='.$idQuestion;
                    echo "<form method='get' action='$action'>
                            <select name='role'>
                                <option value='votant'>Devenir votant</option>
                                <option value='responsable'>Devenir responsable</option>
                            </select>
                            <input type='hidden' name='controller' value='question'>
                            <input type='hidden' name='action' value='demandeRoleQuestion'>
                            <input type='hidden' name='id' value='$idQuestion'>
                            <input type='submit' value='Demander'/>
                          </form>";
                }





                //TIMELINE
                if(!(empty($phases))){


                    echo '<h3 id="prog">Progression :</h3><div class="ligneP"></div>';

                    echo '<div class="timeline">
    
                        <p id="pet">Introduction</p>';
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


                echo '<br>'
                    . ' <div class="ligneExt"><h2 id="desc">DESCRIPTION</h2></div>
                        <p>' .$question->getDescription() . '</p><div class="descG"></div>';
        foreach ($question->getSections() as $section) {
            echo '<div class="ligneExt"><h3>' . ucfirst(htmlspecialchars($section->getIntitule())) . "</h3></div>";
            echo "<div class='ligne'></div> " . $section->getDescription() . "<br>";
        }
        }
            else{
                echo "<div class='LigneCent'><h3>Vous n'êtes pas connecté. Connectez-vous pour plus d'informations !</h3></div>";
            }
            ?>

    </div>
</div>
