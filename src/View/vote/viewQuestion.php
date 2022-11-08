<?php
use App\Model\DataObject\Question;
/** @var Question $question */
?>
<div class="block">
    <div class="text-box">
        <p>
            <?php
            echo '<a href=frontController.php?controller=' . $_GET['controller'] . '&action=modifyQuestion&id=' . rawurlencode($question->getId()) . '>modifier</a>' .
            '<a href=frontController.php?controller= '. $_GET['controller'] . '&action=deleteQuestion&id='. rawurlencode($question->getId()) . '>supprimer</a>';
            ?>
            </p>
    <p>
        <h1><?=$question->getIntitule()?></h1>
        <?=$question->getDescription()?>
    </p>
        <?php foreach ($question->getSections() as $section) {
            echo "<h3>" . $section->getIntitule() . "</h3>";
            echo "<p>" . $section->getDescription() . "</p>";
        }?>

    </div>
</div>
