<?php

namespace Sardoj\Uccello\Seeds;

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
        $this->call(UserSeeder::class);
        $this->call(DomainSeeder::class);
        $this->call(HomeSeeder::class);
    }
}