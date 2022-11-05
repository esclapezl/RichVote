<?php
use App\Model\DataObject\Proposition;
/** @var Proposition $proposition */
foreach ($proposition->getSectionsTexte() as $texte){
    echo '<p>'.$texte.'</p>';
}
?>
