<div class="block">
    <div class="text-box">
        <form action="frontController.php?controller=user&action=inscrit" method="post">
            <div class="ligneExt"> <h1>Inscription :</h1> <div>Vous êtes déjà inscrit ?<h3><a href="frontController.php?controller=user&action=connexion">Connectez-vous</a></h3></div></div>
            <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
            <p>
            <div class="descG"></div>
            <h3>Identifiant :</h3>
            <input type="text" id="id" name="identifiant" placeholder="Identifiant" size="50"  required
                <?php if(isset($persistanceId))
                {
                    echo 'value="'.$persistanceId.'"';
                }
                ?>>
            <div class="descP"></div>

            <h3>Mot de passe :</h3>
            <input type="password" id="mpd" name="motDePasse" placeholder="********" size="50" required>

            <div class="descP"></div>

            <h3>Confirmer le mot de passe :</h3>
            <input type="password" id="confirmationMpd" name="confirmerMotDePasse" placeholder="********" size="50" required>
            <div class="descG"></div>
            </p>
            <div class="ligneCent"> <input class="optQuestion" type="submit" name="submit" value="S'inscrire"/></div>
            <?php if(isset($msgErreur))
            {
                echo '<p>'.$msgErreur.'</p>';
            }
            ?>

        </form>

    </div>

</div>
