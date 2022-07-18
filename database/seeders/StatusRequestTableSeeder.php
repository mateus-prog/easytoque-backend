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
            ['id' => 1, 'name' => 'Aguardando', 'description' => 'Aguardando Confirmação dos Dados', 'color' => 'warning'],
            ['id' => 2, 'name' => 'Aprovado', 'description' => 'Em breve você receberá o pagamento', 'color' => 'info'],
            ['id' => 3, 'name' => 'Negado', 'description' => 'Solicitação de saque negada. Verifique o motivo', 'color' => 'danger'],
            ['id' => 4, 'name' => 'Pago', 'description' => 'Valor já transferido para a conta', 'color' => 'success'],
        ]);
    }
}
