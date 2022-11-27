<?php
use App\Model\DataObject\Question;
/** @var Question[] $questions*/
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Questions publiées :</h1> <div>Vous êtes connecté en tant que : <h3>Organisateur </h3></div></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt"><a class="optQuestion" href="frontController.php?controller=question&action=create">Créer une Question</a><h3>Statut du Vote</h3></div>
    <ul>
        <?php
        foreach ($questions as $question){
            echo '<div class="ligneExt"><li class="ligneExt">
            <a class="atxt" href=frontController.php?controller=question&action=read&id=' .
                rawurlencode($question->getId()).'>'
            .ucfirst(htmlspecialchars($question->getIntitule())).'</a>
            <a class="abis" href=frontController.php?controller=user&action=read&id=' .
           $question->getOrganisateur() . '>Auteur : <strong>' . $question->getOrganisateur() . '</strong></a></li>
            <a href=frontController.php?controller=vote&action='. $question->getCurrentPhase()->getType() .'><h2>'
                . ucfirst($question->getCurrentPhase()->getType()) . '</h2></a></div>';
        }
        ?>
    </ul>
    </div>
</div>