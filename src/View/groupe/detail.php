<?php
/** @var \App\Model\DataObject\Groupe $groupe
 * @var bool $canAdd
 */
$nomGroupe = $groupe->getId();
$responsable = $groupe->getIdResponsable();
$membres = $groupe->getIdMembres();
?>
<div class="block">
    <div class="text-box">
        <a class="optQuestion" id="fleche" href=frontController.php?controller=groupe&action=readAll>↩</a>
        <h1> Groupe : <?=ucfirst($nomGroupe)?> </h1>
        <br>
        <?=$responsable!=null?'<h2> Responsable : '.ucfirst(htmlspecialchars($responsable)) . '</h2>':''?>
        <br>
        <?php
        if(\App\Lib\ConnexionUtilisateur::getLoginUtilisateurConnecte()==$responsable){
            echo'<div class="ligneCent"><a class="optButton" href="frontController.php?controller=groupe&action=addUserToGroupe&nomGroupe=' .$nomGroupe.'">Ajouter des membres</a></div>';
        }
        ?>
        <br>
        <h3> Membres : </h3>
        <br>
        <ul>
            <?php
            foreach ($membres as $membre){
                echo '<li class="ligneCent"><a id="grp" href="frontController.php?controller=user&action=read&id=' . rawurlencode($membre) . '">' . htmlspecialchars($membre) . '</a></li>';
            }?>
        </ul>

    </div>
</div>