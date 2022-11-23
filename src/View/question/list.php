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
            echo '<div class="ligneExt"><li class="ligneExt"><a href=frontController.php?controller=question&action=read&id=' . rawurlencode($question->getId()).'>'.ucfirst(htmlspecialchars($question->getIntitule())).'</a><p>Auteur : <strong>Guest</strong></p></li><h2>' . $question->getCurrentPhase() . '</h2></div>';
        }
        ?>
    </ul>
    </div>
</div>