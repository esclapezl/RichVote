<?php
use App\Model\DataObject\Proposition;
use \App\Lib\ConnexionUtilisateur;
use App\Model\Repository\VoteRepository;
use App\Model\Repository\UserRepository;
/** @var Proposition $proposition */
?>
<div class="block">
    <div class="text-box">
        <a id="fleche" class="optQuestion" href=<?=$_SERVER['HTTP_REFERER']?>>↩</a>
        <div class="ligneExt">
                <h1><?=htmlspecialchars($proposition->getIntitule())?></h1><h3>Détail de la proposition</h3></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt">
            <?php
            if(ConnexionUtilisateur::estConnecte()) {
                if ((new UserRepository())->getRole(ConnexionUtilisateur::getLoginUtilisateurConnecte()) == "organisateur") {

                    echo '<a href=frontController.php?controller=proposition&action=update&id=' . rawurlencode($proposition->getIdProposition()) . ' class="optQuestion">Modifier</a>' .
                        '<a href=frontController.php?controller=proposition&action=delete&id='. rawurlencode($proposition->getIdProposition()) . ' class="optQuestion">Supprimer</a>';
                }
            }
            ?>
        </div>

        <?php
        foreach ($proposition->getSectionsTexte() as $texte) {
            echo '<div class="ligneExt"><h3>' . ucfirst(htmlspecialchars($proposition->getIntitule())) . "</h3></div>";
            echo "<div class='ligne'></div> <p class='descP'>" . htmlspecialchars($texte) . "</p>";
        }?>




    </div>
</div>
