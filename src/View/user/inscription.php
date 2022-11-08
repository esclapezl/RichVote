<div class="block">
    <div class="text-box">
        <form action="frontController.php?controller=user&action=inscrit" method="post">
            <label> identifiant :<br>
                <input type="text" id="id" name="identifiant" placeholder="Identifiant" required
                    <?php if(isset($persistanceId))
                    {
                    echo 'value="'.$persistanceId.'"';
                    }
                    ?>>
            </label> <br>
            <label> mot de passe :<br>
                <input type="password" id="mpd" name="motDePasse" placeholder="********" required>
            </label> <br>
            <label> confirmer le mot de passe : <br>
                <input type="password" id="confirmationMpd" name="confirmerMotDePasse" placeholder="********" required>
            </label> <br>
            <input type="submit" value="S'inscrire" name="submit">
            <?php if(isset($msgErreur))
            {
                echo '<p>'.$msgErreur.'</p>';
            }
            ?>
        </form>

    </div>

</div>
