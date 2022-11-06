
<div class="block">
    <div class="text-box">
        <div>
            <input type="text" id="id" name="identifiant" placeholder="Identifiant" required>
        </div>
        <div>
            <input type="password" id="mpd" name="motDePasse" placeholder="********" required>
        </div>
        <input type="submit" value="Se connecter">

        <p> pas de compte ?
            <a href="frontController.php?controller=<?= $_GET['controller']?>&action=inscription"> Inscrivez vous </a>
        </p>
    </div>
</div>

