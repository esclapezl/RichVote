<div class="block">
    <div class="text-box">
    <form method="post" action="frontController.php?controller=vote&action=scrutinMajoritaireVoted">
        <div class="ligneCent"> <h1>Vous pouvez voter.</h1></div>

        <div class="ligneCent"><div class="ligne"></div></div><br>
        <div class="ligneCent"> <h3>Votre choix restera confidentiel.</h3></div>
        <div class="descG"></div>


        <?php
        /** @var Proposition[] $propositions */
        foreach ($propositions as $proposition){
            $idProposition = $proposition->getId();
            $intituleProposition = $proposition->getIntitule();
            echo '<div class="ligneCent">
                    <h3>'.ucfirst($intituleProposition).' → </h3>
                    <label for="checkbox"  class="checkbox"><input type="radio" id='.$idProposition .' name=idProposition value='.$idProposition .'></label>
                    </div><div class="descP"></div>';
        }
        ?>
        <div class="descG"></div>
        <div class="ligneCent"><button type="submit" class="opt"><img src="../assets/img/icon-vote.png"></button></div>



    </form>
    </div>
</div>