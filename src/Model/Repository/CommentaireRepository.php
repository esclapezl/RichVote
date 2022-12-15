<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Commentaire;
use App\Model\DataObject\User;
use MongoDB\Driver\Exception\CommandException;

class CommentaireRepository extends AbstractRepository
{

    protected function getNomTable(): string
    {
        return 'SOUVIGNETN.Commentaires';
    }

    protected function getNomClePrimaire(): string
    {
        return '';
    }

    protected function getIntitule(): string
    {
        return '';
    }

    protected function getNomsColonnes(): array
    {
        return array('');
    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Commentaire(
            $objetFormatTableau['IDPROPOSITION'],
            $objetFormatTableau['IDUSER'],
            $objetFormatTableau['TEXTE'],
            $objetFormatTableau['DATECOMMENTAIRE'],
            $objetFormatTableau['NBLIKE'],
            $objetFormatTableau['IDCOMMENTAIRE']
        );
    }

    public function commenter(int $idProposition,string $idUser,string $texte,string $date): void
    {
        $commentaire=new Commentaire($idProposition,$idUser,$texte,$date);
        $sql = "INSERT INTO souvignetn.commentaires(IDPROPOSITION, IDUSER, TEXTE, DATECOMMENTAIRE,NBLIKE) VALUES(:IDPROPOSITION, :IDUSER, :TEXTE, to_date(:DATECOMMENTAIRE),0,NULL)";
        $pdo = DatabaseConnection::getInstance()::getPdo();

        ($pdo->prepare($sql))->execute(array('IDPROPOSITION' => $commentaire->getIDPROPOSITION(),
            'IDUSER' => $commentaire->getIDUSER(),
            'TEXTE' => $commentaire->getTEXTE(),
            'DATECOMMENTAIRE' => $commentaire->getDATECOMMENTAIRE()));
    }

    public function selectAllProp(string $idProposition):Array
    {
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $sqlUpdate = 'CALL updatePhase()';
        $pdo->query($sqlUpdate);


        $pdoStatement = $pdo->query('SELECT * FROM souvignetn.commentaires WHERE IDPROPOSITION ='.$idProposition);

        $tabRepo = array();
        foreach($pdoStatement as $objetFormatTab){
            $tabRepo[] = $this->construire($objetFormatTab);
        }

        return $tabRepo;
    }
}