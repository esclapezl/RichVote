<?php

use App\Model\DataObject\Phase;
use App\Model\DataObject\Section;
use App\Model\DataObject\Question;
/** @var Phase $phase
 * @var int $numeroPhase
 */
$id = $phase->getId();
$dateDebut = '20' . $phase->getDateDebut()->format('y-m-d');
$dateFin = '20' . $phase->getDateFin()->format('y-m-d');
$type = $phase->getType();
$nbPlaces = $phase->getNbDePlaces();
?>

<script type="text/javascript">
    function visibilite(id)
    {

        var element = document.getElementById(id);
        if(element.style.display === "none"){
            element.style.display ="";
        }
        else{
            element.style.display = "none";
        }
    }
</script>
<div class="descP"></div>
Modifier la phase de vote final
<div id="phase<?=$id?>">
    <div>Début :
    <input type="date" id=<?='dD'.$id?> name=<?='dateDebut['.$id.']'?> value="<?=$dateDebut?>">
    </div><div class="descP"></div>
    <div>Fin :
    <input type="date" id=<?='dF'.$id?> name=<?='dateFin['.$id.']'?> value="<?=$dateFin?>">
    </div><div class="descP"></div>

    <div>
        Type de phase :
        <select id="selectwidth" name="<?="type[$id]"?>">
            <option value="scrutinMajoritaire" <?=$type=='scrutinMajoritaire'?'selected':''?>>Phase de vote par scrutin majoritaire</option>
            <option value="scrutinMajoritairePlurinominal" <?=$type=='scrutinMajoritairePlurinominal'?'selected':''?>>Phase de vote par scutin majoritaire plurinominal</option>
            <option value="jugementMajoritaire" <?=$type=='jugementMajoritaire'?'selected':''?>>Phase de vote par jugement majoritaire</option>
        </select>
    </div>


<div class="descP"></div>
    <input type="hidden" id=<?='nbP'.$id?> name=<?='nbDePlaces['.$id.']'?> value=1>

</div>
<div class="descG"></div>