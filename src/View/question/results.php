<?php
use \App\Lib\ConnexionUtilisateur;
use App\Model\DataObject\Phase;
use App\Model\DataObject\Question;
use App\Model\Repository\PropositionRepository;
/** @var Question $question
 * @var array $propositionsScore
 *  une array [Proposition, int score]
 * @var Phase $phase
 */
?>





<div class="block">
    <div class="text-box">
        <a class="optQuestion" id="fleche" href=frontController.php?controller=question&action=read&id=<?=$question->getId()?>>↩</a>
        <div class="column">
            <div class="results">
                <?php
                if ($phase->getType() == "consultation") {
                    echo "<p>Il n'y a pas eu de vote sur cette question.</p>";
                } else {
                    $scores = [];
                    $propositions = [];
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
                        echo '<style>.lineresults'.$cptligne.'{width: '.$widthLigne.'%; display: flex;background: white;height: 8px;border-radius: 20px}</style>';
                        echo '<div class="ligneExt"><div class="lineresults'.$cptligne.'"></div><p id="petit">'.$widthLigne.' %</p></div>';
                        $cptligne++;
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>