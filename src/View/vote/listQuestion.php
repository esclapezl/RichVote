<?php
use App\Model\DataObject\Question;
/** @var Question[] $questions*/
?>

<ul>
    <?php
    foreach ($questions as $question){
        echo "<li>".$question->getIntitule()."</li>";
    }
    ?>
</ul>
