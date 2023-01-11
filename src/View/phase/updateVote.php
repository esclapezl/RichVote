<?php

use App\Model\DataObject\Phase;
use App\Model\DataObject\Question;
use App\Model\DataObject\Section;
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
    <div>Début :
    <input type="date" name="dateDebut[<?=$id?>]" value="<?=$dateDebut?>" <?=($phase->estCommence()||$phase->estFinie())?'readonly':''?>>
    </div><div class="descP"></div>
    <div>Fin :
    <input type="date" name="dateFin[<?=$id?>]" value="<?=$dateFin?>" <?=($phase->estCommence()||$phase->estFinie())?'readonly':''?>>
    </div><div class="descP"></div>

    <div>
        Type de phase :
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