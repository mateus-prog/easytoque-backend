<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\TraitSeeder;
use App\Models\StatusRequest as Model;

class StatusRequestTableSeeder extends Seeder
{
    use TraitSeeder;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->smartySeeder(new Model, [
            ['id' => 1, 'name' => 'Aguardando', 'description' => 'Aguardando Confirmação dos Dados', 'color' => '#ffc107'],
            ['id' => 2, 'name' => 'Aprovado', 'description' => 'Em breve você receberá o pagamento', 'color' => '#2196f3'],
            ['id' => 3, 'name' => 'Negado', 'description' => 'Solicitação de saque negada. Verifique o motivo', 'color' => '#f44336'],
            ['id' => 4, 'name' => 'Pago', 'description' => 'Valor já transferido para a conta', 'color' => '#4caf50'],
        ]);
    }
}
