<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\TraitSeeder;
use App\Models\Menu as Model;

class MenusTableSeeder extends Seeder
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
            ['id' => 1, 'name' => 'Dashboard', 'icon' => 'fas fa-home', 'url' => ''],
            ['id' => 2, 'name' => 'Vendas', 'icon' => 'fas fa-dollar-sign', 'url' => '/sales'],
            ['id' => 3, 'name' => 'Comissões', 'icon' => 'fas fa-hand-holding-usd', 'url' => '/commissions'],
            ['id' => 4, 'name' => 'Alterar Logo', 'icon' => 'fas fa-image', 'url' => '/addLogo'],
            ['id' => 5, 'name' => 'Materiais Divulgação', 'icon' => 'fas fa-bullhorn', 'url' => 'https://www.easytoque.com.br/materiais-didaticos/'],
            ['id' => 6, 'name' => 'Contrato', 'icon' => 'fas fa-file-contract', 'url' => ''],
            ['id' => 7, 'name' => 'Solicitações', 'icon' => 'fas fa-hand-holding-usd', 'url' => '/requests'],
            ['id' => 8, 'name' => 'Parceiros', 'icon' => 'fas fa-users', 'url' => '/partners'],
            ['id' => 9, 'name' => 'Logs', 'icon' => 'fas fa-clock', 'url' => '/logs'],
            ['id' => 10, 'name' => 'Administradores', 'icon' => 'fas fa-user-tie', 'url' => '/administrators'],
        ]);
    }
}
