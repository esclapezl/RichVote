<?php
use App\Model\DataObject\Proposition;
/** @var Proposition $proposition */
?>
<div class="block">
    <div class="text-box">
        <p>
            <?php
            echo '<a href=frontController.php?controller=proposition&action=update&id=' . rawurlencode($proposition->getIdProposition()) . '>modifier</a>' .
                '<a href=frontController.php?controller=proposition&action=delete&id='. rawurlencode($proposition->getIdProposition()) . '>supprimer</a>';
            ?>
        </p>
        <p>
        <h1><?=$proposition->getIntitule()?></h1>
        </p>
        <?php foreach ($proposition->getSectionsTexte() as $texte) {
            echo "<p>" . $texte . "</p>";
        }?>
    </div>
</div>
