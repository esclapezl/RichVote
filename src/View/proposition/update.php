<?php
use App\Model\DataObject\Proposition;
/** @var Proposition $proposition */
?>

<div class="block">
    <div class="text-box">
        <form method="post" action="frontController.php?controller=proposition&action=updated&id=<?=$proposition->getIdProposition()?>">
            <fieldset>
                <legend>Votre proposition</legend>
                <label>Titre</label>
                <input type="text" id="t" name="intitule" value='<?=$proposition->getIntitule()?>' >
                <?php
                $sectionsText = $proposition->getSectionsTexte();
                foreach ($sectionsText as $idSection=>$text){
                    echo '<input type="texte" id=i' . $idSection . ' name=texte[' . $idSection . '] value="' . $text . '">';
                }
                ?>

                <input type="submit" value="envoyer">
            </fieldset>
        </form>
    </div>
</div>