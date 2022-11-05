<?php
use App\Model\DataObject\Question;

/** @var Question $question */
?>
<form method="post" action="frontController.php?action=QuestionModified&idQuestion=<?=$question->getId()?>">
    <fieldset>
        <legend>modification de la question</legend>

        <p>
            <input type="text" id="tq" name="titreQuestion" value="<?=$question->getIntitule()?>">
        </p>

        <p>
            <input type="text" id="dq" name="descriptionQuestion" value="<?=$question->getDescription()?>">
        </p>

        <?php
        $sections = $question->getSections();
        for($i=0; $i<count($sections); $i++){
            $section = $sections[$i];
            require 'modifySection.php';
        }
        ?>

        <input type="submit" value="envoyer">
    </fieldset>
</form>