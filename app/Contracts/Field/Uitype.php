<?php

namespace Uccello\Core\Contracts\Field;

use Illuminate\Http\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

interface Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function getFormType(Field $field) : string;

    /**
     * Returns options for Form builder.
     *
     * @param mixed $record
     * @param \Uccello\Core\Models\Field $field
     * @param \Uccello\Core\Models\Module $module
     * @return array
     */
    public function getFormOptions($record, Field $field, Module $module) : array;

    /**
     * Returns default database column name.
     *
     * @return string
     */
    public function getDefaultDatabaseColumn(Field $field) : string;

    /**
     * Returns default icon.
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string|null
     */
    public function getDefaultIcon(Field $field) : ?string;

    /**
     * Returns default value.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return mixed|null
     */
    public function getDefaultValue(Field $field, $record);

    /**
     * Returns formatted value to display.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string;

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
    public function getFormattedValueToSave(Request $request, Field $field, $value, $record = null, ?Domain $domain = null, ?Module $module = null) : ?string;

    /**
     * Returns formatted value to search.
     * By default adds % at the beginning end the ending to make a 'like' query.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $value
     * @return string
     */
    public function getFormattedValueToSearch(Field $field, $value) : string;

    /**
     * Returns updated query after adding a new search condition.
     *
     * @param \Illuminate\Database\Eloquent\Builder query
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function addConditionToSearchQuery(Builder $query, Field $field, $value) : Builder;

    /**
     * Ask the user some specific options relative to a field
     *
     * @param \StdClass $module
     * @param \StdClass $field
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Input\OutputInterface $output
     * @return void
     */
    public function askFieldOptions(\StdClass &$module, \StdClass &$field, InputInterface $input, OutputInterface $output);

    /**
     * Create field column in the module table
     *
     * @param \Uccello\Core\Models\Field $field
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return \Illuminate\Support\Fluent
     */
    public function createFieldColumn(Field $field, Blueprint $table) : Fluent;

    /**
     * Get field column creation in string format
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function createFieldColumnStr(Field $field) : string;
}