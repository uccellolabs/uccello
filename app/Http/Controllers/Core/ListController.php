<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Filter;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Http\Middleware\CheckPermissions;


class ListController extends Controller
{
    protected $viewName = 'list.main';

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:retrieve');
    }

    /**
     * @inheritDoc
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get datatable columns
        $datatableColumns = $this->getDatatableColumns();

        return $this->autoView(compact('datatableColumns'));
    }

    /**
     * Retrieve columns to display in the list of data
     *
     * @return array
     */
    protected function getDatatableColumns(): array
    {
        $columns = [];

        // Get default filter
        $filter = Filter::where('module_id', $this->module->id)
            ->where('type', 'list')
            ->first();

        // Get all fields
        $fields = $this->module->fields;

        foreach ($fields as $field) {
            // If the field is not listable, continue
            if (!$field->isListable()){
                continue;
            }

            // Add the field as a new column
            $columns[] = [
                'name' => $field->name,
                'db_column' => $field->column,
                'uitype' => $field->uitype->name,
                'data' => $field->data,
                'visible' => in_array($field->name, $filter->columns)
            ];
        }

        return $columns;
    }
}
