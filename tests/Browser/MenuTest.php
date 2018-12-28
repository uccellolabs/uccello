<?php

namespace Uccello\Core\Tests\Unit;

use Tests\DuskTestCase;
use Laravel\Dusk\Chrome;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Uccello\Core\Models\Module;

class MenuTest extends DuskTestCase
{
    public function testCurrentModuleSelected()
    {
        $user = User::first();
        $homeModule = Module::where('name', 'home')->first();

        $this->browse(function ($browser) use ($user, $homeModule) {
            $browser->visit('/login')
                    ->type('identity', $user->email)
                    ->type('password', 'admin')
                    ->press('Login')
                    ->assertPathIs('/default/home')
                    ->assertSeeLink('Home');


            $linkClass = $browser->attribute('#leftsidebar .menu li.active > a', 'class');

            // Module link has toggled class
            $this->assertContains('toggled', $linkClass);
        });
    }
}
