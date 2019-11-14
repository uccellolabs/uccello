<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Date extends DateTime implements Uitype
{
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
        $options = parent::getFormOptions($record, $field, $domain, $module);

        $options[ 'attr' ] = [
            'class' => 'datepicker',
            'autocomplete' => 'off',
            'data-format' => config('uccello.format.js.date'),
        ];

        // We want the field displays the date in the good format
        $options['value'] = $this->getFormattedValueToDisplay($field, $record);

        return $options;
    }

    /**
     * Return options for Module Designer
     *
     * @return array
     */
    public function getFieldOptions() : array
    {
        return [
            'repeated' => [
                'type' => 'boolean',
                'default_value' => false,
            ],
        ];
    }

    /**
     * Returns formatted value to display.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        return $record->{$field->column}
            ? (new \Carbon\Carbon($record->{$field->column}))->format(config('uccello.format.php.date'))
            : '';
    }

    /**
     * Returns formatted value to save.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Uccello\Core\Models\Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module|null $module
     * @return string|null
     */
    public function getFormattedValueToSave(Request $request, Field $field, $value, $record = null, ?Domain $domain = null, ?Module $module = null) : ?string
    {
        if ($value) {
            $value = \Carbon\Carbon::createFromFormat(config('uccello.format.php.date'), $value);
        }

        return $value;
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
            $query->where(function ($query) use($field, $value) {
                if (strpos($value, ',') > -1) {
                    $values = explode(',', $value); // Start Date, End Date
                    $dateStart = \Carbon\Carbon::createFromFormat(config('uccello.format.php.date'), trim($values[0]));
                    $dateEnd = \Carbon\Carbon::createFromFormat(config('uccello.format.php.date'), trim($values[1]));
                } else {
                    switch ($value) {
                        // Today
                        case uctrans('calendar.ranges.today', $field->module):
                            $dateStart = \Carbon\Carbon::today();
                            $dateEnd = \Carbon\Carbon::today();
                        break;

                        // Month
                        case uctrans('calendar.ranges.month', $field->module):
                            $dateStart = \Carbon\Carbon::today()->firstOfMonth();
                            $dateEnd = $dateStart->copy()->lastOfMonth();
                        break;

                        // Last month
                        case uctrans('calendar.ranges.last_month', $field->module):
                            $dateStart = \Carbon\Carbon::today()->firstOfMonth()->subMonth();
                            $dateEnd = $dateStart->copy()->lastOfMonth();
                        break;

                        // Next month
                        case uctrans('calendar.ranges.next_month', $field->module):
                            $dateStart = \Carbon\Carbon::today()->firstOfMonth()->addMonth();
                            $dateEnd = $dateStart->copy()->lastOfMonth();
                        break;

                        // Quarter
                        case uctrans('calendar.ranges.quarter', $field->module):
                            $dateStart = \Carbon\Carbon::today()->firstOfQuarter();
                            $dateEnd = $dateStart->copy()->lastOfQuarter();
                        break;

                        // Last quarter
                        case uctrans('calendar.ranges.last_quarter', $field->module):
                            $dateStart = \Carbon\Carbon::today()->firstOfQuarter()->subQuarter();
                            $dateEnd = $dateStart->copy()->lastOfQuarter();
                        break;

                        // Next quarter
                        case uctrans('calendar.ranges.next_quarter', $field->module):
                            $dateStart = \Carbon\Carbon::today()->firstOfQuarter()->addQuarter();
                            $dateEnd = $dateStart->copy()->lastOfQuarter();
                        break;

                        // Year
                        case uctrans('calendar.ranges.year', $field->module):
                            $dateStart = \Carbon\Carbon::today()->firstOfYear();
                            $dateEnd = $dateStart->copy()->lastOfYear();
                        break;

                        // Last year
                        case uctrans('calendar.ranges.last_year', $field->module):
                            $dateStart = \Carbon\Carbon::today()->firstOfYear()->subYear();
                            $dateEnd = $dateStart->copy()->lastOfYear();
                        break;

                        // Next year
                        case uctrans('calendar.ranges.next_year', $field->module):
                            $dateStart = \Carbon\Carbon::today()->firstOfYear()->addYear();
                            $dateEnd = $dateStart->copy()->lastOfYear();
                        break;
                    }
                }

                $query->whereBetween($field->column, [ $dateStart, $dateEnd ])->get();
            });
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
        $repeated = $output->confirm('Would you like to repeat this field (for confirmation)?', false);

        if ($repeated) {
            $field->data->repeated = true;
        }
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
                'label' => trans('uccello::config.uitypes.global.repeat_field'),
                'type' => 'boolean',
                'attribute' => 'repeated',
                'default' => false,
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
        return $table->date($this->getDefaultDatabaseColumn($field));
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
        return "\$table->date('$column')";
    }
}