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
    <select name="phase" size="1">
        <option value="consultation">Phase de consultation
        <option value="scrutinMajoritaire">Phase de vote par scrutin majoritaire
    </select>


    <label for="consultation"> Consultation </label>
    <input type="radio" id="consultation" name=<?='type['.$id.']'?> value="consultation" <?=$type=='consultation'?'checked':''?>>
    <label for="scrutinMajoritaire"> Scrutin majoritaire </label>
    <input type="radio" id="scrutinMajoritaire" name=<?='type['.$id.']'?> value="scrutinMajoritaire" <?=$type=='scrutinMajoritaire'?'checked':''?>>

    <label for=<?='nbP'.$id?>>S'il s'agit d'un vote, indiquez le nombre de propositions qui seront sélectionnées à l'issue du vote</label>
    <input type="number" min="1" max="20" id=<?='nbP'.$id?> name=<?='nbDePlaces['.$id.']'?> value="<?=$nbPlaces==null?1:$nbPlaces?>">

</p>

