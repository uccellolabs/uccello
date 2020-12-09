<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class ModuleList extends Select implements Uitype
{
    use DefaultUitype;
    use UccelloUitype;

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
        $choices = [ ];

        $modules = Module::all();
        foreach ($modules as $_module) {
            if (auth()->user()->canRetrieve($domain, $_module)) {
                // Ignore admin modules if necessary
                if ($_module->isAdminModule() && isset($field->data->admin) && $field->data->admin === false) {
                    continue;
                }

                $choices[ $_module->id ] = uctrans($_module->name, $_module);
            }
        }

        // Sort choices by values
        sort($choices);

        $options = [
            'choices' => $choices,
            'selected' => null,
            'empty_value' => uctrans('field.select_empty_value', $module),
            'attr' => [
                // 'class' => 'form-control show-tick',
                // 'data-live-search' => 'true'
            ],
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
     * Translate the value.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        $value = $record->{$field->column};

        if ($value) {
            $module = Module::find($value);
            $value = $module ? uctrans($module->name, $module) : '';
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
        $query->where(function ($query) use ($field, $value) {
            foreach ((array) $value as $_value) {
                $formattedValue = $this->getFormattedValueToSearch($_value);
                $query = $query->orWhere($field->column, '=', $formattedValue);
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
    public function askFieldOptions(\stdClass &$module, \StdClass &$field, InputInterface $input, OutputInterface $output)
    {
        $show = $output->confirm('Do you want to show admin modules ?', true);

        if ($show === false) {
            $field->data->admin = false;
        }
    }
}
