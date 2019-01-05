<?php

namespace Uccello\Core\Support;

use Illuminate\Filesystem\Filesystem;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Field;

class ModuleExport
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
     * Get module structure
     *
     * @param \Uccello\Core\Models\Module $module
     * @return string
     */
    public function getStructure(Module $module)
    {
        if (empty($this->structure) || $this->structure->name !== $module->name) {
            $this->generateStructure($module);
        }

        return $this->structure;
    }

    /**
     * Get module structure in json format
     *
     * @param \Uccello\Core\Models\Module $module
     * @return \StdClass
     */
    public function getJson(Module $module)
    {
        if (empty($this->structure) || $this->structure->name !== $module->name) {
            $this->generateStructure($module);
        }

        return json_encode($this->structure);
    }

    /**
     * Generate module exportable structure
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    public function generateStructure(Module $module)
    {
        $this->initFilePath($module);
        $this->generateModuleStructure($module);
        $this->generateTabsBlocksFieldsStructure($module);
        $this->generateRelatedListsStructure($module);
        $this->generateLinksStructure($module);
        $this->generateTranslationsStructure($module);
    }

    /**
     * Generate module exportable structure
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function generateModuleStructure(Module $module)
    {
        $this->structure = new \StdClass();
        $this->structure->name = $module->name;
        $this->structure->icon = $module->icon;
        $this->structure->model = $module->model_class;
        $this->structure->data = $module->data;

        if (!empty($module->model_class)) {
            $modelClass = $module->model_class;
            $model = new $modelClass;

            $table = $model->getTable(); // prefix + name
            $tablePrefix = $model->getTablePrefix();
            $tableName = preg_replace("`^$tablePrefix`", '', $table);

            $this->structure->tableName = $tableName;
            $this->structure->tablePrefix = $tablePrefix;
        } else {
            $this->structure->tableName = null;
            $this->structure->tablePrefix = null;
        }
    }

    /**
     * Generate tabs, blocks and fields exportable structure
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function generateTabsBlocksFieldsStructure(Module $module) {
        if (empty($this->structure->tabs)) {
            $this->structure->tabs = [ ];
        }

        $defaultFilter = $module->filters->where('name', 'filter.all')->first();

        foreach ($module->tabs as $tab) {
            $_tab = new \StdClass();
            $_tab->blocks = [ ];
            $_tab->id = $tab->id;
            $_tab->label = $tab->label;
            $_tab->icon = $tab->icon;
            $_tab->sequence = $tab->sequence;
            $_tab->data = $tab->data;

            foreach ($tab->blocks as $block) {
                $_block = new \StdClass();
                $_block->fields = [ ];
                $_block->id = $block->id;
                $_block->label = $block->label;
                $_block->icon = $block->icon;
                $_block->sequence = $block->sequence;
                $_block->data = $block->data;

                $_tab->blocks[ ] = $_block;

                foreach ($block->fields as $field) {
                    $_field = new \StdClass();
                    $_field->id = $field->id;
                    $_field->name = $field->name;
                    $_field->uitype = uitype($field->uitype_id)->name;
                    $_field->displaytype = displaytype($field->displaytype_id)->name;
                    $_field->sequence = $field->sequence;
                    $_field->data = $field->data;
                    $_field->displayInFilter = !empty($defaultFilter) ? in_array($field->name, $defaultFilter->columns) : false;

                    $_block->fields[ ] = $_field;
                }
            }

            $this->structure->tabs[ ] = $_tab;
        }
    }

    /**
     * Generate related lists exportable structure
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function generateRelatedListsStructure(Module $module) {
        if (empty($this->structure->relatedlists)) {
            $this->structure->relatedlists = [ ];
        }

        foreach ($module->relatedlists as $relatedlist) {
            $_relatedlist = new \StdClass();
            $_relatedlist->id = $relatedlist->id;
            $_relatedlist->module = ucmodule($relatedlist->module_id)->name;
            $_relatedlist->related_module = ucmodule($relatedlist->related_module_id)->name;
            $_relatedlist->tab = $relatedlist->tab_id ? Tab::find($relatedlist->tab_id)->label : null;
            $_relatedlist->related_field = $relatedlist->related_field_id ? Field::find($relatedlist->related_field_id)->name : null;
            $_relatedlist->label = $relatedlist->label;
            $_relatedlist->icon = $relatedlist->icon;
            $_relatedlist->type = $relatedlist->type;
            $_relatedlist->method = $relatedlist->method;
            $_relatedlist->sequence = $relatedlist->sequence;
            $_relatedlist->data = $relatedlist->data;

            $this->structure->relatedlists[ ] = $_relatedlist;
        }
    }

    /**
     * Generate links exportable structure
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function generateLinksStructure(Module $module) {
        if (empty($this->structure->links)) {
            $this->structure->links = [ ];
        }

        foreach ($module->links as $link) {
            $_link = new \StdClass();
            $_link->id = $link->id;
            $_link->label = $link->label;
            $_link->icon = $link->icon;
            $_link->type = $link->type;
            $_link->url = $link->url;
            $_link->sequence = $link->sequence;
            $_link->data = $link->data;

            $this->structure->links[ ] = $_link;
        }
    }

    /**
     * Generate translations exportable structure
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function generateTranslationsStructure(Module $module)
    {
        if (empty($this->structure->lang)) {
            $this->structure->lang = new \StdClass();
        }

        if (!$this->files->exists($this->filePath.'resources/lang')) {
            return;
        }

        // Retrieve all languages (one directory represents one language)
        $languageDirectories = $this->files->directories($this->filePath.'resources/lang');

        foreach ($languageDirectories as $languageDirectory) {
            $lang = $this->files->basename($languageDirectory);

            $this->structure->lang->{$lang} = new \StdClass();

            // Get module translations
            $moduleTranslationFile = $this->filePath.'resources/lang/'.$lang.'/'.$this->structure->name.'.php';

            if ($this->files->exists($moduleTranslationFile)) {
                $translations = $this->files->getRequire($moduleTranslationFile);

                foreach ($translations as $key => $val) {
                    $this->structure->lang->{$lang}->{$key} = $val;
                }
            }
        }
    }

    /**
     * Retrieve module base directory from its package name
     *
     * @param \Uccello\Core\Models\Module $module
     * @return void
     */
    protected function initFilePath(Module $module)
    {
        $this->filePath = '';

        if (!empty($module->data->package)) {
            // Extract vendor and package names
            $packageParts = explode('/', $module->data->package);

            if (count($packageParts) === 2) {
                $this->filePath = 'packages/'.$packageParts[ 0 ].'/'.$packageParts[ 1 ].'/';
            }
        }
    }
}