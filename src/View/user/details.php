<?php
use App\Model\DataObject\User;
use App\Lib\ConnexionUtilisateur;
/** @var User $user */
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1><?=htmlspecialchars($user->getId())?></h1> <h3>Detail user</h3></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>

            <?php
            echo '<div> '.$user->getNom().'</div>
                  <div> '.$user->getPrenom().'</div>
                  <div class="descP"></div>';

              if((new ConnexionUtilisateur())->estUtilisateur($user->getId()))
                  {
                      echo ' <a href="frontController.php?controller=user&action=update&id='.$user->getId(). '" style="color: #aca9ff">Modifier le mot de passe </a>';

                  }

            ?>



    </div>
</div>