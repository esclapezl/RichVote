<?php

use App\Model\DataObject\Question;
use App\Model\DataObject\Phase;
use App\Model\DataObject\Section;
/** @var Phase $phase
 * @var int $numeroPhase
 */
$id = $phase->getId();
$dateDebut = '20' . $phase->getDateDebut()->format('y-m-d');
$dateFin = '20' . $phase->getDateFin()->format('y-m-d');
$type = $phase->getType();
$nbPlaces = $phase->getNbDePlaces();
?>
<div class="descP"></div>
Modifier la phase de rédaction
<div id="phase<?=$id?>">
    <div>Début :
        <input type="date" id=<?='dD'.$id?> name=<?='dateDebut['.$id.']'?> value="<?=$dateDebut?>">
    </div><div class="descP"></div>
    <div>Fin :
        <input type="date" id=<?='dF'.$id?> name=<?='dateFin['.$id.']'?> value="<?=$dateFin?>">
        <input type="hidden"  name="<?="type[$id]"?>" value="redaction">
    </div><div class="descP"></div>

    <div class="descG"></div>