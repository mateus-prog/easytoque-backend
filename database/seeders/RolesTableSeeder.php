<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Traits\TraitSeeder;

class RolesTableSeeder extends Seeder
{
    use TraitSeeder;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')
            ->where('id', 1)
            ->update([
                "display_name" => 'Administrador'
        ]);

        DB::table('roles')
            ->where('id', 2)
            ->update([
                "display_name" => 'Colaborador'
        ]);

        DB::table('roles')
            ->where('id', 3)
            ->update([
                "display_name" => 'Financeiro'
        ]);

        DB::table('roles')
            ->where('id', 4)
            ->update([
                "display_name" => 'Parceiro'
        ]);
    }
}
