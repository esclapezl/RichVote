<?php

use App\Model\DataObject\Groupe;
use App\Model\DataObject\Question;
use App\Model\DataObject\User;
use App\Lib\ConnexionUtilisateur;
use \App\Model\Repository\UserRepository;

/** @var Question[] $questions*/
/** @var Groupe[] $groupes*/
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
        if(ConnexionUtilisateur::estConnecte()){


                if(isset($_GET['modif']))
                {
                    echo '<form method="post" action="frontController.php?controller=user&action=updated&id='.$user->getId().'">';
                }
          echo '<div class="profil">
                    <div class="ligneExt">
                        <img class="photo" src="../assets/img/user-lambda.svg">
                        <h1>' . htmlspecialchars(ucfirst($user->getId())) . '</h1><div>';

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
                    echo '<div class="ligneCent"><p class="names" id="petit">Mail :</p><h3 id="mail">' . htmlspecialchars($user->getEmail()) . '</h3></div>
                </div>
                </div>


        </div>';


            if((new ConnexionUtilisateur())->estUtilisateur($user->getId()) || (new ConnexionUtilisateur())->estAdministrateur()) {
                echo "<div class='ligneCent'> <div id='parametres'>";
                if((new ConnexionUtilisateur)->getLoginUtilisateurConnecte() == $user->getId())
                {
                    echo ' <a href="frontController.php?controller=user&action=update&id='.$user->getId(). '">Modifier le mot de passe </a>';
                }
                echo '<a href="frontController.php?controller=user&action=delete&id='.$user->getId(). '">Supprimer le compte </a>';
                echo "</div></div>";
            }

            if(isset($_GET['modif'])) {
                echo '</form>';
            }

            echo
            "<div class='ligneCent'> 
                   <div id='groupe'>
                   <img class='photogrp' src='../assets/img/icons8-groupe.png'>";

            if(empty($groupes)){
                echo "<h3>" .htmlspecialchars(ucfirst($user->getId()))." n'a pas de groupes.</h3>";
            }
            else{
                echo "<h3>" .htmlspecialchars(ucfirst($user->getId()))." fait parti des groupes suivants :</h3>";
                foreach ($groupes as $groupe){
                    echo /*'<a href="frontController.php?controller=groupe&action=read&id=' . rawurlencode($groupe->getId()).'">'.*/'<p>' . htmlspecialchars($groupe->getId()).'</p>';
                }

            }
            echo "</div></div>";



            if(!empty($questions)){
                echo  '<div class="descG"></div>
                   
                   <div class="ligneExt"> <h1 id="fontsize">Questions publiées par '. htmlspecialchars($user->getId()).' :</h1></div>
                   <div class="ligneExt"><div class="ligne"></div> </div>';
                echo '<ul>';
                foreach ($questions as $question){
                    echo '
                <li class="ligneExt">
                    <div>
                    <a href=frontController.php?controller=question&action=read&id=' . rawurlencode($question->getId()).'>
                        <div class="atxt">' .ucfirst(htmlspecialchars($question->getIntitule())).'</div>
                        <div class="descP"></div>
                        <p>'. htmlspecialchars($question->getApercuDescription()).'</p>
                        <p id="date">Du '. htmlspecialchars($question->dateToString($question->getDateCreation())) .' au ' .
                                htmlspecialchars($question->dateToString($question->getDateFermeture())) .'</p>
                    </a>
                   </div>
                    <div>
                    <a class="abis" href=frontController.php?controller=user&action=read&id=' .
                                $question->getIdOrganisateur() . '>Organisateur<strong>' . $question->getIdOrganisateur() . '</strong>
                    </a>
                    </div>
                    </li>
                   
                
                    ';
                }
        echo '</ul>';
        }



        }
        else {
                echo "
                    <div class='descG'></div> <div class='ligneCent'>
                    <p>Vous n'avez pas accès aux informations.</p></div><div class='ligneCent'>
                    <p>Connectez-vous en cliquant juste<a href='frontController.php?controller=user&action=connexion'>ici</a>.
                    </p>
                    </div>";

        }
        
        ?>






    </div>
</div>