<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\HttpStatus;
use App\Traits\ApiResponser;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailerController extends Controller {

    use ApiResponser;

    // =============== [ Email ] ===================
    public function email() {
        return view("email");
    }

    // ========== [ Compose Email ] ================
    public function composeEmail(Request $request) {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {
            // Email server settings
            $mail->charSet = "UTF-8";
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            //$mail->Host = 'smtp.kinghost.net';             // smtp host
            //$mail->SMTPAuth = true;
            //$mail->Host = 'smtplw.com.br';             // smtp host
            //$mail->Username = 'rkservicos6';   // sender username
            //$mail->Password = 'dxNSlpPR5186';              // sender password
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // encryption - ssl/tls
            //$mail->Username   = 'parceiros@easytoque.com.br'; //SMTP username
			//$mail->Password   = 'tcolorparc$_';               //SMTP password
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // encryption - ssl/tls
            //$mail->Port = 587;   

            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'mateusguizelini01@gmail.com';
            $mail->Password   = 'M@te0109';                   
            //$mail->Username   = 'emailvucom@gmail.com';                     //SMTP username
			//$mail->Password   = 'hre260311';                               //SMTP password
			$mail->SMTPSecure = 'tls';                                    //Enable implicit TLS encryption
			$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
            //$mail->setFrom('contato@easytoque.com.br', 'Easytoque');
            $mail->setFrom('emailvucom@gmail.com', 'Easytoque');
            //$mail->setFrom($mail->Username, 'Easytoque'); ///email e nome do remetente
            //$mail->setFrom('semparar.atendimento@fulltimesolucoes.com.br', 'Easytoque'); ///email e nome do remetente
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