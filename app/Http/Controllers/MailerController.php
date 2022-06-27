<?php

namespace App\Http\Controllers;

//use App\Http\HttpStatus;
use App\Traits\ApiResponser;
use App\Services\Mail\MailService;

class MailerController extends Controller {

    use ApiResponser;

    protected $mailService;

    public function __construct(
        MailService $mailService
    )
    {
        $this->mailService = $mailService;
    }

    // =============== [ Email ] ===================
    public function email() {
        return view("email");
    }

    // ========== [ Compose Email ] ================
    public function composeEmail($mailRecipient, $mailSubject, $mailBody, $userId) {
        $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $userId);
    }
}