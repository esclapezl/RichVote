<?php

namespace App\Lib;

use App\Config\Conf;
use App\Model\DataObject;
use App\Model\DataObject\User;

class VerificationEmail
{
    public static function envoiEmailValidation(User $utilisateur): void
    {

        $subject = 'Your email subject';

        $headers = "From: RichVote \r\n";
        //$headers .= "Reply-To: no-reply@abc.com \r\n";
        //$headers .= "CC: abc@abc.com\r\n";
        //$headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";


        $message  = '<div align="center" style="display:block;height:100%; width:100%;background-color: whitesmoke; justify-content: center">
<div style="text-align:center;height:100%; width:500px;background-color: white">
<div  style="background: linear-gradient( rgb(113, 117, 213) 0%, rgba(57,78,222,1) 100%);height:100px;">
<div style="color: white; font-weight:bolder;font-size:26px;padding: 30px 50px 0px 0px">Validez votre compte RichVote !</div>
</div>
<div style="margin-top: 40px">
<p style="color: black;width:60%;margin-left:auto;margin-right:auto; font-weight:bold;font-size:18px"> Plus qu\'une étape!</p>
<p style="color: #181818FF;width:60%;margin-left:auto;margin-right:auto;"> Utilisez le code de vérification ci-dessous pour finaliser votre inscription.</p></div>
<div style="margin-left: 150px;margin-right: 150px;margin-bottom: 40px ;background:#ffffff;border:2px solid #e2e2e2;line-height:1.1;text-align:center;text-decoration:none;display:block;border-radius:8px;font-weight:bold;padding:10px 40px">
<span style="color:#333;letter-spacing:5px">' . $utilisateur->getNonce().'</span>
</div>
<div style="margin-top: 40px">Si vous n\'êtes pas à l\'origines de cette demande, ignorez cet e-mail.</div>
<div style="background-color: black"><table role="presentation" width="100%" >
<tbody><tr>
<td style="padding:10px 10px 10px 20px" align="left"><div style="color: white;font-weight: bold"> richvote.website@gmail.&#8203;com</div></td> 
<td style="padding:10px 20px 10px 10px" align="right"><img  src="https://cdn.discordapp.com/attachments/1028652119558987950/1050906297479995392/logo.png" width="60px" height="60px">  </td> 
</tr></tbody></table></div>
</div></footer></div></div>
';




        mail($utilisateur->getEmail(),
            $subject,
            $message,
            $headers);



    }

}
