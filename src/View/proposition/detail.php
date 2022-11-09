<?php
use App\Model\DataObject\Proposition;
/** @var Proposition $proposition */
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1><?=$proposition->getIntitule()?></h1> <h3>Détail de la proposition</h3></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt">
            <?php
            echo '<a href=frontController.php?controller=proposition&action=update&id=' . rawurlencode($proposition->getIdProposition()) . ' class="optQuestion">Modifier</a>' .
                '<a href=frontController.php?controller=proposition&action=delete&id='. rawurlencode($proposition->getIdProposition()) . ' class="optQuestion">Supprimer</a>';
            ?>
        </div>

        <?php
        foreach ($proposition->getSectionsTexte() as $texte) {
            echo '<p><h3>Section</h3><textarea type="text" rows="4" cols="100">' . $texte . '</textarea></p>';
        }?>


    </div>
</div>
