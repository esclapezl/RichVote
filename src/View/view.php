<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <title><?php
            /** @var $pagetitle string */
            echo $pagetitle;
            ?>
        </title>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <header>
            <nav class="navMenu">
                <a href="#">Home</a>
                <a href="#"><img src="images/logo.png" alt="RichVote" id="logo"></a>
                <a href="#">About</a>
            </nav>
        </header>
        <main>
            <?php
            /** @var $cheminVueBody string */
            require __DIR__ . "/{$cheminVueBody}";
            ?>
        </main>
        <footer>
            <p>mon super site de vote</p>
        </footer>
    </body>
</html>