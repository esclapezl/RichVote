<?php
use App\Model\DataObject\Proposition;
/** @var Proposition $proposition */
?>

<div class="block">
    <div class="text-box">
        <form method="post" action="frontController.php?controller=proposition&action=updated&id=<?=$proposition->getIdProposition()?>">
            <fieldset>
                <h1>Votre Proposition</h1>
                <div class="ligneCent"><div class="ligne"></div></div>
                <div class="descG"></div>


                <h3>Proposition :</h3>
                <input type="text" name="intitule" size="50" value='<?=ucfirst($proposition->getIntitule())?>' >
                <br><br>



                <?php
                $sectionsText = $proposition->getSectionsTexte();
                foreach ($sectionsText as $infos){
                    $idSection = $infos['section']->getId();
                    $text = $infos['texte'];
                    echo ' <div class="descP"></div><h3>Description : </h3>
                        <textarea rows="4" cols="80" maxlength="1000" id="mytextarea" name=texte[' . $idSection . '] >' . $text . '</textarea></form>';
                }
                ?>
            <div class="descG"></div>

                <div class="ligneCent"> <input class="optQuestion" type="submit" value="sauvegarder"/></div>
            </fieldset>
        </form>
    </div>
</div>