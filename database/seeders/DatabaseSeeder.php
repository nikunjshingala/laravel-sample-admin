<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

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
        DB::table('users')->insert(
            array(
                array(
                    'name' => 'Webs Optimization',
                    'email' => 'testadmin@websoptimization.com',
                    'password' => bcrypt("Testadmin"),
                ),
            )
        );
    }
}
