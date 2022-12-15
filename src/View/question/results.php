<?php
use \App\Lib\ConnexionUtilisateur;
use App\Model\Repository\VoteRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\DataObject\Question;
use App\Model\Repository\UserRepository;
use App\Model\Repository\PhaseRepository;
use App\Model\Repository\PropositionRepository;
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
            if(ConnexionUtilisateur::estConnecte()) {
                if(/*(new QuestionRepository())->estFini($question->getId())*/1==1) {


                    echo '<div class="results">';

                    $bool = 0;
                    foreach ($phases as $phase) {
                        if ($phase->getType() != "consultation") {
                            $bool = 1;
                        }
                    }
                    $cpt = 0;
                    if ($bool == 1) {
                        if ($phases[count($phases) - 1]->getType() == "consultation") {
                            echo "<p>Il n'y a pas eu de vote sur cette question.</p>";
                        } else {
                            $scores = [];
                            $propositions = [];

                            $propositionsScore = (new PropositionRepository())->selectAllWithScore($phase->getId());
                            foreach ($propositionsScore as $proposition) {
                                $propositions[] = $proposition[0];
                                $scores[$proposition[0]->getId()] = $proposition[1];
                            }
                            $cpt = 0;

                            foreach ($propositions as $proposition) {
                                $idProposition = $proposition->getId();
                                $score = $scores[$idProposition];
                                $widthLigne=($score*100)/sizeof($scores);
                                if ($cpt == 0) {
                                    echo "<h1>" . $proposition->getIntitule() . " l'emporte !</h1><h3>avec un score de " . $score . ".</h3>";
                                    $cpt++;
                                } else {
                                    echo '<style>.lineresults{width: '.$widthLigne.'%;}</style>';
                                    echo '<div class="lineresults"></div>';
                                    echo "<p id='petit'>" . $proposition->getIntitule() . " avec un score de : " . $score . "</p>";
                                }

                            }
                        }

                    } else {
                        echo "<h3>Il n'y a pas eu de phases de vote sur cette question.</h3>";

                    }
                }
                else{
                    echo "<div class='LigneCent'><h3>La question n'est pas finit, revenez plus tard !</h3></div>";
                }

            }else{
                echo "<div class='LigneCent'><h3>Vous n'êtes pas connecté. Connectez-vous pour plus d'informations !</h3></div>";
            }

            ?>
        </div>

    </div>





