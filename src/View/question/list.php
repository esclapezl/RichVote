<?php

use \App\Lib\ConnexionUtilisateur;
use App\Model\Repository\VoteRepository;
use App\Model\Repository\UserRepository;
/** @var Question[] $questions*/
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Questions publiées :</h1>

                <?php
                if(ConnexionUtilisateur::estConnecte()){
                    $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                    echo "<div class='responsive'>Vous êtes connecté en tant que :<h3>".ucfirst((new UserRepository())->getRole($idUser))."</h3></div>";
                }
                else{
                    echo "<h3 class='responsive'>Vous n'êtes pas connecté</h3>";
                }?>

                </div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt"><form class="ligneAlign" method="post" action="frontController.php?controller=question&action=readAll">
                <input type="search" class="opt" name="title" id="title" placeholder="Rechercher une Question">
                <button type="submit" class="opt"><img src="../assets/img/icon-chercher.svg"></button>
                <a href="frontController.php?controller=question&action=readAll" id="refresh"><img src="../assets/img/icon-refresh.svg"></a>
            </form>
            <?php
            if(ConnexionUtilisateur::estConnecte()) {
            if ((new UserRepository())->getRole(ConnexionUtilisateur::getLoginUtilisateurConnecte()) == "organisateur") {

                echo '<a class="optQuestion" href = "frontController.php?controller=question&action=create" > Créer une Question </a >';
                }
            }
            ?>
<!--            <button class="opt">Trier Par</button>-->

        </div><ul>
        <?php
        if(empty($questions)){
            echo "<div class='descG'></div><div class='ligneCent'><h3>Aucun résultat a été trouvé pour ". htmlspecialchars($_POST['title'])." .</h3></div>
                    <div class='descP'></div><div class='ligneCent'>
                    <a href=frontController.php?controller=question&action=readAll>Clique <strong>ici</strong> pour afficher <strong>toute</strong> la liste !</a></div>";
        }
        else{




        foreach ($questions as $question){
            $typePhase= $question->getCurrentPhase()->getType();
            switch ($typePhase) {
                case 'consultation':
                    $typePhase= 'En cours de consultation';
                    break;
                case 'scrutinMajoritaire' || 'scrutinMajoritairePlurinominal' :
                    $typePhase= 'En cours de vote';
                    break;
                case 'termine':
                    echo "Vote(s) terminé(s)";
                    break;
            }
            echo '
<div class="ligneExt">
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
           
            <a class="liste" href=frontController.php?controller=vote&action=' . rawurlencode($question->getCurrentPhase()->getType()) . '><h2>'
                . $typePhase . '</h2></a>
        
            </div>';
        }}?>
    </ul>
    </div>
</div>