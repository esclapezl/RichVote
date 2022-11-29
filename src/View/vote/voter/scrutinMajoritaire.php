<div class="block">
    <form method="post" action="frontController.php?controller=vote&action=scrutinMajoritaireVoted">
        <?php
        /** @var Proposition[] $propositions */
        foreach ($propositions as $proposition){
            $idProposition = $proposition->getId();
            echo"<input type='radio' id='p$idProposition' name='idProposition' value='$idProposition'> $idProposition </input>";
        }

        ?>
        <button type="submit"> valider </button>
    </form>
</div>