<?php
/**
 * @var array $propositionsScore
 */
foreach ($propositionsScore as [$proposition, $scores]){
    echo $proposition->getIntitule() . '    scores: <ol>';
    foreach ($scores as $nomScore=>$score){
        $tradNom = "";
        switch ($nomScore){
            case '0':
                $tradNom = 'à rejeter';
                break;
            case '1':
                $tradNom = 'insuffisant';
                break;
            case '2':
                $tradNom = 'passable';
                break;
            case '3':
                $tradNom = 'assez bien';
                break;
            case '4':
                $tradNom = 'bien';
                break;
            case '5':
                $tradNom = 'très bien';
                break;

        }
        echo "<li>Pour $tradNom : $score </li>";
    }
    echo '</ol>';
}
?>


