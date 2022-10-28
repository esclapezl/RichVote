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
<body>
<header>
    <p>header accueil</p>
</header>
<main>
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
