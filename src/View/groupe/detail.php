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
        <h1> Groupe : <?=ucfirst($nomGroupe)?> </h1>
        <br>
        <?=$responsable!=null?'<h2> Responsable : '.ucfirst(htmlspecialchars($responsable)) . '</h2>':''?>
        <br>
        <br>
        <h3> Membres : </h3>
        <br>
        <ul>
            <?php
            foreach ($membres as $membre){
                echo '<li class="ligneCent"><a id="grp" href="frontController.php?controller=user&action=read&id=' . rawurlencode($membre) . '">' . htmlspecialchars($membre) . '</a></li>';
            }?>
        </ul>
        <?php
        if($canAdd){
            echo'<a href="frontController.php?controller=groupe&action=addUserToGroupe&nomGroupe=' .$nomGroupe.'">Ajouter des membres</a>';
        }
        ?>
    </div>
</div>