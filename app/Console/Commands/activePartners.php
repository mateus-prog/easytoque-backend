<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\User\UserService;
use App\Services\ClickSign\ClickSignService;

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

    public function __construct(
        UserService $userService,
        ClickSignService $clickSignService
    )
    {
        parent::__construct();
        $this->userService = $userService;
        $this->clickSignService = $clickSignService;
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
            }
        }
    }
}
