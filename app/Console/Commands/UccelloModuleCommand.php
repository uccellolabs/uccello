<?php

namespace Uccello\Core\Console\Commands;

use Illuminate\Console\Command;
use Uccello\Core\Models\Designer;
use Uccello\Core\Models\DesignedModule;

class UccelloModuleCommand extends Command
{
    /**
     * The structure of the module.
     *
     * @var string
     */
    protected $module;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uccello:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or edit a module compatible with Uccello';

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
        $this->checkForDesignedModules();
    }

    /**
     * Check if modules are being designed and
     * ask user to choose a module to continue
     * or select another action to perform.
     *
     * @return void
     */
    protected function checkForDesignedModules()
    {
        // Get all designed modules
        $designedModules = DesignedModule::all();

        // If designed modules are found display several options
        if (count($designedModules) > 0) {
            $choices = [];
            $modules = [];

            $createEditModuleChoice = 'Create or edit another module';
            $removeDesignedModuleChoice = 'Remove a designed module from the list';

            foreach ($designedModules as $module) {
                // Get module name
                $name = $module->name;

                // Store module data
                $modules[$name] = $module->data;

                // Add module name to choices list
                $choices[] = $name;
            }

            // Add actions to choices list
            $availableChoices = array_merge($choices, [
                $createEditModuleChoice,
                $removeDesignedModuleChoice
            ]);

            // Ask the action to perform
            $choice = $this->choice('Some modules are being designed. Choose a module to continue or select an action to perform', $availableChoices);

            // Create or Edit another module
            if ($choice === $createEditModuleChoice) {
                $this->chooseAction(0, true);
            }
            // Remove designed module from the list
            elseif ($choice === $removeDesignedModuleChoice) {
                // Ask the user what designed module he wants to remove
                $designedModuleToDelete = $this->choice('What designed module do you want to remove from the list?', $choices);

                // Delete designed module
                DesignedModule::where('name', $designedModuleToDelete)->delete();
                $this->info('<comment>' . $designedModuleToDelete . '</comment> was deleted from the list');

                // Display the list again
                $this->checkForDesignedModules();
            }
            // Select module and continue
            else {
                $this->module = $modules[$choice];
                $this->line('<info>Selected module:</info> '.$choice);

                // Continue
                $this->chooseAction(1);
            }
        }
        // No designed module available, simply continue
        else {
            $this->chooseAction(0, true);
        }
    }

    /**
     * Ask the user what action he wants to perform
     *
     * @param int|null $defaultChoiceIndex
     * @return void
     */
    protected function chooseAction($defaultChoiceIndex = null, $canCreateModule = false)
    {
        // Default choices
        $choices = [
            'Create a new module',
            'Add a block',
            'Add a field',
            'Generate a migration',
            'Exit'
        ];

        // Remove first choice if necessary
        $availableChoices = $choices;
        if (!$canCreateModule) {
            unset($availableChoices[0]);
        }

        $choice = $this->choice('What action do you want to perform?', $availableChoices, $defaultChoiceIndex);

        switch ($choice) {
            // Create a new module
            case $choices[0]:
                $this->createModule();
                break;

            // Add a block
            case $choices[1]:
                $this->createBlock();
                break;

            // Add a field
            case $choices[2]:
                $this->createField();
                break;

            // Generate a migration
            case $choices[3]:
                $this->createMigration();
                break;

            // Exit
            case $choices[4]:
                // Do nothing
                break;
        }
    }

    /**
     * Ask the user information to make the skeleton of the module.
     *
     * @return void
     */
    protected function createModule()
    {
        $moduleName = $this->ask('<info>What is the module name? (e.g. book_type)</info>');

        // The snake_case function converts the given string to snake_case
        $moduleName = snake_case($moduleName);

        // If module name is not defined, ask again
        if (!$moduleName) {
            $this->error('You must specify a module name');
            return $this->createModule();
        }
        // Check if module name is only with alphanumeric characters
        elseif (!preg_match('`^[a-z0-9_]+$`', $moduleName)) {
            $this->error('You must use only alphanumeric characters');
            return $this->createModule();
        }

        // Create an empty object
        $this->module = new \stdClass();

        // Name
        $this->module->name = snake_case($moduleName);

        // Model. The studly_case function converts the given string to StudlyCase
        $this->module->model = 'App\\' . studly_case($moduleName);

        // Icon
        $this->module->icon = $this->ask('Material icon name (e.g. book)');

        // Is for administration
        $this->module->isForAdmin = $this->confirm('Is this module for administration panel?');

        // Link
        $this->module->link = $this->ask('Main page name', 'list');

        // Display module data
        $this->table(
            [
                'Name', 'Model', 'Icon', 'For admin', 'Main page'
            ],
            [
                [$this->module->name, $this->module->model, $this->module->icon, ($this->module->isForAdmin ? 'Yes' : 'No'), $this->module->link]
            ]
        );

        // If information is not correct, restart step
        $isCorrect = $this->confirm('Is this information correct?', true);
        if (!$isCorrect) {
            return $this->createModule();
        }

        // Save module structure
        $this->saveModuleStructure();

        // Ask user to choose another action (Default: Add a block)
        $this->chooseAction(1);
    }

    /**
     * Create or update a line into designed_modules table
     *
     * @return void
     */
    protected function saveModuleStructure()
    {
        $designedModule = DesignedModule::updateOrCreate(
            ['name' => $this->module->name],
            ['data' => $this->module]
        );
    }
}
