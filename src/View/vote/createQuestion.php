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
    <p>header question</p>
</header>
<body>

<main>

    <div>
    <input type="text" id="tq" name="titreQuestion" placeholder="Titre de la question" required>
    </div>
    <label for="nbSections"> Nombre de sections </label>
    <input min="1" max="10" id="nbSections">


    <div>
    <input type="text" id="" name="titreQuestion" placeholder="Titre de la question" required>
    </div>

    <?php
    /** @var $cheminVueBody string */
    //require __DIR__ . "/{$cheminVueBody}";
    ?>
</main>
<footer>
    <p>
        foot question
    </p>
</footer>
</body>
</html>

