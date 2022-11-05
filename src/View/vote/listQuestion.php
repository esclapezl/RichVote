<?php
use App\Model\DataObject\Question;
/** @var Question[] $questions*/
?>
<div class="block">
    <div class="text-box">
        <h3>Liste des Questions :</h3>
    <ul>
        <?php
        foreach ($questions as $question){
            echo "<li>".$question->getIntitule()."</li>";
        }
        ?>
    </ul>
    </div>
</div>