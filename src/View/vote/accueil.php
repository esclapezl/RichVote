<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../Web/CSS/style.css">
    <meta charset="UTF-8">
    <title><?php
        /** @var $pagetitle string */
        echo $pagetitle;
        ?></title>
</head>
<header>
    <p>header accueil</p>
</header>
<body>

<main>

    <p>
    <img src="../../../images/logoVote.png" alt="logo of a vote">
    </p>
    <div>
        <input type="text" id="id" name="identifiant" placeholder="Identifiant" required>
    </div>
    <div>
        <input type="password" id="mpd" name="motDePasse" placeholder="********" required>
    </div>
    <input type="submit" value="Se connecter">

    <?php
    /** @var $cheminVueBody string */
    //require __DIR__ . "/{$cheminVueBody}";
    ?>
</main>
<footer>
    <p>
        foot accueil
    </p>
</footer>
</body>
</html><?php
