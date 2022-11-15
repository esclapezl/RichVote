
<div class="block">
    <div class="text-box">
        <form method="post" action="frontController.php?controller=user&action=connected">
            <div class="ligneExt"> <h1>Connexion :</h1> <div>Vous n'avez pas de compte ? <h3><a href="frontController.php?controller=user&action=inscription">Inscrivez vous</a></h3></div></div>
            <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
            <p>
            <div class="descG"></div>
            <h3>Identifiant :</h3>
            <input type="text" id="id" name="identifiant" placeholder="Identifiant" size="50"  required>
            <div class="descP"></div>

            <h3>Mot de passe :</h3>
            <input type="password" id="mpd" name="motDePasse" placeholder="********" size="50" required>
            <div class="descG"></div>

            </p>
            <div class="ligneCent"> <input class="optQuestion" type="submit" value="Se connecter"/></div>
        </form>

    </div>
</div>

