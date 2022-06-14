<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\TraitSeeder;
use App\Models\TypeTransfer as Model;

class TypeTransfersTableSeeder extends Seeder
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
            ['id' => 1, 'name' => 'Ted'],
            ['id' => 2, 'name' => 'Doc'],
            ['id' => 3, 'name' => 'Pix'],
        ]);
    }
}
