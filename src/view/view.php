<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <title><?php echo $pagetitle; ?></title>
</head>
<body>
    <header>

    </header>
    <main>
        <?php
        require __DIR__ . "/{$cheminVueBody}";
        ?>
    </main>
    <footer>
        <br>
        <p>
            Site de covoiturage de zinzin
        </p>


    </footer>
</body>
</html>

