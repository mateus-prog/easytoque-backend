<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\User\UserService;
use App\Services\User\UserStoreService;
use App\Services\User\UserBankService;
use App\Services\User\UserCorporateService;
use App\Services\Log\LogService;

class deletePartners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:partners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que deleta os parceiros que estão pendentes a 30 dias';

    protected $userService;
    protected $userStoreService;
    protected $userBankService;
    protected $userCorporateService;
    protected $logService;

    public function __construct(
        UserService $userService,
        UserStoreService $userStoreService,
        UserBankService $userBankService,
        UserCorporateService $userCorporateService,
        LogService $logService,
    )
    {
        parent::__construct();
        $this->userService = $userService;
        $this->userStoreService = $userStoreService;
        $this->userBankService = $userBankService;
        $this->userCorporateService = $userCorporateService;
        $this->logService = $logService;
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
                $date = explode(' ', $user->created_at)[0];
                $dateDelete = date('Y-m-d', strtotime('+30 days', strtotime($date)));
                if($dateDelete == date('Y-m-d'))
                {
                    $idStore = $this->userStoreService->getUserStoreByUser($user->id);
                    $this->userStoreService->destroy($idStore);
                    
                    $userBank = $this->userBankService->getUserBankEditByUser($user->id);
                    if(isset($userBank[0]))
                    {
                        $idBank = $userBank[0]['id'];
                        $this->userBankService->destroy($idBank);
                    }
                    
                    $userCorporate = $this->userCorporateService->getUserCorporateEditById($user->id);
                    if(isset($userCorporate[0]))
                    {
                        $idCorporate = $userCorporate[0]['id'];
                        $this->userCorporateService->destroy($idCorporate);
                    }

                    $this->logService->destroy($user->id);

                    $this->userService->destroy($user->id);    
                }
            }
        }
    }
}
