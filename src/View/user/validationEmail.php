<?php
/** @var string $idUser
*/
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Validation de l'email :</h1></div>
        <form method="post" action="frontController.php?controller=user&action=userValide&idUser=<?=$idUser?>">
            <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
            <div class="descG"></div>
            <h3>Code de verification :</h3>
            <input type="text" id="nonce" name="nonce" placeholder="_ _ _ _ _ _" size="6"  required>
            <div class="descG"></div>
            <div class="ligneCent"> <input class="optQuestion" type="submit" value="Valider"/></div>
        </form>


    </div>
</div>