<?php
use App\Model\DataObject\Proposition;
/** @var Proposition $proposition */
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1><!--=$proposition->getIntitule()?-->Proposition titre</h1> <h3>Détail de la proposition</h3></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt">
            <?php
            echo '<a href=frontController.php?controller=proposition&action=update&id=' . rawurlencode($proposition->getIdProposition()) . ' class="optQuestion">modifier</a>' .
                '<a href=frontController.php?controller=proposition&action=delete&id='. rawurlencode($proposition->getIdProposition()) . ' class="optQuestion">supprimer</a>';
            ?>
        </div>
        <p>
        <h1><?=$proposition->getIdProposition()?></h1>
        </p>
        <?php foreach ($proposition->getSectionsTexte() as $texte) {
            echo "<p>" . $texte . "</p>";
        }?>
    </div>
</div>
