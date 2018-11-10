<?php

namespace Uccello\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePackageCommand extends Command
{
    /**
     * The structure of the package.
     *
     * @var string
     */
    protected $package;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:package
                        {name? : Package name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new package';

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
        // Ask package information
        $this->askPackageInformation();

        // Make package
        $packageMade = $this->makePackage();

        if ($packageMade) {
            // Add local repository
            $this->addLocalRepository();

            $this->info('Package created!');
            $this->info('You can install with <comment>composer require ' . $this->package->name . '</comment>');
        } else {
            $this->error('Package not made');
        }
    }

    /**
     * Ask the user information to make the skeleton of the package.
     *
     * @return void
     */
    protected function askPackageInformation()
    {
        // Get package name from argument or ask it
        if ($this->argument('name')) {
            $packageName = $this->argument('name');
        } else {
            $packageName = $this->ask('<info>What is the package name? (e.g. vendor/package)</info>');
        }

        // The kebab_case function converts the given string to kebab-case
        $packageName = kebab_case($packageName);

        // If module name is not defined, ask again
        if (!$packageName) {
            $this->error('You must specify a package name');
            return $this->createPackage();
        }
        // Check if package name is only with alphanumeric characters
        elseif (!preg_match('`^[a-z0-9-]+/[a-z0-9-]+$`', $packageName)) {
            $this->error('You must use only alphanumeric characters');
            return $this->createPackage();
        }

        $packageData = explode('/', $packageName);

        // Create an empty object
        $this->package = new \stdClass();

        // Name
        $this->package->name = $packageName;

        // Vendor
        $this->package->vendor = $packageData[0];

        // Package
        $this->package->package = $packageData[1];

        // Version
        $this->package->version = $this->ask('Version', '1.0.0');

        // Description
        $this->package->description = $this->ask('Description');

        // Author name
        $this->package->authorName = $this->ask('Author name');

        // Author email
        $this->package->authorEmail = $this->ask('Author email');

        // Namespace
        $defaultNamespace = studly_case($this->package->vendor) . '\\' . studly_case($this->package->package); // The studly_case function converts the given string to StudlyCase
        $this->package->namespace = $this->ask('Namespace', $defaultNamespace);

        // Module
        $this->package->module = $this->ask('Module name', $this->package->package);

        // Table
        $this->package->table = $this->ask('Table name', str_replace('-', '_', str_plural($this->package->module)));

        // Display module data
        $this->table(
            [
                'Name', 'Version', 'Description', 'Author', 'Email', 'Namespace', 'Module', 'Table'
            ],
            [
                [$this->package->name, $this->package->version, $this->package->description, $this->package->authorName, $this->package->authorEmail, $this->package->namespace, $this->package->module, $this->package->table]
            ]
        );

        // If information is not correct, restart step
        $isCorrect = $this->confirm('Is this information correct?', true);
        if (!$isCorrect) {
            return $this->createPackage();
        }
    }

    /**
     * Use module-skeleton to make a new package
     *
     * @return void
     */
    protected function makePackage()
    {
        // Make directory
        $packageMade = $this->makeDirectory();

        if ($packageMade) {
            // Generate composer.json
            $this->generateComposerJsonFile();

            // Generate webpack.mix.js
            $this->generateWebpackMixFile();

            // Generate app/Providers/AppServiceProvider.php
            $this->generateAppServiceProviderFile();

            // Generate model file
            $this->generateModelFile();

            // Generate lang file
            $this->generateLangFile();

            // Generate migration file
            $this->generateMigrationFile();

            // Generate overrided views path
            $this->generateOverridedViewsPath();
        }

        return $packageMade;
    }

    /**
     * Make package directory and copy package-skeleton files
     *
     * @return boolean
     */
    protected function makeDirectory(): bool
    {
        $packagePath = 'packages/' . $this->package->vendor . '/' . $this->package->package;

        if (File::exists($packagePath)) {
            $this->error('This package already exists');

            return false;
        }

        // Save path
        $this->package->path = $packagePath;

        // Make directory
        File::makeDirectory($packagePath, 0755, true);

        // Copy files from package-skeleton
        File::copyDirectory('vendor/uccello/package-skeleton', $packagePath);

        return true;
    }

    /**
     * Generate composer.json file
     *
     * @return void
     */
    protected function generateComposerJsonFile()
    {
        $filePath = $this->package->path . '/composer.json';

        // Get file content
        $content = file_get_contents($filePath);

        // Formatted namespace
        $namespace = str_replace('\\', '\\\\', $this->package->namespace);

        // Replace data
        $content = str_replace(
            [
                'uccello/package-skeleton',
                'Uccello\\\\PackageSkeleton',
                '1.0.0',
                'Package skeleton for Uccello',
                'Jonathan SARDO',
                'jonathan@uccellolabs.com',
                '"laravel": {}'
            ],
            [
                $this->package->name,
                $namespace,
                $this->package->version,
                $this->package->description,
                $this->package->authorName,
                $this->package->authorEmail,
                '"laravel": {' . "\n" .
                '            "providers": [' . "\n" .
                '                "' . $namespace . '\\\\Providers\\\\AppServiceProvider"' . "\n" .
                '            ]' . "\n" .
                '        }'
            ],
            $content);

        // Save data
        file_put_contents($filePath, $content);
    }

