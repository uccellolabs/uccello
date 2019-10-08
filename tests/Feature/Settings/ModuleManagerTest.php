<?php

namespace Uccello\Core\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Domain;

class ModuleManagerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Acting as admin
     *
     * @return void
     */
    protected function actingAsAdmin()
    {
        $user = factory(\Uccello\Core\Models\User::class)->make();
        $user->is_admin = true;
        $user->save();

        Artisan::call('cache:clear');

        $this->actingAs($user);
    }

    protected function getSettingsModule()
    {
       return Module::where('name', 'settings')->first();
    }

    public function testModuleActivationCanBeChanged()
    {
        $this->actingAsAdmin();

        $domain = Domain::first();

        // Create fake module
        $module = factory(\Uccello\Core\Models\Module::class)->create();
        $moduleName = $module->name;

        $url = ucroute('uccello.settings.module.activation', $domain);

        // Deactivate module
        $response = $this->post($url, [
            'src_module' => $moduleName,
            'active' => 0
        ]);

        // Test response content
        $response->assertJson([ 'success' => true, 'message' => uctrans('message.module_deactivated', $this->getSettingsModule()) ]);

        // Activation status wash changed
        $module = $module->refresh();
        $this->assertFalse($module->isActiveOnDomain($domain));

        // Activate module
        $response = $this->json('POST', $url, [
            'src_module' => $moduleName,
            'active' => 1
        ]);

        // Test response content
        $response->assertJson([ 'success' => true, 'message' => uctrans('message.module_activated', $this->getSettingsModule()) ]);

        // Test if activation status was changed
        $module = $module->refresh();
        $this->assertTrue($module->isActiveOnDomain($domain));
    }

    public function testMandatoryModuleActivationCannotBeChanged()
    {
        $this->actingAsAdmin();

        $domain = Domain::first();

        $moduleName = 'settings';
        $module = Module::where('name', $moduleName)->first();

        $this->assertTrue($module->isMandatory());

        $url = ucroute('uccello.settings.module.activation', $domain);

        // Try to deactivate module
        $response = $this->json('POST', $url, [
            'src_module' => $moduleName,
            'active' => 0
        ]);

        // Test response content
        $response->assertJson([ 'success' => false, 'error' => uctrans('error.module_is_mandatory', $this->getSettingsModule()) ]);

        // Test if activation status was not changed
        $module = $module->refresh();
        $this->assertTrue($module->isActiveOnDomain($domain));
    }

    public function testModuleNotDefined()
    {
        $this->actingAsAdmin();

        $domain = Domain::first();

        $url = ucroute('uccello.settings.module.activation', $domain);

        // Try to deactivate module
        $response = $this->json('POST', $url, [
            'active' => 0
        ]);

        // Test response content
        $response->assertJson([ 'success' => false, 'error' => uctrans('error.module_not_defined', $this->getSettingsModule()) ]);
    }
}
