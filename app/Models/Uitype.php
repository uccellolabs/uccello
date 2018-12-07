<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Schema\Blueprint;

class Uitype extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uitypes';

    protected function setTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    /**
     * Returns uitype package name
     *
     * @return string|null
     */
    public function getPackageAttribute() : ?string
    {
        $uitypeClass = $this->class;
        return (new $uitypeClass())->package;
    }

    /**
     * Returns formatted value to display.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        $uitypeClass = $this->class;
        return (new $uitypeClass())->getFormattedValueToDisplay($field, $record);
    }

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
    public function getFormattedValueToSave(Request $request, Field $field, $value, $record=null, ?Domain $domain=null, ?Module $module=null) : ?string
    {
        $uitypeClass = $this->class;
        return (new $uitypeClass())->getFormattedValueToSave($request, $field, $value, $record, $domain, $module);
    }

    /**
     * Returns formatted value to search.
     *
     * @param mixed $value
     * @return string
     */
    public function getFormattedValueToSearch($value) : string
    {
        $uitypeClass = $this->class;
        return (new $uitypeClass())->getFormattedValueToSearch($value);
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
        $uitypeClass = $this->class;
        return (new $uitypeClass())->addConditionToSearchQuery($query, $field, $value);
    }

    /**
     * Ask user some specific options relative to a field
     *
     * @param \StdClass $module
     * @param \StdClass $field
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function askFieldOptions(\StdClass &$module, \StdClass &$field, InputInterface $input, OutputInterface $output)
    {
        $uitypeClass = $this->class;
        return (new $uitypeClass())->askFieldOptions($module, $field, $input, $output);
    }

    /**
     * Create field column in the module table
     *
     * @param Field $field
     * @param Blueprint $table
     * @return void
     */
    public function createFieldColumn(Field $field, Blueprint $table)
    {
        $uitypeClass = $this->class;
        return (new $uitypeClass())->createFieldColumn($field, $table);
    }
}
