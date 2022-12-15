<?php
use \App\Lib\ConnexionUtilisateur;
use App\Model\Repository\VoteRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\DataObject\Question;
use App\Model\Repository\UserRepository;
/** @var Question $question */

$phases=(new \App\Model\Repository\PhaseRepository())->getPhasesIdQuestion($question->getId());
?>


<div class="block">
    <div class="text-box">
        <a class="optQuestion" id="fleche" href=frontController.php?controller=question&action=read&id=<?=$question->getId()?>>↩</a>
                <div class="column">

                    <h3><?=htmlspecialchars($question->getIntitule())?></h3>
                    <br>
                    <div class="ligne"></div>
                    <br>
                    <h1>Résultats Finaux</h1>
                    <div class="descG"></div>
                    <?php
                        if(!empty($phases)){

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


                            echo '<div class="timeline" id="smallmargintimeline"><p id="pet">'.htmlspecialchars($question->dateToString($question->getDateCreation())).'</p>';
                            $widthLigne=(70/(sizeof($phases)+1));
                            foreach ($phases as $phase){
                                echo '<style>.ligneTbis{width: '.$widthLigne.'%;}</style>';
                                echo '<div class="ligneTbis" style="background: transparent"></div><p id="pet">Du '.htmlspecialchars($question->dateToString($phase->getDateDebut())).' au
        '.htmlspecialchars($question->dateToString($phase->getDateFin())).'</p>';
                            }
                            echo '<div class="ligneTbis" style="background: transparent"></div><p id="pet">'.htmlspecialchars($question->dateToString($question->getDateFermeture())).'</p></div>';
                        }

                    ?>





        <div class="results">
            <?php
            $cpt=0;
                if((count($phases))>1){
                    foreach ($phases as $phase) {
                        $cpt = $cpt + 1;
                        echo '<h3>Phase ' . $cpt . '</h3>';
                        if ($phase->getType() == "consultation") {
                            echo "<p>Il n'y a pas de vote sur cette phase.</p>";

                        }
                    }
                }
                else{
                    if($phases[0]->getType()=="consultation"){
                        echo "<h3>Il n'y a pas eu de phases de vote sur cette question.</h3>";

                    }
                }
            ?>

        </div>

    </div>




                <?php

                if(ConnexionUtilisateur::estConnecte()) {
                    //if($question->estarchive()){


                }
                else{
                    echo "<div class='LigneCent'><h3>Vous n'êtes pas connecté. Connectez-vous pour plus d'informations !</h3></div>";
                }

                ?>


