<?php

namespace App\Covoiturage\Lib;

use App\Covoiturage\Config\Conf;
use App\Covoiturage\Model\DataObject\Utilisateur;

class VerificationEmail
{
    public static function envoiEmailValidation(Utilisateur $utilisateur): void
    {
        $loginURL = rawurlencode($utilisateur->getLogin());
        $nonceURL = rawurlencode($utilisateur->getNonce());
        $absoluteURL = Conf::getAbsoluteURL();
        $lienValidationEmail = "$absoluteURL?action=validerEmail&controller=utilisateur&login=$loginURL&nonce=$nonceURL";
        $corpsEmail = "<a href=\"$lienValidationEmail\">Validation</a>";

        // Temporairement avant d'envoyer un vrai mail
        MessageFlash::ajouter("success", $corpsEmail);
    }

    public static function traiterEmailValidation($login, $nonce): bool
    {
        // À compléter
        return true;
    }

    public static function aValideEmail(Utilisateur $utilisateur) : bool
    {
        // À compléter
        return true;
    }
}