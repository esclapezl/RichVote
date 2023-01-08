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
    case 'scrutinMajoritaire' || 'scrutinMajoritairePlurinominal' || 'jugementMajoritaire' :
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
                        else if($roleQuestion==null && ($typePrecisPhase!="termine" || $typePrecisPhase!="consultation")){
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
                    echo '<fieldset><h2>Interface organisateur</h2><br><div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Voir les propositions</a>';
                    echo '<a class="optQuestion" href=frontController.php?controller=question&action=readResult&id=' . rawurlencode($question->getId()) . '>Résultats</a>';
                        echo '</div><div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=create&id=' . rawurlencode($question->getId()) . '>
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
                         
                                <a class="optQuestion" id="addVotants" href="frontController.php?controller=question&action=addUsersToQuestion&id=' . $question->getId() .'">Ajouter des utilisateurs</a>
                                <p id="petit">Il y a ' . sizeof($demandes) . ' demande(s) de vote(s)</p>
                                <a class="optQuestion" id="askVotants" href=frontController.php?controller=question&action=readDemandeVote&id='.$question->getId().'> Voir les demandes de votes</a>
                        
                                <div class="ligne">
                            </div>
                        </div>
                        </div>
                        </fieldset>';
                } else if($roleQuestion=="responsable"){
                    echo '<div class="ligneExt">
                            <a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Voir les propositions</a>';
                            echo '<a class="optQuestion" href=frontController.php?controller=question&action=readResult&id=' . rawurlencode($question->getId()) . '>Résultats</a>';

                    echo '</div><div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=create&id=' . rawurlencode($question->getId()) . '>
                        Créer proposition</a></div>';
                }
                else{
                    echo '<div class="ligneExt">
                            <a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Voir les propositions</a>';
                            echo '<a class="optQuestion" href=frontController.php?controller=question&action=readResult&id=' . rawurlencode($question->getId()) . '>Résultats</a>';
                    echo "</div><a class='optQuestion' href=frontController.php?controller=question&action=demandeRoleQuestion&role=responsable&id=". rawurlencode($idQuestion) .">
                                Demander à écrire une proposition</a>";
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
                    echo '<div class="timeline"><a href=frontController.php?controller=vote&action=' . rawurlencode($question->getPhases()[0]->getType()) . ' id="circle"></a>';

                    $widthLigne= 90/(sizeof($phases)+1);

                    foreach ($phases as $phase){
                        echo '<style>.ligneT{width: '.$widthLigne.'%;}</style>';
                        echo '<div class="ligneT"></div><a href=frontController.php?controller=question&action=readResultPhase&id=' . $question->getId() . '&idPhase='. $phase->getId() .' id="circle"></a>';
                    }
                    //FIN
                    echo '<div class="ligneT"></div><a href=frontController.php?controller=question&action=readResult&id=' . $question->getId() . ' id="circle"></a></div>';


                    echo '<div class="timeline" id="margintimeline"><p id="pet">'.htmlspecialchars($question->dateToString($question->getDateCreation())).'</p>';
                    $widthLigne=(70/(sizeof($phases)+1));
                    foreach ($phases as $phase){
                        echo '<style>.ligneTbis{width: '.$widthLigne.'%;}</style>';
                        echo '<div class="ligneTbis" style="background: transparent"></div><p id="pet">Du '.htmlspecialchars($question->dateToString($phase->getDateDebut())).' au
                        '.htmlspecialchars($question->dateToString($phase->getDateFin())).'</p>';
                    }
                    echo '<div class="ligneTbis" style="background: transparent"></div><p id="pet">'.htmlspecialchars($question->dateToString($question->getDateFermeture())).'</p></div>';
                }

                echo '<br><div class="description">';
                echo '<div class="ligneAlign"><h1>Description :</h1></div><div class="ligne"></div><br><p id="sections">
                        ' .$question->getDescription() . '</p><div class="descG"></div>';
                echo '<div class="ligneCent"><div class="ligne"></div></div><div class="ligneCent"><h1>Sommaire</h1></div>';
                $y=0;
                foreach ($question->getSections() as $section) {
                    $y++;
                    echo '<div class="ligneCent"><h3 id="sections">' . $y . '. ' . ucfirst(htmlspecialchars($section->getIntitule())) . "</h3></div>";
                }
                echo '<br><div class="ligneCent"><div class="ligne"></div></div><br><br>';

                $i=0;
                foreach ($question->getSections() as $section) {
                    $i++;
                    echo '<div class="ligneExt"><h3 id="sections">'. $i .'. ' . ucfirst(htmlspecialchars($section->getIntitule())) . "</h3></div>";
                    echo "<div class='ligne'></div><p>" . $section->getDescription();
                    echo '<br>';
                    //echo '<div><a href="frontController.php?controller=question&action=likeSection&id='.$section->getId().'&idQuestion='.$_GET['id'].'"><img src="../assets/img/icons8-jaimeBlanc.png"></a>     '.$section->getNbLikes().'</div></li>';
                    echo '<br>';
                    echo "</p><br>";

                }
                echo '</div>';
        }
            else{
                echo "<div class='LigneCent'><h3>Vous n'êtes pas connecté. Connectez-vous pour plus d'informations !</h3></div>";
            }
            ?>

    </div>
</div>
