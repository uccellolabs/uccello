<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
     * @param object $bundle
     *
     * @return array
     */
    public function getFieldOptions($bundle) : array
    {
        return [
            [
                'key' => 'module',
                'label' => trans('uccello::uitype.option.entity.module'),
                'required' => true,
                'altersDynamicFields' => true,
                'type' => 'select',
                'choices' => function () {
                    $choices = [];

                    // Get all CRUD modules and add translation
                    $modules = Module::whereNotNull('model_class')->get();
                    $modules = $modules->map(function ($module) {
                        $module->label = uctrans($module->name, $module);
                        return $module;
                    });

                    // Make choices list
                    foreach ($modules->sortBy('label') as $module) {
                        $choices[] = [
                            'value' => $module->name,
                            'label' => $module->label
                        ];
                    }

                    return $choices;
                }
            ],
            [
                'key' => 'field',
                'label' => trans('uccello::uitype.option.entity.field'),
                'type' => 'select',
                'default' => 'recordLabel',
                'choices' => function () use ($bundle) {
                    $options = [
                        ['value' => null, 'label' => trans('uccello::uitype.option.entity.record_label')],
                    ];

                    if (!empty($bundle->field->data['module'])) {
                        $module = Module::where('name', $bundle->field->data['module'])->first();
                        foreach ($module->fields->sortBy('sequence') as $field) {
                            $options[] = [
                                'value' => $field->name,
                                'label' => uctrans('field.'.$field->name, $module)
                            ];
                        }
                    }

                    return $options;
                }
            ],
            [
                'key' => 'relatedlist',
                'label' => trans('uccello::uitype.option.entity.relatedlist'),
                'type' => 'boolean',
                'default' => true
            ]
        ];
    }

    /**
     * Return formatted data column and eventualy all related translations.
     *
     * @param object $bundle
     *
     * @return array
     */
    public function getFormattedFieldDataAndTranslationFromOptions($bundle) : array
    {
        $data = (object) $bundle->field->data;

        // Delete relatedlist param (not useful)
        if (!empty($bundle->field->data['relatedlist'])) {
            unset($data->relatedlist);
        }

        // Delete field=recordLabel (not useful)
        if ($bundle->field->data['field'] === 'recordLabel') {
            unset($data->field);
        }

        return [
            "data" => $data,
            "translation" => [],
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
        //TODO : Allow us on config to decide if we want to display entities on which we do not have access
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
     * Returns formatted value to save with config.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Uccello\Core\Models\Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module|null $module
     * @return string|null
     */
    public function getFormattedValueToSaveWithConfig(Request $request, Field $field, $value, $config, $record = null, ?Domain $domain = null, ?Module $module = null) : ?string
    {
        $newValue = $value;

        if (!is_null($value) && !empty($config) && !empty($config->related_field)) { // Search related record by a specific field
            if ($field->data->module ?? false) {
                $relatedModule = ucmodule($field->data->module);

                $modelClass = $relatedModule->model_class;
                $foundRecord = $modelClass::where($config->related_field, $value)->first();

                $newValue = null;
                if ($foundRecord) {
                    $newValue = $foundRecord->getKey();
                }
            }
        }

        return $this->getFormattedValueToSave($request, $field, $newValue, $record, $domain, $module);
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
