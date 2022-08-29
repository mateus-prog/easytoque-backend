<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class activeUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'active:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que copia os uploads';

    
    public function __construct(){}

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        shell_exec('docker cp laravel:/var/www/public/logo/logo_default.png /home/larahthamires2/easytoque-backend/public/logos/aqui2.png');  
    }
}
