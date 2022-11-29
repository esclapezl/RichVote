<?php

namespace App\Lib;

use App\Model\HTTP\Session;

class ConnexionUtilisateur
{
    // L'utilisateur connecté sera enregistré en session associé à la clé suivante
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): void
    {
        Session::getInstance()->enregistrer(ConnexionUtilisateur::$cleConnexion,$loginUtilisateur);
    }

    public static function estConnecte(): bool
    {
        if(Session::getInstance()->lire(ConnexionUtilisateur::$cleConnexion) != null)
        {return true;}
        else return false;
    }

    public static function deconnecter(): void
    {
        Session::getInstance()->supprimer(ConnexionUtilisateur::$cleConnexion);
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
        return Session::getInstance()->lire(ConnexionUtilisateur::$cleConnexion);
    }
}