<div class="block">
    <div class="text-box">
    <form method="post" action="frontController.php?action=questionCreated">
        <fieldset>
            <legend>Créer une nouvelle question</legend>

            <p>
                <label>Question :</label>
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
    </div>
</div>
