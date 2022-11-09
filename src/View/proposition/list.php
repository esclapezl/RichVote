<?php
//liste des propositions pour une question donnée

use App\Model\DataObject\Proposition;
/** @var array $propositions */
?>

<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Propositions publiées :</h1> <div>Vous êtes connecté en tant que : <h3>Organisateur </h3></div></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <ul>
        <?php foreach ($propositions as $proposition) {
            echo '<div class="ligneExt"><li class="ligneExt"><a href= frontController.php?controller=proposition&action=read&id=' . $proposition-->getIntitule() . '>' . $proposition->getIdProposition() . '</a></li></div>';
        }?>
        </ul>
    </div>
</div>
