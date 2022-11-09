<?php
use App\Model\DataObject\Proposition;
/** @var Proposition $proposition */
?>

<div class="block">
    <div class="text-box">
        <form method="post" action="frontController.php?controller=proposition&action=updated&id=<?=$proposition->getIdProposition()?>">
                <div class="descP"></div>
                <h1><legend>Votre Proposition</legend></h1>
                <div class="ligneCent"><div class="ligne"></div></div>
                <div class="descG"></div>

                <p>
                <h3>Proposition :</h3>
                <input type="text" id="t" name="intitule" size="50" value='<?=ucfirst($proposition->getIntitule())?>' >
                <div class="descP"></div>
                </p>


                <?php
                $sectionsText = $proposition->getSectionsTexte();
                foreach ($sectionsText as $idSection=>$text){
                    echo ' <div class="descP"></div><h3>Description</h3><textarea type="texte" rows="4" cols="80" id=i' . $idSection . ' name=texte[' . $idSection . '] >' . $text . '</textarea>';
                }
                ?>
            <div class="descG"></div>

                <div class="ligneCent"> <input class="optQuestion" type="submit" value="sauvegarder"/></div>
            </fieldset>
        </form>
    </div>
</div>