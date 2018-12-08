<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Contracts\Field\Uitype;

class Checkbox implements Uitype
{
    use DefaultUitype;
    use UccelloUitype;

    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
    {
        return 'checkbox';
    }

    /**
     * Returns formatted value to display.
     * Display Yes or No instead of 1 or 0.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        $value = $record->{$field->column};

        $label = $value ? uctrans('yes', $field->module) : uctrans('no', $field->module);

        return  ucfirst($label);
    }

    /**
     * Returns formatted value to save.
     * If value is null, returns 0 (false)
     *
     * @param Request $request
     * @param Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param Domain|null $domain
     * @param Module|null $module
     * @return string|null
     */
    public function getFormattedValueToSave(Request $request, Field $field, $value, $record=null, ?Domain $domain=null, ?Module $module=null) : ?string
    {
        return $value ?? 0;
    }

    /**
     * Returns formatted value to search.
     *
     * @param mixed $value
     * @return string
     */
    public function getFormattedValueToSearch($value) : string
    {
        $formattedValue = $value === 'true' ? true : false;

        return $formattedValue;
    }

    /**
     * Returns updated query after adding a new search condition.
     *
     * @param Builder query
     * @param Field $field
     * @param mixed $value
     * @return Builder
     */
    public function addConditionToSearchQuery(Builder $query, Field $field, $value) : Builder
    {
        $formattedValue = $this->getFormattedValueToSearch($value);
        $query = $query->where($field->column, '=', $formattedValue);

        return $query;
    }

    /**
     * Create field column in the module table
     *
     * @param Field $field
     * @param Blueprint $table
     * @return \Illuminate\Support\Fluent
     */
    public function createFieldColumn(Field $field, Blueprint $table)
    {
        return $table->boolean($this->getDefaultDatabaseColumn($field));
    }

    /**
     * Ask the user some specific options relative to a field
     *
     * @param \StdClass $module
     * @param \StdClass $field
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function askFieldOptions(\StdClass &$module, \StdClass &$field, InputInterface $input, OutputInterface $output)
    {
        // Not repeated
    }
}