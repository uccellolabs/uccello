<?php

namespace Uccello\Core\Support;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Models\Domain;

class ModuleImport
{
    /**
     * The structure of the module.
     *
     * @var \StdClass
     */
    protected $structure;

    /**
     * Generate all files and database structure to install the new module
     *
     * @param \StdClass $module
     * @return void
     */
    public function install(\StdClass $module)
    {
        $this->structure = $module;

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

        // Create language file
        $this->createLanguageFile($module);

        // Create model file
        $this->createModelFile($module);
    }

    /**
     * Create module structure in the database
     *
     * @return void
     */
    protected function createModule()
    {
        // Create new module
        $module = Module::firstOrNew([
            'name' => $this->structure->name,
        ]);
        $module->icon = $this->structure->icon;
        $module->model_class = $this->structure->model;
        $module->save(); //TODO: or update

        // Create tabs
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

        return $module;
    }

    /**
     * Create module table from fields information
     *
     * @param Module $module
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

        } else {
            // Update table
            Schema::table($tableName, function(Blueprint $table) use ($module, $tableName) {
                // Create each column according to the selected uitype
                foreach ($module->fields as $field) {
                    // Do not recreate id
                    if ($field->name === 'id' || Schema::hasColumn($tableName, $field->name)) {
                        continue;
                    }

                    // Create column
                    $this->createColumn($field, $table);
                }
            });
        }
    }

    /**
     * Create column in database table
     *
     * @param Field $field
     * @param Blueprint $table
     * @return void
     */
    protected function createColumn(Field $field, Blueprint $table)
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
    }

    /**
     * Create default filter
     *
     * @param Module $module
     * @return void
     */
    protected function createDefaultFilter(Module $module)
    {
        // Add all field in the filter
        $columns = [];
        foreach ($this->getAllFields() as $field) { //TODO: Choose fields to display in filter
            $columns[] = $field->name;
        }

        $filter = new Filter();
        $filter->module_id = $module->id;
        $filter->domain_id = null;
        $filter->user_id = null;
        $filter->name = 'filter.all';
        $filter->type = 'list';
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
     * @param Module $module
     * @return void
     */
    protected function createRelatedLists(Module $module)
    {
        if (isset($this->structure->relatedLists)) {
            foreach ($this->structure->relatedLists as $_relatedList) {
                // $relatedList = new Relatedlist();
                // $relatedList->module_id = $module->id;

            }
        }
    }

    /**
     * Create all links
     *
     * @param Module $module
     * @return void
     */
    protected function createLinks(Module $module)
    {
        if (isset($this->structure->links)) {
            foreach ($this->structure->links as $_link) {
                // $link = new Link
            }
        }
    }

    /**
     * Activate module on all domains
     *
     * @param Module $module
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
     * Create language file
     *
     * @param Module $module
     * @return void
     */
    protected function createLanguageFile(Module $module)
    {
        $locale = \Lang::getLocale();

        $languageFile = 'resources/lang/' . $locale . '/' . $this->structure->name . '.php';

        if (!file_exists($languageFile)) {
            $content = "<?php\n\n".
                        "return [\n";

            foreach ($this->structure->lang->{$locale} as $key => $val) {
                $content .= "    '$key' => '". str_replace("'", "\'", $val) ."',\n";
            }

            $content .= "];";

            file_put_contents($languageFile, $content);
        }
    }

    /**
     * Create model file
     *
     * @param Module $module
     * @return void
     */
    protected function createModelFile(Module $module)
    {
        $modelClassData = explode("\\", $this->structure->model);

        // Extract class name
        $className = $modelClassData[count($modelClassData) - 1];

        // Extract namespace
        $namespace = str_replace("\\$className", "", $this->structure->model);

        // File path
        $modelFile = "app/$className.php";

        if (file_exists($modelFile)) {
            return false;
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
                    "use Illuminate\Database\Eloquent\Model;\n".
                    "\n".
                    "class $className extends Model\n".
                    "{\n".
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

        file_put_contents($modelFile, $content);
    }

    /**
     * Get all module fields
     *
     * @return array
     */
    protected function getAllFields()
    {
        $fields = [];

        foreach ($this->structure->tabs as $tab) {
            foreach ($tab->blocks as $block) {
                if (isset($block->fields)) {
                    foreach ($block->fields as $field) {
                        $fields[] = $field;
                    }
                }
            }
        }

        return $fields;
    }
}