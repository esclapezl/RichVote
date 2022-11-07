<?php
use App\Model\DataObject\Question;

/** @var Question $question */
?>
<div class="block">
    <div class="text-box">
    <form method="post" action="frontController.php?controller=<?= $_GET['controller']?>&action=questionModified&id=<?=$question->getId()?>">
        <fieldset>
            <h3><legend>Modification de la question</legend></h3>
            <p>
                Titre :  <input type="text" id="tq" name="titreQuestion" value="<?=$question->getIntitule()?>">

                Description :  <input type="text" id="dq" name="descriptionQuestion" value="<?=$question->getDescription()?>">
            </p>
            <?php
            $sections = $question->getSections();
            for($i=0; $i<count($sections); $i++){
                $section = $sections[$i];
                echo '<p>'.$i + 1 .'</p>';
                require 'modifySection.php';
            }
            ?>

            <input type="submit" value="envoyer" id="formulaire">
        </fieldset>
    </form>
    </div>
</div>