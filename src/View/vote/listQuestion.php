<?php
use App\Model\DataObject\Question;
/** @var Question[] $questions*/
?>
<div class="block">
    <div class="text-box">
        <h3>Liste des Questions :</h3>
        <p>
            <a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=createQuestion">Créer une Question</a>
        </p>
    <ul>
        <?php
        foreach ($questions as $question){
            echo "<li>".$question->getIntitule()."<a href='frontController.php?controller=" . $_GET['controller'] . "&action=viewQuestion&id=" . $question->getId() . "'>voir</a>" ."- <a href='frontController.php?controller=" . $_GET['controller'] . "&action=modifyQuestion&id=" . $question->getId() . "'>modifier</a></li>";
        }
        ?>
    </ul>
    </div>
</div>