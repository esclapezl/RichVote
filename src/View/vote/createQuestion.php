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

    <form method="post" action="frontController.php?action=questionCreated">
        <fieldset>
            <legend>Créer une nouvelle question</legend>

            <p>
                <input type="text" id="tq" name="titreQuestion" placeholder="Titre de la question" required>
            </p>

            <p>
                <label for="nbSections"> Nombre de sections </label>
                <input type="number" min="1" max="10" id="ns" name="nbSections">
            </p>

            <p>
                <input type="submit" value="envoyer"/>
            </p>
        </fieldset>
    </form>

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

