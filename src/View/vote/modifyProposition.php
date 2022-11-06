<?php
use App\Model\DataObject\Proposition;
/** @var Proposition $proposition */
?>

<div class="block">
    <div class="text-box">
        <form method="post" action="frontController.php?controller=<?= $_GET['controller']?>&action=propositionModified&id=<?=$proposition->getIdProposition()?>">
            <fieldset>
                <legend>modification de la proposition</legend>

                <?php
                $sectionsText = $proposition->getSectionsTexte();
                foreach ($sectionsText as $idSection=>$text){
                    echo '<input type="texte" id=i' . $idSection . ' name=texte[' . $idSection . '] value=' . $text . '>';
                }
                ?>

                <input type="submit" value="envoyer">
            </fieldset>
        </form>
    </div>
</div>