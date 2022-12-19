<?php
use App\Model\DataObject\Demande;
/**
 * @var Demande[] $demandes
 * @var string $action
 */
?>
<div class="block" >
    <div class="column">
        <div class="text-box">
            <a id="fleche" class="optQuestion" href=frontController.php?controller=question&action=read&id=<?=$_GET['id']?>>↩</a>
<form action="<?=$action?>" method="post">
    <?php
    if(empty($demandes)){
        echo "<h1>Aucunes demandes disponibles</h1>";
    }
    else{
        foreach ($demandes as $demande){
            $idUser = $demande->getIdDemandeur();
            $htmlId = htmlspecialchars($idUser);
            echo "<div class='ligneExt'>
                            <li class='ligneExt'> <a href='frontController.php?controller=user&action=read&id=$idUser'> $htmlId</a></span></li>
                            <label for='checkbox' class='checkbox'> 
                                <input type='checkbox' id='cb[$idUser]' name='user[$idUser]' value='$idUser'>
                            </label>
                          </div>";
        }
        echo '<div class="descG"></div> <div class="ligneCent"> <input type="submit" value="Ajouter les utilisateurs selectionnés" class="optQuestion"></div></form>';

    }
        ?>
</form>
        </div>
    </div>
</div>
