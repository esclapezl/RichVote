<?php
use App\Model\DataObject\Demande;
/**
 * @var Demande[] $demandes
 * @var string $action

 */
?>

<form action="<?=$action?>" method="post">
    <?php
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
    ?>
</form>