    /**
     * Generate webpack.mix.js file
     *
     * @return void
     */
    protected function generateWebpackMixFile()
    {
        $filePath = $this->package->path . '/webpack.mix.js';

        // Get file content
        $content = file_get_contents($filePath);

        // Replace data
        $content = str_replace(
            [
                'uccello/package-skeleton',
            ],
            [
                $this->package->name,
            ],
            $content);

        // Save data
        file_put_contents($filePath, $content);
    }

    /**
     * Generate app/Providers/AppServiceProvider.php file
     *
     * @return void
     */
    protected function generateAppServiceProviderFile()
    {
        $filePath = $this->package->path . '/app/Providers/AppServiceProvider.php';

        // Get file content
        $content = file_get_contents($filePath);

        // Replace data
        $content = str_replace(
            [
                'uccello/package-skeleton',
                'Uccello\\PackageSkeleton',
                'package-skeleton',
            ],
            [
                $this->package->name,
                $this->package->namespace,
                $this->package->package,
            ],
            $content);

        // Save data
        file_put_contents($filePath, $content);
    }

    /**
     * Generate module model file
     *
     * @return void
     */
    protected function generateModelFile()
    {
        $basePath = $this->package->path . '/app/Models';

        // Class name
        $moduleClassName = studly_case($this->package->module);

        // New file path
        $filePath = $basePath . '/' . $moduleClassName . '.php';

        // Rename file
        File::moveDirectory($basePath . '/ModuleSkeleton.php', $filePath);

        // Get file content
        $content = file_get_contents($filePath);

        // Replace data
        $content = str_replace(
            [
                'Uccello\\PackageSkeleton',
                'ModuleSkeleton',
                'module_skeletons',
            ],
            [
                $this->package->namespace,
                $moduleClassName,
                $this->package->table,
            ],
            $content);

        // Save data
        file_put_contents($filePath, $content);
    }

    /**
     * Generate module migration file
     *
     * @return void
     */
    protected function generateMigrationFile()
    {
        $basePath = $this->package->path . '/database/migrations';

        // New file path
        $filePath = $basePath . '/' . date('Y_m_d'). '_000001_create_' . str_replace('-', '_', $this->package->module) . '_module.php';

        // Rename file
        File::moveDirectory($basePath . '/2018_10_01_000001_create_module_skeleton_module.stub', $filePath);

        // Class name
        $moduleClassName = studly_case($this->package->module);

        // Get file content
        $content = file_get_contents($filePath);

        // Replace data
        $content = str_replace(
            [
                'CreateModuleSkeletonModule',
                'package-skeleton',
                'module-skeleton',
                'module_skeletons',
                'Uccello\\PackageSkeleton\\Models\\ModuleSkeleton',
            ],
            [
                'Create' . $moduleClassName . 'Module',
                $this->package->package,
                $this->package->module,
                $this->package->table,
                $this->package->namespace . '\\Models\\' . $moduleClassName,
            ],
            $content);

        // Save data
        file_put_contents($filePath, $content);
    }

    /**
     * Generate module lang file
     *
     * @return void
     */
    protected function generateLangFile()
    {
        $basePath = $this->package->path . '/resources/lang/en';

        // New file path
        $filePath = $basePath . '/' . $this->package->module . '.php';

        // Rename file
        File::moveDirectory($basePath . '/module-skeleton.php', $filePath);

        // Get file content
        $content = file_get_contents($filePath);

        // Replace data
        $content = str_replace(
            [
                'module-skeleton',
                'Module Skeletons',
                'Module Skeleton',
            ],
            [
                $this->package->module,
                str_plural(studly_case($this->package->module)),
                studly_case($this->package->module),
            ],
            $content);

        // Save data
        file_put_contents($filePath, $content);
    }

    /**
     * Generate base path to override views if necessary
     *
     * @return void
     */
    protected function generateOverridedViewsPath()
    {
        $basePath = $this->package->path . '/resources/views/modules';

        // Rename directory
        File::moveDirectory($basePath . '/module-skeleton', $basePath . '/' . $this->package->package);
    }

    /**
     * Add local repository to root composer.json
     *
     * @return void
     */
    protected function addLocalRepository()
    {
        $content = file_get_contents('composer.json');

        $composerData = json_decode($content);

        // Add repository if does not exist
        if (!$composerData->repositories) {
            $composerData->repositories = [];
        }

        $repository = [
            'type' => 'path',
            'url' => './' . $this->package->path
        ];

        $composerData->repositories[] = $repository;

        // Save file
        file_put_contents('composer.json', json_encode($composerData, JSON_PRETTY_PRINT));
    }
}
