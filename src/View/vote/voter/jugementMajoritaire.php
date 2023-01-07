<?php
/**
 * @var Question $question
 * @var array $propositionsWithScore
 */

use App\Model\DataObject\Proposition;
use App\Model\DataObject\Question;

?>
<div class="block">
    <div class="text-box">
        <form method="post" action="frontController.php?controller=vote&action=jugementMajoritaireVoted">
            <?php
            foreach ($propositionsWithScore as $propositionWithScore){
                $proposition = $propositionWithScore[0];
                $score = $propositionWithScore[1];
                $intituleProposition = $proposition->getIntitule();
                $idProposition = $proposition->getId();
                $nameScore = "score[$idProposition]";
                echo "
        <label for='$nameScore'>$intituleProposition</label>
        <select name='$nameScore'>
        <option value='0'>Non intéressé</option>
        <option value='1'>Insuffisant</option>
        <option value='2'>Passable</option>
        <option value='3'>Assez bien</option>
        <option value='4'>Bien</option>
        <option value='5'>Très bien</option>
        </select>";
            }
            ?>
            <input type="submit" name="Valider votre choix">
        </form>
    </div>
</div>