<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\TraitSeeder;
use App\Models\MenuRole as Model;

class MenusRolesTableSeeder extends Seeder
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
            ['id' => 1, 'role_id' => '4', 'menu_id' => '1'],
            ['id' => 2, 'role_id' => '4', 'menu_id' => '2'],
            ['id' => 3, 'role_id' => '4', 'menu_id' => '3'],
            ['id' => 4, 'role_id' => '4', 'menu_id' => '4'],
            ['id' => 5, 'role_id' => '4', 'menu_id' => '5'],
            //['id' => 6, 'role_id' => '4', 'menu_id' => '6'],
            ['id' => 7, 'role_id' => '1', 'menu_id' => '1'],
            ['id' => 8, 'role_id' => '1', 'menu_id' => '7'],
            ['id' => 9, 'role_id' => '1', 'menu_id' => '8'],
            ['id' => 10, 'role_id' => '1', 'menu_id' => '9'],
            ['id' => 11, 'role_id' => '1', 'menu_id' => '10'],
            ['id' => 12, 'role_id' => '2', 'menu_id' => '8'],
            ['id' => 13, 'role_id' => '2', 'menu_id' => '10'],
            ['id' => 14, 'role_id' => '3', 'menu_id' => '7'],
            ['id' => 15, 'role_id' => '3', 'menu_id' => '8'],
        ]);
    }
}
