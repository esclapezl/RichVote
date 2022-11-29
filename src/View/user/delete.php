
<?php
use App\Model\DataObject\User;

/** @var User $user */
?>

<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Suppresion du compte</h1></div>
        <form method="post" action="frontController.php?controller=user&action=deleted&id=<?=$user->getId()?>">
           <div class="ligneExt"><div class="ligne"></div></div>
            <div class="descG"></div>
            <p>
            <h3>Mot de passe du compte :</h3>
            <input type="password" id="mdp" name="mdp" placeholder="********" size="50"  required>
            <div class="descP"></div>

            <h3>Entrer à nouveau le mot de passe du compte :</h3>
            <input type="password" id="cMdp" name="cMdp" placeholder="********" size="50" required>
            <div class="descG"></div>
            </p>
            <div class="ligneCent"> <input class="optQuestion" type="submit" value="Valider la suppression du compte"/></div>
        </form>

    </div>
</div>

