<?php

namespace App\Covoiturage\Lib;

use App\Config\Conf;
use App\Model\DataObject;

class VerificationEmail
{
    public static function envoiEmailValidation(User $utilisateur): void
    {
        $loginURL = rawurlencode($utilisateur->getId());
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