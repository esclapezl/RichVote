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
        <h1> Groupe: <?=$nomGroupe?> </h1>
        <?=$responsable!=null?'<h2> Responsable: '.$responsable . '</h2>':''?>

        <h2> Membres: </h2>
        <ul>
            <?php
            foreach ($membres as $membre){
                echo '<li>' . htmlspecialchars($membre) . '</li>';
            }?>
        </ul>
        <?php
        if($canAdd){
            echo'<a href="frontController.php?controller=groupe&action=addUserToGroupe&nomGroupe=' .$nomGroupe.'">Ajouter des membres</a>';
        }
        ?>
    </div>
</div>