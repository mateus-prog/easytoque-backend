<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\User\UserService;
use App\Services\User\UserStoreService;
use App\Services\ClickSign\ClickSignService;
use App\Services\Mail\MailService;
use App\Services\Store\StoreService;

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
    protected $userStoreService;
    protected $clickSignService;
    protected $mailService;
    protected $storeService;

    public function __construct(
        UserService $userService,
        UserStoreService $userStoreService,
        ClickSignService $clickSignService,
        MailService $mailService,
        StoreService $storeService,
    )
    {
        parent::__construct();
        $this->userService = $userService;
        $this->userStoreService = $userStoreService;
        $this->clickSignService = $clickSignService;
        $this->mailService = $mailService;
        $this->storeService = $storeService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clientIdMax = 0;
        $stores = $this->storeService->all();
        foreach($stores as $store){
            $idStore = $store->id;
            $clientIdMax = $store->client_id;
        }

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
                    $clientIdMax++;

                    //incluir a loja no banco de dados
                    $response = $this->storeService->createStoreMagento($user->id, $clientIdMax);
                    $storeId = $response['loja_id'];
                    
                    $id = $this->userStoreService->getUserStoreByUser($user->id);
                    $data = array(
                        'store_id' => $storeId,
                        'client_id' => $clientIdMax,
                    );

                    $this->userStoreService->update($id, $data);
                    
                    $store = $this->userStoreService->findById(strval($id));

                    //sendMail complete register user
                    $mailRecipient = $user->email;
                    
                    $linkStore = 'https://loja.easytoque.com.br/?___store=loja_'.$store->client_id;
                    
                    //mail welcome
                    $mailBody = $this->mailService->createMailPartnerAddFinish($user->first_name, $mailRecipient, $linkStore);
                    $mailSubject = "[Parceiros Easytoque] - Seus dados de acesso e sua loja!";

                    $messageLog = "Dados da loja";
                    
                    $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $user->id, $messageLog);
                }
            }
        }

        $this->storeService->update($idStore, ['client_id' => $clientIdMax]);
    }
}
