
<?php
use App\Model\DataObject\User;
use \App\Model\Repository\UserRepository;

/** @var User $user */

$userRepository = new UserRepository();

$mdpOublie = false;
if($userRepository->selectMdpHache($_GET['id']) != null) $mdpOublie=true;

?>

<div class="block">
    <div class="text-box">
        <?php
        if (!$mdpOublie)
        {
            echo    '<form method="post" id="formConnect" action="frontController.php?controller=user&action=updated&id='.$user->getId().'">';
        }
        else
        {
            echo    '<form method="post" id="formConnect" action="frontController.php?controller=user&action=updatedMdpOublie&id='.$_GET['id'].'">';
        }
        ?>

            <div class="ligneExt"> <h1>Modifier le mot de passe :</h1></div>
                <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
            <p>
            <div class="descG"></div>
        <?php
        if (!$mdpOublie)
        {
            echo    '<h3>Ancien mot de passe :</h3>
            <input type="password" id="aMdp" name="aMdp" placeholder="********" size="50"  required>
            <div class="descP"></div>';
        } ?>
            <h3>Nouveau mot de passe :</h3>
            <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" id="nMdp" name="nMdp" placeholder="********" size="50" required>
            <div class="descG"></div>

            <h3>Confirmer le nouveau mot de passe :</h3>
            <input type="password" id="cNMdp" name="cNMdp" placeholder="********" size="50" required>
            <div class="descG"></div>

            </p>
            <div class="ligneCent"> <input class="optQuestion" type="submit" value="Valider"/></div>
            <?php if(isset($msgErreur))
            {
                echo '<p>'.$msgErreur.'</p>';
            }
            ?>
        </form>

    </div>
</div>
