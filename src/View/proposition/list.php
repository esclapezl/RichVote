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
            if(!$proposition->isArchive()) {
                echo '<div class="ligneExt"><li class="ligneExt"><a href= frontController.php?controller=proposition&action=read&id=' . htmlspecialchars($proposition->getIdProposition()) . '>' . htmlspecialchars($proposition->getIntitule()) . '</a></li></div>';
            }
        }?>
        </ul>
    </div>
</div>
