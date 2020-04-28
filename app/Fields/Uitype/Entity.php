<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Spatie\Searchable\Search;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Entity implements Uitype
{
    use DefaultUitype;
    use UccelloUitype;

    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType() : string
    {
        return 'text';
    }

    /**
     * Returns options for Form builder.
     *
     * @param mixed $record
     * @param \Uccello\Core\Models\Field $field
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return array
     */
    public function getFormOptions($record, Field $field, Domain $domain, Module $module) : array
    {
        return [
            'value' => $record->{$field->column},
            'label_attr' => [
                'style'=> 'margin-left: 3.5rem'
            ]
        ];
    }

    /**
     * Return options for Module Designer
     *
     * @return array
     */
    public function getFieldOptions() : array
    {
        return [
            'module' => [
                'mandatory' => true,
                'type' => 'module',
                'whitelist' => [ ],
                'blacklist' => [ ],
                'add_crud_modules' => true,
                'add_not_crud_modules' => true,
                'include_itself' => true,
            ],
        ];
    }

    /**
     * Returns default database column name.
     *
     * @return string
     */
    public function getDefaultDatabaseColumn(Field $field) : string
    {
        return $field->name.'_id';
    }

    /**
     * Returns formatted value to display.
     * Uses recordLabel attribute if defined, id else.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        $relatedRecordId = $record->{$field->column};

        if (!is_object($field->data) || !$field->data->module || !$relatedRecordId) {
            return '';
        }

        // Get related module
        $relatedModule = ucmodule($field->data->module);

        // Get related record
        $relatedModelClass = $relatedModule->model_class;
        $relatedRecord = $relatedModelClass::withoutGlobalScopes()
            ->find($relatedRecordId);

        // Check if there is an attribute called displayLabel in the related record else use id
        if (!is_null($relatedRecord)) {
            $value = $relatedRecord->recordLabel ?? $relatedRecord->getKey();
        } else { // Related record was probably deleted
            $value = '';
        }

        return  $value;
    }

    /**
     * Returns updated query after adding a new search condition.
     *
     * @param \Illuminate\Database\Eloquent\Builder query
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function addConditionToSearchQuery(Builder $query, Field $field, $value) : Builder
    {
        if (!$this->isEmptyOrNotEmptySearchQuery($query, $field, $value)) {
            $tryWithId = true;
            if ($field->data->module ?? false) {
                $modelClass = ucmodule($field->data->module)->model_class;

                if (method_exists($modelClass, 'getSearchResult') && property_exists($modelClass, 'searchableColumns')) {
                    // Search related records and get all ids
                    $searchResults = new Search();
                    $searchResults->registerModel($modelClass, (array) (new $modelClass)->searchableColumns);
                    $recordIds = $searchResults->search($value)->pluck('searchable.id');

                    // Search records linked to record ids got previously
                    $query->whereIn($field->column, $recordIds);
                    $tryWithId = false;
                }
            }

            // Try with id if it was not possible to search into the related module
            if ($tryWithId) {
                $query->where($field->column, '=', $value);
            }
        }

        return $query;
    }

    /**
     * Ask the user some specific options relative to a field
     *
     * @param \StdClass $module
     * @param \StdClass $field
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Input\OutputInterface $output
     * @return void
     */
    public function askFieldOptions(\StdClass &$module, \StdClass &$field, InputInterface $input, OutputInterface $output)
    {
        // Get all modules
        $modules = Module::orderBy('name')->get();

        $choices = [ ];
        foreach ($modules as $_module) {
            $choices[ ] = $_module->name;
        }

        // Add module itself if necessary
        if (!in_array($module->name, $choices)) {
            $choices[ ] = $module->name;
        }

        // Sort
        sort($choices);

        // Related module
        $relatedModule = $output->choice('What is the related module', $choices);

        $field->data->module = $relatedModule;
    }

    /**
     * Returns some specific options relative to the field, used by uccello/module-designer
     *
     * @return array
     */
    public function moduleDesignerConfig()
    {
        return [
            [
                'label' => trans('uccello::config.uitypes.entity.module'),
                'type' => 'module',
                'attribute' => 'module',
                'default' => null,
            ],
        ];
    }

    /**
     * Create field column in the module table
     *
     * @param \Uccello\Core\Models\Field $field
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return \Illuminate\Support\Fluent
     */
    public function createFieldColumn(Field $field, Blueprint $table) : Fluent
    {
        return $table->unsignedInteger($this->getDefaultDatabaseColumn($field));
    }

    /**
     * Get field column creation in string format (for make:module)
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function createFieldColumnStr(Field $field) : string
    {
        $column = $this->getDefaultDatabaseColumn($field);
        return "\$table->unsignedInteger('$column')";
    }
}