<?php
use App\Model\DataObject\Question;
/** @var Question[] $questions*/
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Questions publiées :</h1> <div>Vous êtes connecté en tant que : <h3>Organisateur </h3></div></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt"><form class="ligneAlign" method="post" action="frontController.php?controller=question&action=readAll">
                <input type="search" class="opt" name="title" id="title" placeholder="Rechercher une Question">
                <button type="submit" class="opt"><img src="../assets/img/icon-chercher.svg"></button>
                <a href="frontController.php?controller=question&action=readAll" id="refresh"><img src="../assets/img/icon-refresh.svg"></a>
            </form><a class="optQuestion" href="frontController.php?controller=question&action=create">Créer une Question</a></div>


<!--            <button class="opt">Trier Par</button>-->

        <ul>
        <?php


        foreach ($questions as $question){
            $typePhase= $question->getCurrentPhase()->getType();
            switch ($typePhase) {
                case 'consultation':
                    $typePhase= 'En cours de consultation';
                    break;
                case 'scrutinMajoritaire':
                    $typePhase= 'En cours de vote';
                    break;
                case 'termine':
                    echo "Vote(s) terminé(s)";
                    break;
            }
            echo '<div class="ligneExt">
        <li class="ligneExt">
            <div>
                    <a href=frontController.php?controller=question&action=read&id=' .
                    rawurlencode($question->getId()).'><div class="atxt">'
                    .ucfirst(htmlspecialchars($question->getIntitule())).'
                    </div><div class="descP"></div>
                    <p>'.
                htmlspecialchars($question->getApercuDescription()).'</p>
                    <p id="date">
                    Du '. htmlspecialchars($question->dateToString($question->getDateCreation())) .' au ' .
                    htmlspecialchars($question->dateToString($question->getDateFermeture())) .'</p></a>
                    
            </div>
            <div>
            <a class="abis" href=frontController.php?controller=user&action=read&id=' .
                $question->getOrganisateur() . '>Organisateur<strong>' . $question->getOrganisateur() . '</strong></a>
            
            
            </div>
            </li>
           
            <h2 class="liste">'
                . $typePhase . '</h2>
        
            </div>';
        }?>
    </ul>
    </div>
</div>