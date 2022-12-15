<?php
use \App\Lib\ConnexionUtilisateur;
use App\Model\Repository\VoteRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\DataObject\Question;
use App\Model\Repository\UserRepository;
use App\Model\Repository\PhaseRepository;
use App\Model\Repository\PropositionRepository;
/** @var Question $question */


$phases=(new \App\Model\Repository\PhaseRepository())->getPhasesIdQuestion($question->getId());
?>


<div class="block">
    <div class="text-box">
        <a class="optQuestion" id="fleche" href=frontController.php?controller=question&action=read&id=<?=$question->getId()?>>↩</a>
                <div class="column">

                    <h3><?=htmlspecialchars($question->getIntitule())?></h3>
                    <br>
                    <div class="ligne"></div>
                    <br>
                    <h1>Résultats Finaux</h1>
                    <div class="descG"></div>

        <div class="results">
            <?php
            $bool=0;
            foreach ($phases as $phase)
            {
                if($phase->getType()!="consultation"){
                    $bool=1;
                }
            }
            $cpt=0;
            if($bool==1){
                    if ($phases[count($phases)-1]->getType() == "consultation") {
                        echo "<p>Il n'y a pas eu de vote sur cette question.</p>";
                    }
                    else {
                        $scores = [];
                        $propositions = [];

                        $propositionsScore = (new PropositionRepository())->selectAllWithScore($phase->getId());
                        foreach ($propositionsScore as $proposition) {
                            $propositions[] = $proposition[0];
                            $scores[$proposition[0]->getId()] = $proposition[1];
                        }
                        $cpt=0;
                        foreach ($propositions as $proposition) {
                            $idProposition = $proposition->getId();
                            $score = $scores[$idProposition];
                            if($cpt==0){
                                echo "<h1>" . $proposition->getIntitule() ." l'emporte !</h1><h3>avec un score de ". $score . ".</h3>";
                                $cpt++;
                            }
                            else{
                                echo "<p>" . $proposition->getIntitule() ." perd avec un score de : ". $score . "</hp>";
                            }

                        }
                    }

            }
            else{
                if($phases[0]->getType()=="consultation"){
                    echo "<h3>Il n'y a pas eu de phases de vote sur cette question.</h3>";

                }
            }

            ?>

        </div>

    </div>




                <?php

                if(ConnexionUtilisateur::estConnecte()) {
                    //if($question->estarchive()){


                }
                else{
                    echo "<div class='LigneCent'><h3>Vous n'êtes pas connecté. Connectez-vous pour plus d'informations !</h3></div>";
                }

                ?>


