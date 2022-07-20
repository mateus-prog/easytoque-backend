<?php

namespace App\Services\Mail;

use Illuminate\Http\Request;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;

use League\OAuth2\Client\Provider\Google;

use App\Repositories\Elouquent\LogRepository;
use App\Helpers\Log;
use Exception;

class MailService
{
    public function __construct()
    {
        $this->logRepository = new LogRepository();
    }

    public function sendMail($mailRecipient, $mailSubject, $mailBody, $userId, $message = '')
    {
        $idUserLog = $userId;

        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {
            $mail->isSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->Host = 'smtp.gmail.com';
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;

            //Set AuthType to use XOAUTH2
            $mail->AuthType = 'XOAUTH2';
            $mail->Port = 465;
            //Set the SMTP port number:
            // - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
            // - 587 for SMTP+STARTTLS

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            $mailTo = 'parceiros@toquecolor.com.br';
            $clientId = '518263093860-sv6g3pmjkg544i1uc7am61n8ivvf04ch.apps.googleusercontent.com';
		    $clientSecret = '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';
            //$refreshToken = '1//0fVp3ZxiOZRz8CgYIARAAGA8SNwF-L9IruqCJA-c2zfJnz812FtJLjRG9HVSR3jkyEbYVdYr5H4rD7tw1YRD3ik3jlmMm8-KTRi8';
		    //$refreshToken = '1//095p1uxhhmp_cCgYIARAAGAkSNwF-L9Ir-bhAGxpvOymsmjAEJUyYtc_M5IBlWiilXkN15F2aLBqqDm5Dzh-9zN-al0ecZfYIaXU';
            //$refreshToken = '1//09HSE5cbEo8dSCgYIARAAGAkSNwF-L9IrXmzs4TXrARGqiirNLRsfVaWEHKEG-Cjz0W6TGULsPwzaAWpLRljhcVYaEvq8QudgjWc';
            $refreshToken = '1//09ihZIonqB1TcCgYIARAAGAkSNwF-L9IrFo0VKN90GmrEIsBbVj0gG2fT6982MIf_FFcffV-0winDIdS7iU8CzdUPCwJMVp4yksE';
            
            $provider = new Google(
                [
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                ]
            );
            
            $mail->setOAuth(
                new OAuth(
                    [
                        'provider' => $provider,
                        'clientId' => $clientId,
                        'clientSecret' => $clientSecret,
                        'refreshToken' => $refreshToken,
                        'userName' => $mailTo,
                    ]
                )
            );
            
            $mail->setFrom($mailTo, 'Parceiros Easytoque');
            $mail->addAddress($mailRecipient); //email do destinatario
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

            $mail->Subject = $mailSubject; //assunto
            $mail->Body    = $mailBody;    //corpo do email

            // $mail->AltBody = plain text version of email body;

            if( !$mail->send() ) {
                $messageLog = "E-mail";
                $status = false;
                
                //return $this->error('Email não enviado.', HttpStatus::UNAUTHORIZED);
            }else {
                $messageLog = "E-mail";
                $status = true;
                //return $this->success('E-mail foi enviado com sucesso.', HttpStatus::SUCCESS);
            }

        } catch (Exception $e) {
            $messageLog = "E-mail";
            $status = false;
            //return $this->error('A Mensagem não pode ser enviada.', HttpStatus::SERVER_ERROR);
        }

        $actionId = 6;
        //$idUserLog = $user->id;

        $messageLog .= ' - ' . $message;

        $log = Log::createLog($idUserLog, $messageLog, $actionId, $status);
        $this->logRepository->store($log);
    }

