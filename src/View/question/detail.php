<?php
use App\Model\DataObject\Question;
use App\Model\Repository\UserRepository;
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

                    <?php
                    use \App\Lib\ConnexionUtilisateur;
                    use App\Model\Repository\VoteRepository;
                    if(ConnexionUtilisateur::estConnecte()){
                        $idQuestion = $question->getId();
                        $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                        echo "<div id='col'><h3> Vous êtes ".(new UserRepository())->getRoleQuestion($idUser, $idQuestion)." sur cette question.</h3>";



                        if(VoteRepository::peutVoter($idUser, $idQuestion) && $question->getCurrentPhase()->getType()!="termine" && $question->getCurrentPhase()->getType()!="consultation") {
                            echo "<a href=frontController.php?controller=vote&action=voterScrutinMajoritaire&idQuestion=$idQuestion><h2>$typePhase</h2></a>";
                        }
                        else if((new UserRepository())->getRoleQuestion(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $question->getId())==null  && $typePhase== 'Voter juste Ici'){
                            echo "<a href=frontController.php?controller=vote&action=demandeAcces&idQuestion=". rawurlencode($idQuestion) .">
                                <h2>Vous souhaitez voter?</h2></a>";
                        }
                        else{
                            echo '<h2>' . $typePhase . '</h2>';
                        }

                        echo '</div>';


                    }
                    else{
                      echo '<div><h2 id="desc">Aucunes informations disponibles.</h2></div>';
                    }?>

                </div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>


            <?php
            if(ConnexionUtilisateur::estConnecte()) {
                if ((new UserRepository())->getRoleQuestion(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $question->getId()) == "organisateur") {
                    echo '<div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Liste des propositions</a>
    <a class="optQuestion" href=frontController.php?controller=question&action=delete&id=' . rawurlencode($question->getId()) . '>Supprimer</a></div>'

                        . '<div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=create&id=' . rawurlencode($question->getId()) . '>
    Créer proposition</a><a class="optQuestion" href=frontController.php?controller=question&action=update&id=' . rawurlencode($question->getId()) . '>Modifier</a></div>' . '
                        <div class="ligneExt"><div><p id="petit">Il y a 500 votants. 10 policiers, 15 utilisateurs premiums, 20 belges...</p>
                        </div>
                        <div class="ligneExt">
                            <div id="col">
                         
                                <a class="optQuestion" id="addVotants" href="frontController.php?controller=question&action=addVotantToQuestion&id=<?=$question->getId()?>">Ajouter des votants</a>
                                <p id="petit">Il y a n demandes de votes</p>
                                <div class="ligne">
                                </div>
                            </div>
                        </div>
                        </div>';
                } else {
                    echo '<div class="ligneExt"><a class="optQuestion" href=frontController.php?controller=proposition&action=readAll&id=' . rawurlencode($question->getId()) . '>Liste des propositions</a></div>';
                }
                echo '<div class="descP"></div>

        <div class="ligneExt"><h2 id="desc">DESCRIPTION</h2></div>
        <p class="descG"><?=htmlspecialchars($question->getDescription())?></p>';

        foreach ($question->getSections() as $section) {
            echo '<div class="ligneExt"><h3>' . ucfirst(htmlspecialchars($section->getIntitule())) . "</h3></div>";
            echo "<div class='ligne'></div> <p class='descP'>" . htmlspecialchars($section->getDescription()) . "</p>";
        }
        }
            else{
                echo "<div class='LigneCent'><h3>Vous n'êtes pas connecté. Connectez-vous pour plus d'informations !</h3></div>";
            }
            ?>

    </div>
</div>
