<?php

namespace Uccello\Core\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Uccello\Core\Models\Workspace;

class WorkspaceTest extends \Orchestra\Testbench\TestCase
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
        $workspace = Workspace::create([
            'name' => 'Uccello'
        ]);

        $this->assertEquals($workspace->slug, "uccello");
    }
}
