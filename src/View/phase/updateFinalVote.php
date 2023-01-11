<?php

use App\Model\DataObject\Phase;

/** @var Phase $phase
 * @var int $numeroPhase
 */
$id = $phase->getId();
$dateDebut = $phase->getDateDebut()->format('Y-m-d');
$dateFin = $phase->getDateFin()->format('Y-m-d');
$type = $phase->getType();
$nbPlaces = $phase->getNbDePlaces();
?>
<div class="descP"></div>
Modifier la phase de vote final
<div id="phase<?=$id?>">
    <div><label for="dpf">Début :</label>
        <input type="date" id="dpf" name="dateDebut[<?=$id?>]" value="<?=$dateDebut?>">
    </div><div class="descP"></div>
    <div><label for="dff">Fin :</label>
    <input type="date" id="dff" name="dateFin[<?=$id?>]" value="<?=$dateFin?>">
    </div><div class="descP"></div>

    <div>
        <label for="selectwidth">Type de phase :</label>
        <select id="selectwidth" name="<?="type[$id]"?>">
            <option value="scrutinMajoritaire" <?=$type=='scrutinMajoritaire'?'selected':''?>>Phase de vote par scrutin majoritaire</option>
            <option value="scrutinMajoritairePlurinominal" <?=$type=='scrutinMajoritairePlurinominal'?'selected':''?>>Phase de vote par scutin majoritaire plurinominal</option>
            <option value="jugementMajoritaire" <?=$type=='jugementMajoritaire'?'selected':''?>>Phase de vote par jugement majoritaire</option>
        </select>
    </div>


<div class="descP"></div>
    <input type="hidden" name="nbDePlaces[<?=$id?>]" value=1>
</div>
<div class="descG"></div>