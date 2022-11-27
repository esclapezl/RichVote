<?php
use App\Model\DataObject\Question;

/** @var Question $question */
?>
<div class="block">
    <div class="text-box">
    <form method="post" action="frontController.php?controller=question&action=updated&id=<?=$question->getId()?>">
        <fieldset>
            <div class="descP"></div>
            <h1><legend>Votre Question</legend></h1>
            <div class="ligneCent"><div class="ligne"></div></div>
            <div class="descG"></div>

            <p>
            <h3>Question :</h3>
            <input type="text" id="tq" name="titreQuestion" size="50" value="<?=ucfirst($question->getIntitule())?>">
            <div class="descP"></div>
            </p>
            <p>
            <h3>Description :</h3>
            <textarea type="text" id="dq" name="descriptionQuestion" rows="4" cols="100"><?=ucfirst($question->getDescription())?></textarea>
            </p>
            <div class="descG"></div>
            <?php

            $phases = (new \App\Model\Repository\PhaseRepository())->getPhasesIdQuestion($question->getId());
            for($i=0; $i<count($phases); $i++){
                $phase = $phases[$i];
                echo '<h3> Phase '.$i + 1 .'</h3>';
                require __DIR__ .'/../phase/update.php';
                echo '<div class="descP"></div>';
            }

            $sections = $question->getSections();
            for($i=0; $i<count($sections); $i++){
                $section = $sections[$i];
                echo '<h3> Section '.$i + 1 .'</h3>';
                require __DIR__ .'/../section/update.php';
                echo '<div class="descP"></div>';
            }
            ?>

            <div class="ligneCent"> <input class="optQuestion" type="submit" value="sauvegarder"/></div>
        </fieldset>
    </form>
    </div>
</div>