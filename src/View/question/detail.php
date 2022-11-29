<?php
use App\Model\DataObject\Question;
/** @var Question $question */

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
echo '<div class="block">
        <div class="text-box">
            <div class="ligneExt">
                <div><a class="optQuestion" href=frontController.php?controller=question&action=readAll>↩</a>
                    <h1>' . htmlspecialchars($question->getIntitule()) . '</h1>
                </div> 
                <div><h3>Détail de la question</h3>
                    <div class="ligneAlign">
                    <h3>Etat :</h3>
                    <a href=frontController.php?controller=vote&action=<?= ' . rawurlencode($question->getCurrentPhase()->getType()) . '><h2>' . $typePhase . '</h2></a>
                    </div>
                </div>
            </div>
            <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div>
        </div>';
echo '<div class="ligneExt">
      <a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Liste des propositions</a>'
                     .
                    '<a class="optQuestion" href=frontController.php?controller=question&action=delete&id='. rawurlencode($question->getId()) . '>Supprimer</a>';

            ?>
                 </div>
        <div class="ligneExt"><?php echo '<a class="optQuestion" href=frontController.php?controller=proposition&action=create&id=' . rawurlencode($question->getId()) . '>Créer proposition</a>' .
                    '<a class="optQuestion" href=frontController.php?controller=question&action=update&id=' . rawurlencode($question->getId()) . '>Modifier</a>';
?></div>
        <div class="descP"></div>

    <div class="ligneExt"><h3>Description :</h3></div>
        <p class="descG"><?=htmlspecialchars($question->getDescription())?></p>

        <?php foreach ($question->getSections() as $section) {
            echo '<div class="ligneExt"><h3>' . ucfirst(htmlspecialchars($section->getIntitule())) . "</h3></div>";
            echo "<div class='ligne'></div> <p class='descP'>" . htmlspecialchars($section->getDescription()) . "</p>";
        }?>

    </div>
</div>
