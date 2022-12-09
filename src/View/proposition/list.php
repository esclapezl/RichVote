<?php
//liste des propositions pour une question donnée

use App\Model\DataObject\Phase;
use App\Model\DataObject\Proposition;
/** @var array $propositions
 *  @var Phase $phase
 */
?>

<div class="block">
    <div class="text-box">
        <a class="optQuestion" id="fleche" href=frontController.php?controller=question&action=read&id=<?= rawurlencode($_GET['id'])?>>↩</a>
        <div class="ligneExt">
            <div>

                <h1>Propositions publiées :</h1></div>
            <div>Vous êtes connecté en tant que : <h3>Organisateur </h3></div>
        </div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <ul>
            <?php foreach ($propositions as $proposition) {
                $archive = $proposition->estArchive()?'(archivé)':'';
                echo '<li class="ligneExt"><a class="atxt" href=frontController.php?controller=proposition&action=read&id=' . rawurlencode($proposition->getIdProposition()) . '>' . htmlspecialchars($proposition->getIntitule()) . $archive . '</a></li>';
            }?>
        </ul>
    </div>
</div>