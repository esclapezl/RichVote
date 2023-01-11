<?php

use App\Model\DataObject\Phase;

/** @var Phase $phase
 * @var int $numeroPhase
 */
$id = $phase->getId();
$dateDebut = $phase->getDateDebut()->format('Y-m-d');
$dateFin =  $phase->getDateFin()->format('Y-m-d');
$type = $phase->getType();
$nbPlaces = $phase->getNbDePlaces();
?>
<div id="phase<?=$id?>">
    Modifier la phase de vote <?=$numeroPhase?>
    <div><label for="db">Début :</label>
    <input type="date" id="db" name="dateDebut[<?=$id?>]" value="<?=$dateDebut?>">
    </div><div class="descP"></div>
    <div><label for="df">Fin :</label>
    <input type="date" id="df" name="dateFin[<?=$id?>]" value="<?=$dateFin?>">
    </div><div class="descP"></div>

    <div>
        <label for="selectwidth">Type de phase :</label>
        <select id="selectwidth" name="type[<?=$id?>]">
            <option value="scrutinMajoritaire" <?=$type=='scrutinMajoritaire'?'selected':''?>>Phase de vote par scrutin majoritaire</option>
            <option value="scrutinMajoritairePlurinominal" <?=$type=='scrutinMajoritairePlurinominal'?'selected':''?>>Phase de vote par scutin majoritaire plurinominal</option>
            <option value="jugementMajoritaire" <?=$type=='jugementMajoritaire'?'selected':''?>>Phase de vote par jugement majoritaire</option>
        </select>
    </div>


<div class="descP"></div>
    <label for=<?='nbP'.$id?>>Indiquez le nombre de propositions qui seront sélectionnées à l'issue du vote</label>
    <input type="number" min="1" max="20" id=<?='nbP'.$id?> name=<?='nbDePlaces['.$id.']'?> value="<?=$nbPlaces==null?1:$nbPlaces?>">

</div>
<div class="descG"></div>