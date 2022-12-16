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
                            $scorebis=null;
                            $classement=0;
                            echo '<h2>Résultats Finaux</h2><h3 id="quest">' . htmlspecialchars($question->getIntitule()) . '</h3>

                    <div class="ligne"></div>
                    <br>';
                            $scoretotal=0;
                            $cptligne=0;
                            foreach ($propositions as $proposition) {
                                $idProposition = $proposition->getId();
                                $score = $scores[$idProposition];
                                $scoretotal+=$score;
                            }


                            foreach ($propositions as $proposition) {
                                $classement++;
                                $idProposition = $proposition->getId();
                                $score = $scores[$idProposition];
                                $widthLigne=($score/$scoretotal)*100;
                                $widthLigne=round($widthLigne);



                                    echo "<h3>" . $classement . ". " . ucfirst(htmlspecialchars($proposition->getIntitule())) . " avec un score de : " . $score . "</h3>";
                                    echo '<style>.lineresults'.$cptligne.'{width: '.$widthLigne.'%; display: flex;background: white;height: 8px;
                                         ;border-radius: 20px}</style>';
                                echo '<div class="ligneExt"><div class="lineresults'.$cptligne.'"></div><p id="petit">'.$widthLigne.' %</p></div>';
                                $cptligne++;




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





