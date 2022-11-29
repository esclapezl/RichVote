<?php
use App\Model\DataObject\User;
use App\Lib\ConnexionUtilisateur;
use \App\Model\Repository\UserRepository;
$user = (new UserRepository())->select($_GET['id']);


?>


<div class="block">
    <div class="text-box">
        <?php
        if(isset($_GET['modif']))
        {
            echo '<form method="post" action="frontController.php?controller=user&action=updated&id='.$user->getId().'">';
        }

        //IDENTIFIANT
        if(isset($_GET['modif'])&&$_GET['modif']=='identifiant')
        {
            echo  '<input type="text" id="identifiant" name="identifiant" placeholder="Identifiant" size="50"> <button type="submit" class="opt"><img src="../assets/img/icons8-coche.svg"></button>';
        }
        else
        {
            echo '<div class="ligneExt"> <h1>'.$user->getId().' <a href="frontController.php?controller=user&action=read&id='.$user->getId().'&modif=identifiant" id="modif"><img src="../assets/img/icons8-paramètres.svg"></a> </h1> <h3>Detail user</h3></div>';
        }
        echo'
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>';


        //NOM
        if(isset($_GET['modif'])&&$_GET['modif']=='nom')
        {
            echo '<input type="text" id="nom" name="nom" placeholder="Nom" size="50"> <button type="submit" class="opt"><img src="../assets/img/icons8-coche.svg"></button>';
        }
        else
        {
            echo '<div> '.$user->getNom().'<a href="frontController.php?controller=user&action=read&id='.$user->getId().'&modif=nom" id="modif"><img src="../assets/img/icons8-paramètres.svg"></a></div>';
        }
        echo '<div class="descP"></div>';

        //PRENOM
        if(isset($_GET['modif'])&&$_GET['modif']=='prenom')
        {
            echo '<input type="text" id="prenom" name="prenom" placeholder="Prenom" size="50"> <button type="submit" class="opt"><img src="../assets/img/icons8-coche.svg"></button>';
        }
        else
        {
            echo '<div> '.$user->getPrenom().'<a href="frontController.php?controller=user&action=read&id='.$user->getId().'&modif=prenom" id="modif"><img src="../assets/img/icons8-paramètres.svg"></a></div>';
        }
        echo '<div class="descP"></div>';

        //EMAIL
        if(isset($_GET['modif'])&&$_GET['modif']=='email')
        {
            echo '<div><input type="text" id="email" name="email" placeholder="Email" size="50" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"> <button type="submit" class="opt"><img src="../assets/img/icons8-coche.svg"></button></div>';
        }
        else
        {
            echo '<div> '.$user->getEmail().'<a href="frontController.php?controller=user&action=read&id='.$user->getId().'&modif=email" id="modif"><img src="../assets/img/icons8-paramètres.svg"></a></div>';
        }
        echo '<div class="descG"></div>';

        //MODIFIER MDP ET SUPPRIMER COMPTE
        if((new ConnexionUtilisateur())->estUtilisateur($user->getId()) || (new ConnexionUtilisateur())->estAdministrateur()) {
            if((new ConnexionUtilisateur)->getLoginUtilisateurConnecte() == $user->getId())
            {
                echo ' <a href="frontController.php?controller=user&action=update&id='.$user->getId(). '" style="color: #aca9ff">Modifier le mot de passe </a> <div class="descG"></div>';
            }
            echo '<a href="frontController.php?controller=user&action=delete&id='.$user->getId(). '" style="color: #ffa9a9">Supprimer le compte </a>';
        }
        if(isset($_GET['modif'])) {
            echo '</form>';
        }
        ?>





    </div>
</div>