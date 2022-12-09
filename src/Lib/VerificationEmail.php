<?php

namespace App\Lib;

use App\Config\Conf;
use App\Model\DataObject;
use App\Model\DataObject\User;

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
        MessageFlash::ajouter("info", $corpsEmail);
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