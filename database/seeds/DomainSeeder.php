<?php

namespace Sardoj\Uccello\Seeds;

use Illuminate\Database\Seeder;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createData();
        $module = $this->createModule();
    }

    protected function createData()
    {
        Domain::create([
            'name' => 'Default',
            'description' => null,
            'parent_id' => null
        ]);
    }

    protected function createModule()
    {
        Module::create([
            'name' => 'domain',
            'icon' => 'domain',
            'entity_class' => 'Domain',
            'is_for_admin' => true
    ]);
    }
}