    public function MailBody($body){
        $html = 
        '<style type="text/css">
        img {
        max-width: 100%;
        }
        body {
        -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 20px;
        }
        p,td{line-height:1.5em;display: block;}
        body {
        background-color: #f6f6f6;
        overflow: hidden;
        }
        img{max-width: 100%;}
        .button {
            padding: 10px 20px;
            border-radius: 5px;
            -o-border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            background-color: #E76131;
            text-transform: capitalize;
            color: #FFF;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }
        @media only screen and (max-width: 640px) {
            .conteudo {
                width: 94%;
            }
            .hidden-cel {display: none;}
        }
        </style>
        <body>
        <meta charset="UTF-8" />
        <br/><br/>
        <table style="margin:15px 0;" width="100%" bgcolor="#f6f6f6">
            <tr>
                <td>
                    <table width="640" class="conteudo" align="center" bgcolor="#fff" style="border:1px solid #e9e9e9;padding:20px;font-size:14px;color:#404040;font-family:arial">
                        <tr>
                            <td>
                                <center><img src="http://loja.easytoque.com.br/skin/frontend/easytoque/default/images/logo/easytoque.png" style="max-width:100%" alt="Easytoque" width=250></center>
                                <br/>
                                '.$body.'
                                <p>&mdash; Equipe Easytoque</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>	
        <br/><br/>
        </body>';

        return $html;
    }

    public function createMailWelcomeBody($name)
    {
        $body = '
        <p>Ol&aacute;, '.utf8_decode($name).'</p>
        <p style="color: green;">Muito obrigado pela confian&ccedil;a e o interesse em se tornar um parceiro <b>Easytoque</b>.</p>
        <p>Seu cadastro foi aprovado.</p>
        <p>Para lhe auxiliar compartilho as pr&oacute;ximas etapas:</p>
        <p><strong>Informa&ccedil;&otilde;es iniciais:</strong></p>
        <ul>
            <li>1. Ser&aacute; encaminhado um e-mail para <strong>cadastrar seus dados banc&aacute;rios</strong>.</li> 
            <li>2. Ap&oacute;s completar o cadastro que foi enviado no primeiro e-mail, enviaremos o <strong>contrato de presta&ccedil;&atilde;o de servi&ccedil;o.</strong><br>Esse contrato &eacute; assinado digitalmente, com validade legal.</li>
            <li>3. Ap&oacute;s a assinatura do Contrato, enviaremos o <strong>link da sua loja e os dados de acesso ao seu painel administrativo.</strong></li>
            <li>4. Por fim, vamos encaminhar alguns e-mails indicando nossos <strong>materiais de apoio</strong>, falando dos nossos produtos.</li>
        </ul>
        <p>Quaisquer d&uacute;vidas estaremos a disposi&ccedil;&atilde;o no WhatsApp (11) 94559-8672 ou atrav&eacute;s do e-mail parceiros@easytoque.com.br.</p>
        <p>
            Atenciosamente, 
        </p>';

        $mailHtml = $this->MailBody($body);
        return $mailHtml;
    }

    public function createMailDataBankUserBody($name, $link)
    {
        $body = '
        <p>Ol&aacute;, <b>'.utf8_decode($name).'</b>.</p><br>
        <p>
            Estamos entrando em contato pois precisamos que voc&ecirc; preencha o restante das informa&ccedil;&otilde;es em seu cadastro de parceiro Easytoque e assine o contrato.
        </p><br>
        <p>
            Para concluir seu cadastro, clique no link abaixo
        </p>
        <a href="'.$link.'">Concluir meu cadastro</a>';

        $mailHtml = $this->MailBody($body);
        return $mailHtml;
    }

    public function createMailCompleteRegisterBody($name, $corporate_name, $mail)
    {

        $body = '
        <p>Ol&aacute;,</p>
            <p>
                Informamos que o seguinte parceiro finalizou seu cadastro
            </p>
        <p>
        <ul>
            <li><b>Nome:</b> '.utf8_decode($name).'</li>
            <li><b>Empresa:</b> '.utf8_decode($corporate_name).'</li>
            <li><b>E-mail:</b>  '.$mail.'</li>
        </ul>
        </p>';

        $mailHtml = $this->MailBody($body);
        return $mailHtml;
    }

    public function createMailPartnerAddFinish($name, $email, $linkStore)
    {
        $body = '
            <p>Ol&aacute;, '.utf8_decode($name).'</p>
            <p>
                Concluimos seu cadastro como <b>Parceiro Easytoque!</b>
            </p>
            <p>
                Seguem os dados de acesso do seu painel e sua loja!
            </p>
            <ul>
                <li><b>Acesso ao painel:</b> <a href="https://parceiro.easytoque.com.br/login.php">https://parceiro.easytoque.com.br/login.php</a></li>
                <li><b>E-mail de acesso:</b> '.$email.'</li>
                <li><b>Senha de acesso:</b> <i>senha escolhida</i></li>
                <li><b>Endere&ccedil;o da loja:</b> <a href="'.$linkStore.'">'.$linkStore.'</a></li>
            </ul>';

        $mailHtml = $this->MailBody($body);
        return $mailHtml;
    }
}
