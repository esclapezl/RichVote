<?php

use App\Model\DataObject\Phase;
use App\Model\DataObject\Section;
/** @var Phase $phase */
$id = $phase->getId();
$dateDebut = '20' . $phase->getDateDebut()->format('y-m-d');
$dateFin = '20' . $phase->getDateFin()->format('y-m-d');
$type = $phase->getType();
$nbPlaces = $phase->getNbDePlaces();
?>

<p>
    <div>Début :
    <input type="date" id=<?='dD'.$id?> name=<?='dateDebut['.$id.']'?> value="<?=$dateDebut?>">
    </div><div class="descP"></div>
    <div>Fin :
    <input type="date" id=<?='dF'.$id?> name=<?='dateFin['.$id.']'?> value="<?=$dateFin?>">
    </div><div class="descP"></div>

    <div>
        Type de phase :
        <select name="<?='type['.$id.']'?>">
            <option value="consultation">Phase de consultation</option>
            <option value="scrutinMajoritaire">Phase de vote par scrutin majoritaire</option>
            <option value="scrutinMajoritairePlurinominal">Phasede vote par scutin majoritaire plurinominal</option>
        </select>
    </div>

<div class="descP"></div>
    <label for=<?='nbP'.$id?>>S'il s'agit d'un vote, indiquez le nombre de propositions qui seront sélectionnées à l'issue du vote</label>
    <input type="number" min="1" max="20" id=<?='nbP'.$id?> name=<?='nbDePlaces['.$id.']'?> value="<?=$nbPlaces==null?1:$nbPlaces?>">

</p>

