<?php
use App\Model\DataObject\Question;
/** @var Question $question */

$typePhase= $question->getCurrentPhase()->getType();
switch ($typePhase) {
    case 'consultation':
        $typePhase= 'En cours de consultation';
        break;
    case 'scrutinMajoritaire':
        $typePhase= 'Voter juste Ici';
        break;
    case 'termine':
        echo "Vote(s) terminé(s)";
        break;
}
?>

<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <div><a class="optQuestion" id="fleche" href=frontController.php?controller=question&action=readAll>↩</a>
                <h1><?=htmlspecialchars($question->getIntitule())?></h1></div>
                <div><h3>Détail de la question</h3>
                <div class="ligneAlign">
                    <?php
                    use \App\Lib\ConnexionUtilisateur;
                    use App\Model\Repository\VoteRepository;
                    if(ConnexionUtilisateur::estConnecte()){
                        $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                        $idQuestion = $question->getId();
                        if(VoteRepository::peutVoter($idUser, $idQuestion)) {
                            echo "<a href=frontController.php?controller=vote&action=voterScrutinMajoritaire&idQuestion=$idQuestion><h2>$typePhase</h2></a>";
                        }
                        else{
                            echo "<a href=frontController.php?controller=vote&action=demandeAcces&idQuestion=$idQuestion><h2>Vous souhaitez voter?</h2><h3>Demandez votre accès ici</h3></a>";
                        }

                    }else{
                        echo "<a><h2>Vous n'avez pas le droit de voter</h2></a>";
                    }?>

                </div></div></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt">

            <?php
            echo
                '<a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Liste des propositions</a>'.
                '<a class="optQuestion" href=frontController.php?controller=question&action=delete&id='. rawurlencode($question->getId()) . '>Supprimer</a></div>'

            . '<div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=create&id=' . rawurlencode($question->getId()) . '>Créer proposition</a>' .
                '<a class="optQuestion" href=frontController.php?controller=question&action=update&id=' . rawurlencode($question->getId()) . '>Modifier</a></div>';
            ?>

        <div class="descP"></div>

        <div class="ligneExt"><div>
                <p id="petit">Il y a 500 votants. 10 policiers, 15 utilisateurs premiums, 20 belges...</p>
            </div>
            <div class="col">
                <a class="optQuestion" href="frontController.php?controller=question&action=addVotantToQuestion&id=<?=$question->getId()?>">Ajouter des votants</a>
                <p id="petit">Il y a n demandes de votes</p>
                <div class='ligne'></div>
            </div>
        </div>
        <div class="descP"></div>

        <div class="ligneExt"><h2 id="desc">DESCRIPTION</h2></div>
        <p class="descG"><?=htmlspecialchars($question->getDescription())?></p>

        <?php foreach ($question->getSections() as $section) {
            echo '<div class="ligneExt"><h3>' . ucfirst(htmlspecialchars($section->getIntitule())) . "</h3></div>";
            echo "<div class='ligne'></div> <p class='descP'>" . htmlspecialchars($section->getDescription()) . "</p>";
        }?>

    </div>
</div>
