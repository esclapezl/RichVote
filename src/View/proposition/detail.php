<?php
use App\Model\DataObject\Proposition;
/** @var Proposition $proposition */
?>
<div class="block">
    <div class="text-box">
        <p>
        <h1><?=$proposition->getIdProposition()?></h1>
        </p>
        <?php foreach ($proposition->getSectionsTexte() as $texte) {
            echo "<p>" . $texte . "</p>";
        }?>
    </div>
</div>
