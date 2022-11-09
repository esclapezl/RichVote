<?php
use App\Model\DataObject\Question;
/** @var Question $question */
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1><?=$question->getIntitule()?></h1> <h3>Détail de la question</h3></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt"><?php
                echo
                    '<a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Liste des propositions</a>'
                     .
                    '<a class="optQuestion" href=frontController.php?controller=question&action=delete&id='. rawurlencode($question->getId()) . '>Supprimer</a>';

            ?>
                 </div>
        <div class="ligneExt"><?php echo '<a class="optQuestion" href=frontController.php?controller=proposition&action=create&id=' . rawurlencode($question->getId()) . '>Créer proposition</a>' .
                    '<a class="optQuestion" href=frontController.php?controller=question&action=update&id=' . rawurlencode($question->getId()) . '>Modifier</a>';
?></div>
        <div class="descP"></div>

    <div class="ligneExt"><h3>Description :</h3></div>
        <p class="descG"><?=$question->getDescription()?></p>

        <?php foreach ($question->getSections() as $section) {
            echo '<div class="ligneExt"><h3>' . ucfirst($section->getIntitule()) . "</h3></div>";
            echo "<div class='ligne'></div> <p class='descP'>" . $section->getDescription() . "</p>";
        }?>

    </div>
</div>
