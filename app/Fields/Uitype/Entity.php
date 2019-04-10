<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
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
        return 'entity';
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
        if (!is_object($field->data)) {
            return [ ];
        }

        $options = [ ];

        if ($field->data->module) {
            $relatedModule = ucmodule($field->data->module);

            $options = [
                'class' => $relatedModule->model_class ?? null,
                'property' => $field->data->field ?? 'recordLabel',
                'empty_value' => uctrans('field.select_empty_value', $module),
                'selected' => $record->{$field->column} ?? null,
                'attr' => [
                    // 'class' => 'form-control show-tick',
                    // 'data-live-search' => 'true',
                    // 'data-abs-ajax-url' => ucroute('uccello.autocomplete', $domain, $relatedModule)
                ],
                'query_builder' => function($relatedRecord) use($record) {
                    // If related record class is the same as the record one, ignore the current record
                    if (get_class($relatedRecord) === get_class($record)) {
                        return $relatedRecord->where($relatedRecord->getKeyName(), '!=', $record->getKey());
                    } else {
                        return $relatedRecord->all();
                    }
                },
            ];
        }

        return $options;
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
        $relatedModule = Module::where('name', $field->data->module)->first();

        // Get related record
        $relatedModelClass = $relatedModule->model_class;
        $relatedRecord = $relatedModelClass::find($relatedRecordId);

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
        $query->where(function ($query) use($field, $value) {
            foreach ((array) $value as $_value) {
                $query = $query->orWhere($field->column, '=', $_value);
            }
        });

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