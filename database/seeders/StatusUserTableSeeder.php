<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\TraitSeeder;
use App\Models\StatusUser as Model;

class StatusUserTableSeeder extends Seeder
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
            ['id' => 1, 'name' => 'Ativo', 'status' => 'success'],
            ['id' => 2, 'name' => 'Bloqueado', 'status' => 'danger'],
            ['id' => 3, 'name' => 'Pendente', 'status' => 'warning'],
        ]);
    }
}
