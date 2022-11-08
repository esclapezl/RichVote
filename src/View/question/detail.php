<?php
use App\Model\DataObject\Question;
/** @var Question $question */
?>
<div class="block">
    <div class="text-box">
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
