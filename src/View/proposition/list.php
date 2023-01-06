<?php
//liste des propositions pour une question donnée

use App\Model\DataObject\Phase;
use App\Model\DataObject\Proposition;
/** @var array $propositions
 * @var ?array $scores
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


            <?php

                if (empty($propositions)) {
                    echo "<div class='descG'></div><div class='ligneCent'><h3>Aucun résultat a été trouvé pour ". htmlspecialchars($_POST['title'])." .</h3></div>
                    <div class='descP'></div><div class='ligneCent'>";
                }
                else {

                    foreach ($propositions as $proposition) {
                    if (isset($scores)) {
                        $infoComplement = $scores[$proposition->getId()];
                    } else {
                        $infoComplement = $proposition->estArchive() ? '(archivé)' : '';
                    }
                    echo '<li class="ligneExt"><a class="atxt" href=frontController.php?controller=proposition&action=read&id=' . rawurlencode($proposition->getIdProposition()) . '>' . htmlspecialchars($proposition->getIntitule()) . $infoComplement . '</a></li>';
                    }
                }?>
        </ul>
    </div>
</div>