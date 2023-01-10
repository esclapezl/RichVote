<?php
use App\Model\DataObject\Proposition;
use \App\Lib\ConnexionUtilisateur;
use  \App\Model\DataObject\Commentaire;
/** @var Proposition $proposition
 * @var Array $commentaires
 * @var Commentaire $commentaire
 * @var string $roleProposition
 * @var Demande[] $demandes
 */
$nbDemandes= sizeof($demandes);
$idProposition = $proposition->getId();
?>
<div class="block" >
    <div class="column">
    <div class="text-box">
        <div class="ligneExt"> <a id="fleche" class="optQuestion" href="frontController.php?controller=question&action=read&id=<?=$proposition->getIdQuestion()?>">↩</a>
            <?=($roleProposition=='responsable'&&$nbDemandes > 0)?'<div class="ligneExt"><span></span><div class="iconsNotifs" id="iconNotification2">'.$nbDemandes.'</div></div><a  class="optQuestion" href="frontController.php?controller=proposition&action=readDemandeAuteur&id=' . rawurlencode($idProposition) .'"> Demandes de Co-Auteurs </a>':''?>
        </div>
            <div class="ligneExt">
                <h1><?=htmlspecialchars($proposition->getIntitule())?></h1><h3>Détail de la proposition</h3></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt">
            <?php
            if ($roleProposition=='responsable'){
                echo '<div class="ligneAlign">'.
                    '<a href=frontController.php?controller=proposition&action=update&id=' . rawurlencode($idProposition) . ' ><img class="icons" alt="modifier" src="../assets/img/icons8-crayon-48.png"></a>' .
                    '<a href=frontController.php?controller=proposition&action=delete&id='. rawurlencode($idProposition) . ' ><img class="icons" id="poubelle" alt="supprimer question" src="../assets/img/icons8-poubelleBlanc.svg"></a>' .
                    '<a class="optQuestion" href="frontController.php?controller=proposition&action=addAuteursToProposition&id=' . rawurldecode($idProposition) .'"> ajouter des auteurs </a>'
                    .'</div>';
            }
            elseif ($roleProposition!='auteur'){
                echo '<a class="optQuestion" href="frontController.php?controller=proposition&action=addDemandeAuteur&id=' . $proposition->getId() . '"> devenir auteur de cette proposition</a>';
            }?>
        </div>
        <br>

        <?php
        foreach ($proposition->getSectionsTexte() as $infos){
            $idSection = $infos['section']->getId();
            $texte = $infos['texte'];
            $nbLikes = (new \App\Model\Repository\SectionRepository())->getNbLikes($idSection,$idProposition);

            //echo '<div class="ligneExt"><h3>' . ucfirst(htmlspecialchars($infos['section']->getIntitule())) . "</h3></div>";
            //echo "<div class='ligne'></div>" . $texte ;
            echo $texte ;
            if((new \App\Model\Repository\SectionRepository())->userALike($idSection,ConnexionUtilisateur::getLoginUtilisateurConnecte(),$idProposition))
            {
                echo '<div><a href="frontController.php?controller=proposition&action=likeSectionProposition&id='.$idSection.'&idQuestion='.$proposition->getIdQuestion().'&idProposition='.$proposition->getId().'"><img src="../assets/img/icons8-jaimeBleu.png"></a>     '.$nbLikes.'</div></li>';
            }
            else
            {
                echo '<div><a href="frontController.php?controller=proposition&action=likeSectionProposition&id='.$idSection.'&idQuestion='.$proposition->getIdQuestion().'&idProposition='.$proposition->getId().'"><img src="../assets/img/icons8-jaimeBlanc.png"></a>     '.$nbLikes.'</div></li>';
            }
            echo '<br>';
        }?>
    </div>

    <div class="text-box" >
        <h3> Commentaires  </h3>
        <form action="frontController.php?controller=proposition&action=ajtCommentaire&id=<?php echo $_GET['id'] ?>" method="post">
            <input  type="text" name="commentaire" id="commentaire" required>
            <div class="ligneExt"><div></div>
                <input type="image" src="../assets/img/icons8-coche-white.svg" border="0" alt="Submit" /></div>
        </form>
        <div class="descG"></div>

        <?php
        if(!empty($commentaires))
        {
            foreach ($commentaires as $commentaire)
            {
                if($commentaire->getIDUSER() == ConnexionUtilisateur::getLoginUtilisateurConnecte() || ConnexionUtilisateur::estAdministrateur())
                {
                    echo '<li class="ligneExt"><div><div  class="descP">'.$commentaire->getIDUSER().'</div><div class="descP" style="margin-left: 20px;color: black">'.$commentaire->getTEXTE(). ' </div><div style="color: #adadad;">' .$commentaire->getDATECOMMENTAIRE().' </div></div>   <div><a href="frontController.php?controller=proposition&action=deleteCommentaire&id='.$proposition->getId().'&idCommentaire='.$commentaire->getIDCOMMENTAIRE() .'"><img src="../assets/img/icons8-poubelleNoir.svg"></a></div></li>';
                }
                else
                {
                    echo '<li class="ligneExt"><div><div  class="descP">'.$commentaire->getIDUSER().'</div><div class="descP" style="margin-left: 20px;color: black">'.$commentaire->getTEXTE(). ' </div><div style="color: #adadad;">' .$commentaire->getDATECOMMENTAIRE().' </div></div>   </li>';
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

