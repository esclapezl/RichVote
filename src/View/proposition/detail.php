<?php
use App\Model\DataObject\Proposition;
use \App\Lib\ConnexionUtilisateur;
use  \App\Model\DataObject\Commentaire;
/** @var Proposition $proposition
 * @var Array $commentaires
 * @var Commentaire $commentaire
 * @var string $roleProposition
 */
$idProposition = $proposition->getId();
?>
<div class="block" >
    <div class="column">
    <div class="text-box">
        <div class="ligneExt"> <a id="fleche" class="optQuestion" href="frontController.php?controller=question&action=read&id=<?=$proposition->getIdQuestion()?>">↩</a><a  class="optQuestion" href="frontController.php?controller=proposition&action=readDemandeAuteur&id=<?=rawurlencode($idProposition)?>"> Demandes de Co-Auteurs </a></div>
        <div class="ligneExt">
                <h1><?=htmlspecialchars($proposition->getIntitule())?></h1><h3>Détail de la proposition</h3></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt">
            <?php
            if ($roleProposition=='responsable'){
                echo '<a href=frontController.php?controller=proposition&action=update&id=' . rawurlencode($idProposition) . ' class="optQuestion">Modifier</a>' .
                    '<a href=frontController.php?controller=proposition&action=delete&id='. rawurlencode($idProposition) . ' class="optQuestion">Supprimer</a>';
            }
            elseif ($roleProposition!='auteur'){
                echo '<a href="frontController.php?controller=proposition&action=addDemandeAuteur&id=' . $proposition->getId() . '"> devenir auteur de cette proposition</a>';
            }?>
        </div>

        <?php
        foreach ($proposition->getSectionsTexte() as $infos){
            $idSection = $infos['section']->getId();
            $texte = $infos['texte'];
            $nbLikes = (new \App\Model\Repository\SectionRepository())->getNbLikes($idSection);

            echo '<div class="ligneExt"><h3>' . ucfirst(htmlspecialchars($proposition->getIntitule())) . "</h3></div>";
            echo "<div class='ligne'></div>" . $texte ;
            echo '<div><a href="frontController.php?controller=proposition&action=likeSectionProposition&id='.$idSection.'&idQuestion='.$proposition->getIdQuestion().'&idProposition='.$proposition->getId().'"><img src="../assets/img/icons8-jaimeBlanc.png"></a>     '.$nbLikes.'</div></li>';
            echo '<br>';
        }?>
    </div>

    <div class="text-box" >
        <h3> Commentaires  </h3>
        <form action="frontController.php?controller=proposition&action=ajtCommentaire&id=<?php echo $_GET['id'] ?>" method="post">
            <input  type="text" name="commentaire" id="commentaire" required>
            <div class="ligneExt"><div></div>
                <input type="image" src="../assets/img/icons8-coche.svg" border="0" alt="Submit" /></div>
        </form>
        <div class="descG"></div>

        <?php
        if(!empty($commentaires))
        {
            foreach ($commentaires as $commentaire)
            {
                if($commentaire->getIDUSER() == ConnexionUtilisateur::getLoginUtilisateurConnecte() || ConnexionUtilisateur::estAdministrateur())
                {
                    echo '<li class="ligneExt"><div><div  class="descP">'.$commentaire->getIDUSER().'</div><div class="descP" style="margin-left: 20px;color: black">'.$commentaire->getTEXTE(). ' </div><div style="color: #adadad;">' .$commentaire->getDATECOMMENTAIRE().' </div></div>   <div><a href="frontController.php?controller=proposition&action=likeCommentaire&id='.$proposition->getId().'&idCommentaire='.$commentaire->getIDCOMMENTAIRE().'"><img src="../assets/img/icons8-jaime.png"></a><a href="frontController.php?controller=proposition&action=dislikeCommentaire&id='.$proposition->getId().'&idCommentaire='.$commentaire->getIDCOMMENTAIRE().'"><img src="../assets/img/icons8-jaimepas.png"></a> '.$commentaire->getNBLIKE().'<a href="frontController.php?controller=proposition&action=deleteCommentaire&id='.$proposition->getId().'&idCommentaire='.$commentaire->getIDCOMMENTAIRE() .'"><img src="../assets/img/icons8-poubelle.png"></a></div></li>';
                }
                else
                {
                    echo '<li class="ligneExt"><div><div  class="descP">'.$commentaire->getIDUSER().'</div><div class="descP" style="margin-left: 20px;color: black">'.$commentaire->getTEXTE(). ' </div><div style="color: #adadad;">' .$commentaire->getDATECOMMENTAIRE().' </div></div>   <div><a href="frontController.php?controller=proposition&action=likeCommentaire&id='.$proposition->getId().'&idCommentaire='.$commentaire->getIDCOMMENTAIRE().'"><img src="../assets/img/icons8-jaime.png"></a><a href="frontController.php?controller=proposition&action=dislikeCommentaire&id='.$proposition->getId().'&idCommentaire='.$commentaire->getIDCOMMENTAIRE().'"><img src="../assets/img/icons8-jaimepas.png"></a> '.$commentaire->getNBLIKE().'</div></li>';
                }
             }
        }
        else
        {
            echo '<div> Pas encore de commentaires. </div>';
        } ?>
    </div>
</div>
</div>

