<?php
use App\Model\DataObject\User;
/** @var User[] $users*/
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Liste des Utilisateurs :</h1> <div>Vous êtes connecté en tant que : <h3>Organisateur </h3></div></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt"><a class="optQuestion" href="frontController.php?controller=user&action=create">Selectionner</a><h3>Rôle</h3></div>
        <ul>
            <?php
            foreach ($users as $user){
                echo '<div class="ligneExt"><li class="ligneExt"><a href=frontController.php?controller=user&action=read&id=' . rawurlencode($user->getId()).'>'.ucfirst(htmlspecialchars($user->getId())).'</a> <span>'.$user->getPrenom() .' '.$user->getNom() .'</span></span></li><h2>'.$user->getRole().'</h2></div>';
            }
            ?>

        </ul>
    </div>
</div>