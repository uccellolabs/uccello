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

class AssignedUser implements Uitype
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
        $options = [ ];

        $relatedModule = ucmodule('user');

        $options = [
            'class' => $relatedModule->model_class ?? null,
            'property' => 'recordLabel',
            'property_key' => 'uid',
            'empty_value' => uctrans('field.select_empty_value', $module),
            'selected' => !empty($record->getKey()) ? $record->{$field->column} : auth()->id(),
            'attr' => [
                // 'class' => 'form-control show-tick',
                // 'data-live-search' => 'true',
                // 'data-abs-ajax-url' => ucroute('uccello.autocomplete', $domain, $relatedModule)
            ],
            'query_builder' => function($relatedRecord) use($record) {
                // TODO: Filter depending users profiles...
                $records = \Uccello\Core\Models\Group::all();

                foreach (\App\User::all() as $user) {
                    $records->push($user);
                }

                return $records;
            },
        ];

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
        $relatedRecord = app('uccello')->getRecordByUid($record->{$field->column});

        if (!$relatedRecord) {
            return '';
        }

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
                // Replace me by connected user's id
                if ($_value === 'me') {
                    $_value = auth()->id();
                }
                $query = $query->orWhere($field->column, '=', $_value);
            }
        });

        return $query;
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
        return $table->string($this->getDefaultDatabaseColumn($field));
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
        return "\$table->string('$column')";
    }
}