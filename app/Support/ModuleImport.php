<?php

namespace Uccello\Core\Support;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Link;

class ModuleImport
{
    /**
     * The structure of the module.
     *
     * @var \StdClass
     */
    protected $structure;

    /**
     * Module directory file path
     *
     * @var string
     */
    protected $filePath;

    /**
     * Filesystem implementation
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Command implementation to be able to display message in the console
     *
     * @var \Illuminate\Console\Command|Uccello\ModuleDesigner\Console\Commands\MakeModuleCommand
     */
    protected $command;

    /**
     * Constructor
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    /**
     * Undocumented function
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Illuminate\Console\Command|Uccello\ModuleDesigner\Console\Commands\MakeModuleCommand|null $output
     */
    public function __construct(Filesystem $files, $command)
    {
        $this->files = $files;

        $this->command = $command;
    }

    /**
     * Generate all files and database structure to install the new module
     *
     * @param \StdClass $module
     * @return void
     */
    public function install(\StdClass $module)
    {
        $this->structure = $module;

        // Initialize module file path
        $this->initFilePath();

        // Create module structure
        $module = $this->createModule();

        // Activate module on all domains
        $this->activateModuleOnDomains($module);

        // Create module table
        $this->createTable($module);

        // Create default filter
        $this->createDefaultFilter($module);

        // Create related lists
        $this->createRelatedLists($module);

        // Create links
        $this->createLinks($module);

        // Create language files
        $this->createLanguageFiles($module);

        // Create model file
        $this->createModelFile($module);

        //TODO: Delete old elements (tab, block, field, ....)
    }

    protected function initFilePath()
    {
        $this->filePath = '';

        if (!is_null($this->structure->package)) {
            // Extract vendor and package names
            $packageParts = explode('/', $this->structure->package);

            if (count($packageParts) === 2) {
                $this->filePath = 'packages/' . $packageParts[0] . '/' . $packageParts[1] . '/';
            }
        }
    }

    /**
     * Create module structure in the database
     *
     * @return void
     */
    protected function createModule()
    {
        $moduleData = [];

        // Package
        if (!is_null($this->structure->package)) {
            $packageParts = explode('/', $this->structure->package); // vendor/package

            // Keep only package name
            $moduleData['package'] = $packageParts[count($packageParts) - 1];
        }

        // Is for admin
        if ($this->structure->isForAdmin === true) {
            $moduleData['admin'] = true;
        }

        // Default route
        if ($this->structure->route !== 'uccello.list') {
            $moduleData['route'] = $this->structure->route;
        }

        // Create new module
        $module = Module::firstOrNew([
            'name' => $this->structure->name,
        ]);

        // Check if the module already exists
        $alreadyExits = !empty($module->id);

        $module->icon = $this->structure->icon;
        $module->model_class = $this->structure->model;
        $module->data = !empty($moduleData) ? $moduleData : null;
        $module->save();

        // Create tabs
        if (isset($this->structure->tabs)) {
            foreach ($this->structure->tabs as $_tab) {

                $tab = Tab::firstOrNew([
                    'label' => $_tab->label,
                    'module_id' => $module->id,
                ]);
                $tab->icon = $_tab->icon;
                $tab->sequence = $_tab->sequence;
                $tab->save();

                // Create blocks
                foreach ($_tab->blocks as $_block) {
                    $block = Block::firstOrNew([
                        'label' => $_block->label,
                        'module_id' => $module->id,
                    ]);
                    $block->icon = $_block->icon;
                    $block->data = $_block->data;
                    $block->sequence = $_block->sequence;
                    $block->tab_id = $tab->id;
                    $block->save();

                    // Create fields
                    foreach ($_block->fields as $_field) {
                        $field = Field::firstOrNew([
                            'name' => $_field->name,
                            'module_id' => $module->id,
                        ]);
                        $field->block_id = $block->id;
                        $field->data = $_field->data ?? null;
                        $field->uitype_id = uitype($_field->uitype)->id;
                        $field->displaytype_id = displaytype($_field->displaytype)->id;
                        $field->sequence = $_field->sequence;
                        $field->save();
                    }
                }
            }
        }

        if (!is_null($this->command)) {
            if ($alreadyExits) {
                $statusMessage = 'already exists. It was <comment>updated</comment>.';
            } else {
                $statusMessage = 'was created.';
            }

            $this->command->line('The module <info>' . $module->name . '</info> '. $statusMessage);
        }

        return $module;
    }

