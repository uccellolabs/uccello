<?php

namespace Uccello\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * AdminLTE Make Command
 */
class UccelloMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:uccello {--views : Only scaffold the views}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold Uccelo Template';

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
        'errors/403.stub' => 'errors/403.blade.php',
        'errors/404.stub' => 'errors/404.blade.php',
        'errors/500.stub' => 'errors/500.blade.php',
        'layouts/app.stub' => 'layouts/app.blade.php',
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
        $this->info('Execute make:auth');
        $this->call('make:auth', [ '--force' => true, '--views' => $this->option('views') ]);

        $this->info('Start Uccello scaffolding');

        $this->info('Copying user model...');
        copy(
            __DIR__.'/stubs/make/app/User.stub',
            app_path('User.php')
        );

        $this->info('Copying login controller...');
        copy(
            __DIR__ . '/stubs/make/app/Http/Controllers/Auth/LoginController.stub',
            app_path('Http/Controllers/Auth/LoginController.php')
        );

        $this->info('Copying views...');
        $this->createDirectories();
        foreach ($this->views as $key => $value) {
            copy(
                __DIR__.'/stubs/make/views/'.$key,
                resource_path('views/'.$value)
            );
        }

        // $this->info('Copying Uitype Selector...');
        // copy(
        //     __DIR__.'/stubs/make/assets/js/uitype-selector.stub',
        //     resource_path('assets/js/uitype-selector.js')
        // );

        $this->info('Publishing assets...');
        Artisan::call('vendor:publish', [
            '--tag' => 'assets',
            '--provider' => 'Uccello\Core\Providers\AppServiceProvider',
            '--force' => 1
        ]);

        Artisan::call('vendor:publish', [
            '--provider' => 'Tymon\JWTAuth\Providers\LaravelServiceProvider'
        ]);

        $this->info('Generating routes with laroute...');
        Artisan::call('laroute:generate');


        // Generate JWT Secret if it does not exist yet (else there is an error)
        if (empty(env('JWT_SECRET'))) {
            $this->info('Generating jwt secret...');
            Artisan::call('jwt:secret');
        }

        $this->info('Uccello scaffolding generated successfully.');
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (! is_dir(resource_path('views/errors'))) {
            mkdir(resource_path('views/errors'), 0755, true);
        }
    }

    /**
     * Copy a file, or recursively copy a folder and its contents
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.0.1
     * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
     * @param       string   $source    Source path
     * @param       string   $dest      Destination path
     * @param       int      $permissions New folder creation permissions
     * @return      bool     Returns true on success, false on failure
     */
    private function xcopy($source, $dest, $permissions = 0755)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            $this->xcopy("$source/$entry", "$dest/$entry", $permissions);
        }

        // Clean up
        $dir->close();
        return true;
    }
}