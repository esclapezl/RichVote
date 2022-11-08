<?php
use App\Model\DataObject\Question;
/** @var Question[] $questions*/
?>
<div class="block">
    <div class="text-box">
        <h3>Liste des Questions :</h3>
        <p>
            <a href="frontController.php?controller=question&action=create">Créer une Question</a>
        </p>
    <ul>
        <?php
        foreach ($questions as $question){
            echo "<li>".htmlspecialchars($question->getIntitule())."<a href='frontController.php?controller=question&action=read&id=" . rawurlencode($question->getId()) . "'>voir</a>"
                ."- <a href='frontController.php?controller=question&action=update&id=" . rawurlencode($question->getId()) . "'>modifier</a>" .
                "- <a href='frontController.php?controller=question&action=delete&id=" . rawurlencode($question->getId()) . "'>supprimer</a></li>";
        }
        ?>
    </ul>
    </div>
</div>