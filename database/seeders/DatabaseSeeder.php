<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            BanksTableSeeder::class,
            StatesTableSeeder::class,
            MenusTableSeeder::class,
            MenusRolesTableSeeder::class,
			TypeTransfersTableSeeder::class,
            StatusUserTableSeeder::class,
            StatusRequestTableSeeder::class,
            ActionsTableSeeder::class,
            LaravelEntrustSeeder::class,
        ]);
    }
}
