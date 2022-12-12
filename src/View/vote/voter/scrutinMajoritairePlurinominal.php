<?php
/*
 * faire la vue en deux  parties:
 * - une qui affiche les propositions votés pour
 * - l'autre qui affiche les votes contre
 * - une dernière qui affiche les neutres?
 */
use App\Model\DataObject\Proposition;
/**
 * @var Proposition[] $propositionsPour
 * @var Proposition[] $propositionsContre
 * @var Question $question
 */?>

<div class="block">
    <div class="text-box">
        <form method="post" action="frontController.php?controller=vote&action=scrutinMajoritairePlurinominalVoted&idQuestion=<?=$question->getId()?>">
            <div class="ligneCent"> <h1>Vous pouvez voter.</h1></div>

            <div class="ligneCent"><div class="ligne"></div></div><br>
            <div class="ligneCent"> <h3>Votre choix restera confidentiel.</h3></div>
            <div class="descG"></div>

            <h1> Vous avez voté pour: </h1>
            <?php
            foreach ($propositionsPour as $propalPour){
                $idProposition = $propalPour->getId();
                $intituleProposition = htmlspecialchars($propalPour->getIntitule());
                echo "<button name='idPropositionContre' value='$idProposition'>$intituleProposition</button>";
            }
            ?>

            <h1> Vous avez voté contre: </h1>
            <?php
            foreach ($propositionsContre as $propalContre){
                $idProposition = $propalContre->getId();
                $intituleProposition = htmlspecialchars($propalContre->getIntitule());
                echo "<button name='idPropositionPour' value='$idProposition'>$intituleProposition</button>";
            }
            ?>
            <div class="descG"></div>
            <div class="ligneCent"><button type="submit" class="opt"><img src="../assets/img/icon-vote.png"></button></div>
        </form>
    </div>
</div>