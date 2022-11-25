
<div class="block">
    <div class="text-box">
        <form method="post" action="frontController.php?controller=user&action=connected">
            <div class="ligneExt"> <h1>Modifier le mot de passe :</h1></div>
                <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
            <p>
            <div class="descG"></div>
            <h3>Ancien mot de passe :</h3>
            <input type="text" id="id" name="aMdp" placeholder="********" size="50"  required>
            <div class="descP"></div>

            <h3>Nouveau mot de passe :</h3>
            <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" id="nMpd" name="nMpd" placeholder="********" size="50" required>
            <div class="descG"></div>

            <h3>Confirmer le nouveau mot de passe :</h3>
            <input type="password" id="cNMpd" name="motDePasse" placeholder="********" size="50" required>
            <div class="descG"></div>

            </p>
            <div class="ligneCent"> <input class="optQuestion" type="submit" value="Se connecter"/></div>
        </form>

    </div>
</div>