    /**
     * Create module table from fields information
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function createTable(Module $module)
    {
        $tableName = $this->structure->tablePrefix . $this->structure->tableName;

        if (!Schema::hasTable($tableName)) {
            // Create table
            Schema::create($tableName, function (Blueprint $table) use ($module) {
                $table->increments('id');

                // Create each column according to the selected uitype
                foreach ($module->fields as $field) {
                    // Do not recreate id
                    if ($field->name === 'id') {
                        continue;
                    }

                    // Create column
                    $this->createColumn($field, $table);
                }

                $table->timestamps();
                $table->softDeletes();
            });

            if (!is_null($this->command)) {
                $this->command->line('The table <info>' . $tableName . '</info> was created.');
            }

        } else {
            // Update table
            Schema::table($tableName, function(Blueprint $table) use ($module, $tableName) {
                // Create each column according to the selected uitype
                foreach ($module->fields as $field) {
                    // Do not recreate id
                    if ($field->name === 'id') {
                        continue;
                    }

                    // Check if the column already exists and if we need to update it
                    $update = Schema::hasColumn($tableName, $field->name);

                    // Create column
                    $this->createColumn($field, $table, $update);
                }
            });

            if (!is_null($this->command)) {
                $this->command->line('The table <info>' . $tableName . '</info> already exists. It was <comment>updated</comment>.');
            }
        }
    }

    /**
     * Create column in database table
     *
     * @param \Uccello\Core\Models\Field $field
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @param boolean $update
     * @return void
     */
    protected function createColumn(Field $field, Blueprint $table, bool $update = false)
    {
        // Create column
        $column = uitype($field->uitype->id)->createFieldColumn($field, $table);

        // Get field rules
        if (isset($field->data->rules)) {
            $rules = explode('|', $field->data->rules);

            // Add nullable() if the field is not required
            if (!in_array('required', $rules)) {
                $column->nullable();
            }
        }

        if ($update) {
            $column->change();
        }
    }

    /**
     * Create default filter
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function createDefaultFilter(Module $module)
    {
        // Add all field in the filter
        $columns = [];
        foreach ($this->getAllFields() as $field) {
            if ($field->displayInFilter === true) {
                $columns[] = $field->name;
            }
        }

        $filter = Filter::firstOrNew([
            "module_id" => $module->id,
            "domain_id" => null,
            "user_id" => null,
            "name" => 'filter.all',
            "type" => 'list',
        ]);
        $filter->columns = $columns;
        $filter->conditions = null;
        $filter->order_by = null;
        $filter->is_default = true;
        $filter->is_public = false;
        $filter->save();
    }

    /**
     * Create all related lists
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function createRelatedLists(Module $module)
    {
        if (isset($this->structure->relatedLists)) {
            foreach ($this->structure->relatedLists as $_relatedList) {
                // Get tab where we want to connect the related list if defined
                if (isset($_relatedList->tab)) {
                    $tab = Tab::where('module_id', $module->id)
                        ->where('label', $_relatedList->tab)
                        ->first();
                } else {
                    $tab = null;
                }

                // Get related field if defined
                if (isset($_relatedList->related_field)) {
                    $relatedField = Field::where('module_id', $module->id)
                        ->where('name', $_relatedList->related_field)
                        ->first();
                } else {
                    $relatedField = null;
                }

                $relatedList = Relatedlist::firstOrNew([
                    "module_id" => $module->id,
                    "related_module_id" => ucmodule($_relatedList->related_module)->id,
                    "label" => $_relatedList->label,
                ]);
                $relatedList->tab_id = isset($tab) ? $tab->id : null;
                $relatedList->icon = $_relatedList->icon;
                $relatedList->type = $_relatedList->type;
                $relatedList->method = $_relatedList->method;
                $relatedList->data = $_relatedList->data;
                $relatedList->sequence = $_relatedList->sequence;
                $relatedList->save();
            }
        }
    }

    /**
     * Create all links
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function createLinks(Module $module)
    {
        if (isset($this->structure->links)) {
            foreach ($this->structure->links as $_link) {
                $link = Link::firstOrNew([
                    "module_id" => $module->id,
                    "label" => $_link->label,
                ]);
                $link->icon = $_link->icon;
                $link->type = $_link->type;
                $link->url = $_link->url;
                $link->data = $_link->data;
                $link->sequence = $_link->sequence;
                $link->save();
            }
        }
    }

    /**
     * Activate module on all domains
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function activateModuleOnDomains(Module $module)
    {
        $domains = Domain::all();

        foreach($domains as $domain) {
            $domain->modules()->detach($module); // Useful if it exists yet
            $domain->modules()->attach($module);
        }
    }

    /**
     * Create or update language files
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function createLanguageFiles(Module $module)
    {
        foreach ($this->structure->lang as $locale => $translations) {

            $languageFile = $this->filePath . 'resources/lang/' . $locale . '/' . $this->structure->name . '.php';

            // If file exists then update translations
            if ($this->files->exists($languageFile)) {

                // Get old translations ($languageFile returns an array)
                $fileTranslations = $this->files->getRequire($languageFile);

                // Add or update translations ($translations have priority)
                $translations = array_merge($fileTranslations, (array) $translations);

                $message = 'The file <info>' . $languageFile . '</info> already exists. It was <comment>updated</comment>.';
            } else {
                $message = 'The file <info>' . $languageFile . '</info> was created.';
            }

            // Write language file
            $this->writeLanguageFile($languageFile, $translations);

            if (!is_null($this->command)) {
                $this->command->line($message);
            }
        }
    }

    /**
     * Write language file
     *
     * @param string $filepath
     * @param Object|array $translations
     * @return void
     */
    protected function writeLanguageFile(string $filepath, $translations)
    {
        $content = "<?php\n\n".
                    "return [\n";

        foreach ($translations as $key => $val) {
            $content .= "    '$key' => '". str_replace("'", "\'", $val) ."',\n";
        }

        $content .= "];";

        $this->files->put($filepath, $content);
    }

