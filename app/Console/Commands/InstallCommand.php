<?php

namespace Uccello\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uccello:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Uccello';

    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $views = [
        'auth/login.stub' => 'auth/login.blade.php',
        'auth/register.stub' => 'auth/register.blade.php',
        'auth/passwords/email.stub' => 'auth/passwords/email.blade.php',
        'auth/passwords/reset.stub' => 'auth/passwords/reset.blade.php',
        'layouts/uccello.stub' => 'layouts/uccello.blade.php',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $this->comment('Executing make:auth...');
        // $this->callSilent('make:auth', ['--force' => true]);
        // $this->callSilent('ui:auth', ['--force' => true]);

        $this->comment('Publishing Uccello Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'uccello-assets']);

        $this->comment('Publishing Uccello Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'uccello-config']);

        // Compatibility with new Laravel version
        $this->comment('Copying User Model...');
        copy(
            __DIR__.'/stubs/make/app/User.stub',
            app_path('Models/User.php')
        );

        $this->comment('Copying UccelloModel Model...');
        copy(
            __DIR__.'/stubs/make/app/UccelloModel.stub',
            app_path('Models/UccelloModel.php')
        );

        $this->comment('Copying Login Controller...');
        copy(
            __DIR__.'/stubs/make/app/Http/Controllers/Auth/LoginController.stub',
            app_path('Http/Controllers/Auth/LoginController.php')
        );

        $this->comment('Copying Views...');
        foreach ($this->views as $key => $value) {
            copy(
                __DIR__.'/stubs/make/views/'.$key,
                resource_path('views/'.$value)
            );
        }

        $this->info(trans('Uccello scaffolding installed successfully'));
    }
}
