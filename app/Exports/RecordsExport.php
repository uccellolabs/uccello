<?php

namespace Uccello\Core\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Schema;

class RecordsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    /**
     * The module from which export records.
     *
     * @var \Uccello\Core\Models\Module
     */
    protected $module;

    /**
     * The domain from which retrieve records.
     *
     * @var \Uccello\Core\Models\Domain
     */
    protected $domain;

    /**
     * Flag to set if we must export or not the record id.
     * Default: false
     *
     * @var boolean
     */
    protected $addId = false;

    /**
     * Flag to set if we must export or not timestamps columns (created_at, updated_at).
     * Default: false
     *
     * @var boolean
     */
    protected $addTimestamps = false;

    /**
     * Flag to set if we must export or not the records of the descendants domains.
     * Default: false
     *
     * @var boolean
     */
    protected $addDescendants = false;

    /**
     * Columns to export
     *
     * @var array
     */
    protected $columns;

    /**
     * Filter conditions
     *
     * @var array
     */
    protected $conditions;

    /**
     * Sort order
     *
     * @var array
     */
    protected $order;

    /**
     * Set the domain from which we want to retrieve records.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @return \Uccello\Core\Exports\RecordsExport
     */
    public function forDomain($domain)
    {
        if (is_string($domain)) {
            $module = ucdomain($domain);
        }

        $this->domain = $domain;

        return $this;
    }

    /**
     * Set the module from which we want to export records.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @return \Uccello\Core\Exports\RecordsExport
     */
    public function forModule($module)
    {
        if (is_string($module)) {
            $module = ucmodule($module);
        }

        $this->module = $module;

        return $this;
    }

    /**
     * Specify that we want to export record id
     *
     * @return \Uccello\Core\Exports\RecordsExport
     */
    public function withId()
    {
        $this->addId = true;

        return $this;
    }

    /**
     * Specify that we want to export timestamps columns (created_at, updated_at)
     *
     * @return \Uccello\Core\Exports\RecordsExport
     */
    public function withTimestamps()
    {
        $this->addTimestamps = true;

        return $this;
    }

    /**
     * Specify that we want to export records of the descendants domains
     *
     * @return \Uccello\Core\Exports\RecordsExport
     */
    public function withDescendants()
    {
        $this->addDescendants = true;

        return $this;
    }

    /**
     * Specify that we want to export only certain columns
     *
     * @return \Uccello\Core\Exports\RecordsExport
     */
    public function withColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Specify that we want to filter records with search conditions
     *
     * @return \Uccello\Core\Exports\RecordsExport
     */
    public function withConditions($conditions)
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * Specify that we want to order records
     *
     * @return \Uccello\Core\Exports\RecordsExport
     */
    public function withOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Returns the query to use to retrieve records.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        // Filter
        $filter = [
            'columns' => $this->columns,
            'conditions' => $this->conditions,
            'order' => $this->order,
        ];

        // Model class
        $modelClass = $this->module->model_class;

        // Filter on the selected domain if needed
        $query = $modelClass::inDomain($this->domain, $this->addDescendants)
                            ->filterBy($filter);

        return $query;
    }

    /**
     * Defined how to format data.
     *
     * @param mixed $record Uccello record model
     * @return array
     */
    public function map($record): array
    {
        $map = [ ];

        // Add id if needed
        if ($this->addId) {
            $map[ "id" ] = $record->{$record->getKeyName()};
        }

        foreach ($this->module->fields as $field) {
            // Ignore hidden columns if needed
            if (!empty($this->columns) && !in_array($field->name, $this->columns)) {
                continue;
            }

            $uitype = uitype($field->uitype_id);
            $map[ $field->name ] = $uitype->getFormattedValueToDisplay($field, $record);
        }

        // Add timestamps if needed
        if ($this->addTimestamps) {
            $map[ "created_at" ] = $record->created_at;
            $map[ "updated_at" ] = $record->updated_at;
        }

        return $map;
    }

    /**
     * Define what headers to add.
     *
     * @return array
     */
    public function headings(): array
    {
        $headings = [ ];

        // Add id column label if needed
        if ($this->addId) {
            $headings[ ] = uctrans('field.id', $this->module);
        }

        // All translated field label
        foreach ($this->module->fields as $field) {
            // Ignore hidden columns if needed
            if (!empty($this->columns) && !in_array($field->name, $this->columns)) {
                continue;
            }

            $headings[ $field->name ] = uctrans($field->label, $this->module);
        }

        // Add timestamps columns labels if needed
        if ($this->addTimestamps) {
            $headings[ "created_at" ] = uctrans('field.created_at', $this->module);
            $headings[ "updated_at" ] = uctrans('field.updated_at', $this->module);
        }

        return $headings;
    }
}
