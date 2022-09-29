<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\User\UserService;

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

    public function __construct(
        UserService $userService
    )
    {
        parent::__construct();
        $this->userService = $userService;
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
                    $this->userService->destroy($user->id);    
                }
            }
        }
    }
}
