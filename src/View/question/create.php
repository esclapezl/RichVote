<div class="block">
    <div class="text-box">
    <form method="post" action="frontController.php?controller=question&action=created">
        <fieldset>
            <div class="descP"></div>
            <h1><legend>Créer une nouvelle question</legend></h1>
            <div class="ligneCent"><div class="ligne"></div></div>
            <div class="descG"></div>

            <p>
                <h3>Question :</h3>
                <input type="text" id="tq" name="titreQuestion" placeholder="Titre de la question" size="50" required>
            <div class="descP"></div>
            </p>

            <p>
                <label for="dF">Date limite de la question</label>
                <input type="date" id="dF" name="dateFermeture" required>
            </p>

            <p>
                <h3><label for="nbSections"> Nombre de sections :</label></h3>
                <input type="number" min="1" max="10" id="ns" name="nbSections" value="1" placeholder="1">
            <div class="descG"></div>
            </p>


                <div class="ligneCent"> <input class="optQuestion" type="submit" value="sauvegarder"/></div>

        </fieldset>
    </form>
    </div>
</div>
