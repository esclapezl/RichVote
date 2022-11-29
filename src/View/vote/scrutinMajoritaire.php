<div class="block">
    <div class="text-box">
        <div class="ligneExt">
        <a class="optQuestion" href=<?=$_SERVER['HTTP_REFERER']?>>↩</a>
            <img src="../assets/img/logo.png" alt="RichVote" id="logo">
        </div>
        <div class="ligneCent">

        <h2>
            Qu'est ce que le scrutin Majoritaire ?
        </h2></div>
        <div class="descG"></div>
        <p>
            Le scrutin majoritaire est un système électoral caractérisé par la victoire du ou des candidats qui
            obtiennent le plus de suffrages, et qui exclut ou limite la représentation des candidats minoritaires.
        </p>
        <div class="descG"></div>
    </div>

    <form method="get" action="frontController.php?controller=vote&action=scrutinMajoritaireVoted&score=1&idUser='souvignetn'">
        <?php
        /** @var Proposition[] $propositions */
        foreach ($propositions as $proposition){
            $idProposition = $proposition->getId();
            echo"<input type='radio' id='p$idProposition' name='idProposition' value='$idProposition'>";
        }

        ?>
        <button type="submit"> valider </button>
    </form>
</div>