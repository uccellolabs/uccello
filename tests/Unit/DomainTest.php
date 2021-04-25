<?php

namespace Uccello\Core\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Uccello\Core\Models\Domain;

class DomainTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected $loadEnvironmentVariables = true;

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSlugIsCreated()
    {
        $domain = Domain::create([
            'name' => 'Uccello'
        ]);

        $this->assertEquals($domain->slug, "uccello");
    }
}
