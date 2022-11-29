<?php
use App\Model\DataObject\Question;
/** @var Question[] $questions*/
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Questions publiées :</h1> <div>Vous êtes connecté en tant que : <h3>Organisateur </h3></div></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt"><a class="optQuestion" href="frontController.php?controller=question&action=create">Créer une Question</a><h3>Phase</h3></div>
        <div class="ligneAlign">
            <form method="post" action="frontController.php?controller=question&action=readAll">
                <input type="search" class="opt" name="title" id="title" placeholder="Rechercher un Auteur">
                <button type="submit" class="opt"><img src="../assets/img/icon-chercher.svg"></button>
            </form>
            <button class="opt">Trier Par</button>
        </div>
        <ul>
        <?php

        foreach ($questions as $question){
            echo '<div class="ligneExt">
        <li class="ligneExt">
            <div> <a href=frontController.php?controller=question&action=read&id=' .
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
            <a href=frontController.php?controller=vote&action='. $question->getCurrentPhase()->getType() .'>
            <h2>'
                . ucfirst($question->getCurrentPhase()->getType()) . '</h2>
            </a>
            </div>';
        }
        ?>
    </ul>
    </div>
</div>