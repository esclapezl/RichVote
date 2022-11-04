<?php
use App\Model\DataObject\Question;

/** @var Question $question */
?>
<form method="get" action="frontController.php?action=QuestionModified">
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
            require 'section.php';
        }
        ?>
    </fieldset>
</form>