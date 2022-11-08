<div class="block">
    <div class="text-box">
        <form action="frontController.php?controller=user&action=inscription" method="post">
            <label> identifiant :<br>
                <input type="text" id="id" name="identifiant" placeholder="Identifiant" required
                    <?php if(isset($_POST['submit']))
                    {
                    echo 'value="'.$_POST["identifiant"].'"';
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
        </form>

        <?php
        function erreurMdp()
        {
            echo '<p>'."Les mots de passe doivent être identiques. ".$_POST["confirmerMotDePasse"].'</p>';
        }
        if(isset($_POST['submit']))
        {
            if($_POST["confirmerMotDePasse"] != $_POST["motDePasse"])
            {
                erreurMdp();
            }
            else
            {

            }

        }
        else
        {

        }

        ?>

    </div>

</div>
