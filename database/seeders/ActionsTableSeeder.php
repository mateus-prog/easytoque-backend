<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\TraitSeeder;
use App\Models\Action as Model;

class ActionsTableSeeder extends Seeder
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
            ['id' => 1, 'name' => 'Adicionar', 'display_name' => 'Adicionou'],
            ['id' => 2, 'name' => 'Editar', 'display_name' => 'Editou'],
            ['id' => 3, 'name' => 'Excluir', 'display_name' => 'Excluiu'],
            ['id' => 4, 'name' => 'Bloquear', 'display_name' => 'Bloqueou'],
            ['id' => 5, 'name' => 'Ativar', 'display_name' => 'Ativou'],
            ['id' => 6, 'name' => 'Enviar', 'display_name' => 'Enviou'],
            ['id' => 7, 'name' => 'Entrar', 'display_name' => 'Entrou'],
            ['id' => 8, 'name' => 'Sair', 'display_name' => 'Saiu'],
        ]);
    }
}
