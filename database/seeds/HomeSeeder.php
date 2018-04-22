<?php

namespace Sardoj\Uccello\Seeds;

use Illuminate\Database\Seeder;
use Sardoj\Uccello\Module;

class HomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module = $this->createModule();
    }

    protected function createModule()
    {
        Module::create([
            'name' => 'home',
            'icon' => 'home',
            'entity_class' => null,
            'is_for_admin' => false
        ]);
    }
}
