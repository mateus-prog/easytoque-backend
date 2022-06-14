<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\HttpStatus;
use App\Traits\ApiResponser;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
//Alias the League Google OAuth2 provider class
use League\OAuth2\Client\Provider\Google;

use PHPMailer\PHPMailer\Exception;

class MailerController extends Controller {

    use ApiResponser;

    // =============== [ Email ] ===================
    public function email() {
        return view("email");
    }

    // ========== [ Compose Email ] ================
    public function composeEmail(Request $request) {
        //SMTP needs accurate times, and the PHP time zone MUST be set
        //This should be done in your php.ini, but this is how to do it if you don't have access to that
        date_default_timezone_set('Etc/UTC');

        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {
            // Email server settings
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            //Set the SMTP port number:
            // - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
            // - 587 for SMTP+STARTTLS
            $mail->Port = 465;

            //Set the encryption mechanism to use:
            // - SMTPS (implicit TLS on port 465) or
            // - STARTTLS (explicit TLS on port 587)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;

            //Set AuthType to use XOAUTH2
            $mail->AuthType = 'XOAUTH2';                                  //Set the SMTP username 
            
            //Start Option 1: Use league/oauth2-client as OAuth2 token provider
            //Fill in authentication details here
            //Either the gmail account owner, or the user that gave consent
            $email = 'emailvucom@gmail.com';
            $clientId = '518263093860-sv6g3pmjkg544i1uc7am61n8ivvf04ch.apps.googleusercontent.com';
            $clientSecret = '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';

            //Obtained by configuring and running get_oauth_token.php
            //after setting up an app in Google Developer Console.
            $refreshToken = '1//0fVp3ZxiOZRz8CgYIARAAGA8SNwF-L9IruqCJA-c2zfJnz812FtJLjRG9HVSR3jkyEbYVdYr5H4rD7tw1YRD3ik3jlmMm8-KTRi8';

            //Create a new OAuth2 provider instance
            $provider = new Google(
                [
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                ]
            );

            //Pass the OAuth provider instance to PHPMailer
            $mail->setOAuth(
                new OAuth(
                    [
                        'provider' => $provider,
                        'clientId' => $clientId,
                        'clientSecret' => $clientSecret,
                        'refreshToken' => $refreshToken,
                        'userName' => $email,
                    ]
                )
            );
            //End Option 1

            //Set who the message is to be sent from
            //For gmail, this generally needs to be the same as the user you logged in as
            $mail->setFrom($email, 'Email Vucom');
            

            $mail->addAddress($request->mailRecipient); //email do destinatario
            //$mail->addCC($request->emailCc); //email com cópia para
            //$mail->addBCC($request->emailBcc); //email com cópia oculta para
            //$mail->addBCC('mateus.guizelini@hotmail.com'); //email com cópia oculta para

            //$mail->addReplyTo('sender-reply-email', 'sender-reply-name');

            if(isset($_FILES['emailAttachments'])) {
                for ($i=0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $mail->isHTML(true);                // Set email content format to HTML

            $mail->Subject = $request->mailSubject; //assunto
            $mail->Body    = $request->mailBody;    //corpo do email

            // $mail->AltBody = plain text version of email body;

            if( !$mail->send() ) {
                //return back()->with("failed", "Email não enviado.")->withErrors($mail->ErrorInfo);
                
                return $this->error('Email não enviado.', HttpStatus::UNAUTHORIZED);
            }else {
                return $this->success('O E-mail foi enviado com sucesso.', HttpStatus::SUCCESS);
            }

        } catch (Exception $e) {
            //return back()->with('error','A Mensagem não pode ser enviada.');
            return $this->error('A Mensagem não pode ser enviada.', HttpStatus::SERVER_ERROR);
        }
    }
}