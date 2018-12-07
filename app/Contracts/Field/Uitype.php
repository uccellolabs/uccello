<?php

namespace Uccello\Core\Contracts\Field;

use Illuminate\Http\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

interface Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @param Field $field
     * @return string
     */
    public function getFormType(): string;

    /**
     * Returns options for Form builder.
     *
     * @param mixed $record
     * @param Field $field
     * @param Module $module
     * @return array
     */
    public function getFormOptions($record, Field $field, Module $module) : array;

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string;

    /**
     * Returns formatted value to display.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string;

    /**
     * Returns updated query after adding a new search condition.
     *
     * @param Builder query
     * @param Field $field
     * @param mixed $value
     * @return Builder
     */
    public function addConditionToSearchQuery(Builder $query, Field $field, $value);

    /**
     * Returns formatted value to save.
     *
     * @param Request $request
     * @param Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param Domain|null $domain
     * @param Module|null $module
     * @return string|null
     */
    public function getFormattedValueToSave(Request $request, Field $field, $value, $record=null, ?Domain $domain=null, ?Module $module=null) : ?string;

    /**
     * Ask the user some specific options relative to a field
     *
     * @param \StdClass $module
     * @param \StdClass $field
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function askFieldOptions(\StdClass &$module, \StdClass &$field, InputInterface $input, OutputInterface $output);

    /**
     * Create field column in the module table
     *
     * @param Field $field
     * @param Blueprint $table
     * @return void
     */
    public function createFieldColumn(Field $field, Blueprint $table);
}