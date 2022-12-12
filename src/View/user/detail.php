<?php
use App\Model\DataObject\User;
use App\Lib\ConnexionUtilisateur;
use \App\Model\Repository\UserRepository;
$user = (new UserRepository())->select($_GET['id']);

$bool =false;
echo $bool;

$peutModif =(((new ConnexionUtilisateur())->getLoginUtilisateurConnecte())==$user->getId() || (new ConnexionUtilisateur())->estAdministrateur());

?>


<div class="block">
    <div class="text-box">
        <div class="ligneExt"><a class="optQuestion" id="fleche" href=<?=$_SERVER['HTTP_REFERER']?>>↩</a><h3>Detail user</h3></div>

        <div class="ligneExt"><div></div><div class="ligne"></div></div>
        <br>

                <?php
                if(isset($_GET['modif']))
                {
                    echo '<form method="post" action="frontController.php?controller=user&action=updated&id='.$user->getId().'">';
                }
                ?>
        <div class="profil">
                <div class="ligneExt">

                    <img class="photo" src="../assets/img/user-lambda.svg">
                    <h1><?=htmlspecialchars(ucfirst($user->getId()))?> </h1><div>

                    <?php
                        //NOM
                        if(isset($_GET['modif'])&&$_GET['modif']=='nom'&& $peutModif)
                        {
                            echo '<div class="ligneCent"><input type="text" id="nom" name="nom" value="'. htmlspecialchars(ucfirst($user->getNom())) .
                                '" size="10" required> <button type="submit" class="valide"><img src="../assets/img/icons8-coche.svg"></button></div>';
                        }
                        else if($peutModif)
                        {
                            echo '<div class="ligneCent"><p class="names" id="petit">Nom :</p><h3 class="names">' . htmlspecialchars(ucfirst($user->getNom())) . '
                                </h3><a href="frontController.php?controller=user&action=read&id='.$user->getId().'&modif=nom" id="modif">
                                <img src="../assets/img/icons8-paramètres.svg"></a></div>';
                        }
                        else
                        {
                            echo '<div class="ligneCent"><p class="names" id="petit">Nom :</p><h3>' . htmlspecialchars(ucfirst($user->getNom())) . '</h3></div>';
                        }

                    //PRENOM
                    if(isset($_GET['modif'])&&$_GET['modif']=='prenom'&& $peutModif)
                    {
                        echo '<div class="ligneCent"><input type="text" id="prenom" name="prenom" value=' .
                            htmlspecialchars(ucfirst($user->getPrenom())) . ' size="10" required> <button type="submit" class="valide">
                            <img src="../assets/img/icons8-coche.svg"></button></div>';
                    }
                    else if($peutModif)
                    {
                        echo '<div class="ligneCent"><p class="names" id="petit">Prénom :</p><h3 class="names">' .
                            htmlspecialchars(ucfirst($user->getPrenom())) . '</h3>
                            <a href="frontController.php?controller=user&action=read&id='.$user->getId().'&modif=prenom" id="modif">
                            <img src="../assets/img/icons8-paramètres.svg"></a></div>';
                    }
                    else
                    {
                        echo '<div class="ligneCent"><p class="names" id="petit">Prénom :</p><h3>' . htmlspecialchars(ucfirst($user->getPrenom())) . '</h3></div>';
                    }


                    echo '<br>';
                    ?>

                    <div class="ligneCent"><p class="names" id="petit">Mail :</p><h3 id="mail"><?=htmlspecialchars($user->getEmail())?> </h3></div>
                </div>

                </div>


        </div>
        <?php

        if((new ConnexionUtilisateur())->estUtilisateur($user->getId()) || (new ConnexionUtilisateur())->estAdministrateur()) {
            echo "<div class='ligneCent'> <div id='parametres'>";
            if((new ConnexionUtilisateur)->getLoginUtilisateurConnecte() == $user->getId())
            {
                echo ' <a href="frontController.php?controller=user&action=update&id='.$user->getId(). '">Modifier le mot de passe </a>';
            }
            echo '<a href="frontController.php?controller=user&action=delete&id='.$user->getId(). '">Supprimer le compte </a>';
        }
        echo "</div></div>";


        if(isset($_GET['modif'])) {
            echo '</form>';
        }
        ?>
    </div>
</div>