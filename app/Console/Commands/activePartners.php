<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\User\UserService;
use App\Services\ClickSign\ClickSignService;
use App\Services\Mail\MailService;

class activePartners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'active:partners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que ativa os parceiros que assinaram o contrato da clicksign e que se encontram pendentes';

    protected $userService;
    protected $clickSignService;
    protected $mailService;

    public function __construct(
        UserService $userService,
        ClickSignService $clickSignService,
        MailService $mailService
    )
    {
        parent::__construct();
        $this->userService = $userService;
        $this->clickSignService = $clickSignService;
        $this->mailService = $mailService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = $this->userService->all();
        foreach($users as $user)
        {
            //verifica se o usuario é Parceiro e se o status esta pendente
            if($user->role_id == 4 && $user->status_user_id == 3)
            {
                $this->clickSignService->viewDocument($user->id);
                
                $user = $this->userService->findById($user->id);
                if($user->status_user_id == 1)
                {   
                    //sendMail complete register user
                    $mailRecipient = $user->email;
                    
                    $linkStore = 'https://loja.easytoque.com.br/?___store=loja_'.$user->id;

                    //mail welcome
                    $mailBody = $this->mailService->createMailPartnerAddFinish($user->first_name, $mailRecipient, $linkStore);
                    $mailSubject = "[Parceiros Easytoque] - Seus dados de acesso e sua loja!";

                    $messageLog = "Dados da loja";
                    
                    $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $user->id, $messageLog);
                }
            }
        }
    }
}
