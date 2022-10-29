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
    <p>la y a des trucs de nav</p>
</header>
<main>
    <?php
    /** @var $cheminVueBody string */
    require __DIR__ . "/{$cheminVueBody}";
    ?>
</main>
<footer>
    <p>
        mon super site de vote
    </p>
</footer>
</body>
</html>