    /**
     * Create model file
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function createModelFile(Module $module)
    {
        $modelClassData = explode('\\', $this->structure->model);

        // Extract class name
        $className = array_pop($modelClassData); // Remove last element: Model

        // Extract namespace
        $namespace = implode('\\', $modelClassData);

        // Extract subdirectories
        $subDirectories = '';
        if (count($modelClassData) > 2) {
            array_pull($modelClassData, 0); // Remove first element: Vendor
            array_pull($modelClassData, 1); // Remove second element: Package

            // Now it remains only the subdirectories
            $subDirectories = implode('/', $modelClassData);

            // Create sub directories if not exist
            if (!$this->files->isDirectory($this->filePath . '/app/' . $subDirectories)) {
                $this->files->makeDirectory($this->filePath . '/app/' . $subDirectories, 0755, true); // Recursive
            }

            $subDirectories .= '/';
        }

        // Table name
        $tableName = $this->structure->tableName;

        // Table prefix
        $tablePrefix = $this->structure->tablePrefix;

        // File path
        $modelFile = $this->filePath .  'app/' . $subDirectories . $className . '.php';

        // Check if file already exists
        if ($this->files->exists($modelFile)) {
            if (!is_null($this->command)) {
                $this->command->line('The file <info>' . $modelFile . '</info> already exists. It was <error>ignored</error>.');
            }
            return;
        }

        // Generate table prefix
        if (!empty($tablePrefix)) {
            $setTablePrefix = "    protected function setTablePrefix()\n".
                            "    {\n".
                            "        \$this->tablePrefix = '$tablePrefix';\n".
                            "    }\n\n";
        } else {
            $setTablePrefix = '';
        }

        // Generate relations
        $relations = "";
        foreach ($this->getAllFields() as $field) {
            if ($field->uitype === 'entity') {
                $relatedModule = Module::where('name', $field->data->module)->first();

                if ($relatedModule) {
                    $relations .= "    public function ". $field->name . "()\n".
                                "    {\n".
                                "        return \$this->belongsTo(\\". $relatedModule->model_class . "::class);\n".
                                "    }\n\n";
                }
            }
        }

        // Generate content
        $content = "<?php\n\n".
                    "namespace $namespace;\n\n".
                    "use Illuminate\Database\Eloquent\SoftDeletes;\n".
                    "use Uccello\Core\Database\Eloquent\Model;\n".
                    "\n".
                    "class $className extends Model\n".
                    "{\n".
                    "    use SoftDeletes;\n\n".
                    "    /**\n".
                    "     * The table associated with the model.\n".
                    "     *\n".
                    "     * @var string\n".
                    "     */\n".
                    "    protected \$table = '$tableName';\n".
                    "\n".
                    "    /**\n".
                    "     * The attributes that should be mutated to dates.\n".
                    "     *\n".
                    "     * @var array\n".
                    "     */\n".
                    "    protected \$dates = ['deleted_at'];\n".
                    "\n".
                    $setTablePrefix.
                    $relations.
                    "    /**\n".
                    "    * Returns record label\n".
                    "    *\n".
                    "    * @return string\n".
                    "    */\n".
                    "    public function getRecordLabelAttribute() : string\n".
                    "    {\n".
                    "        return \$this->id;\n".
                    "    }\n".
                    "}";

        $this->files->put($modelFile, $content);

        if (!is_null($this->command)) {
            $this->command->line('The file <info>' . $modelFile . '</info> was created.');
        }
    }

    /**
     * Get all module fields
     *
     * @return array
     */
    protected function getAllFields()
    {
        $fields = [];

        if (isset($this->structure->tabs)) {
            foreach ($this->structure->tabs as $tab) {
                foreach ($tab->blocks as $block) {
                    if (isset($block->fields)) {
                        foreach ($block->fields as $field) {
                            $fields[] = $field;
                        }
                    }
                }
            }
        }

        return $fields;
    }
}