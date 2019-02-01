<?php

namespace Uccello\Core\Http\Controllers\Core;

use Schema;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Events\BeforeSaveEvent;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Events\BeforeDeleteEvent;
use Uccello\Core\Events\AfterDeleteEvent;

class ApiController extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resources.
     * The result is formated differently if it is a classic query or one requested by datatable.
     * Filter on domain if domain_id column exists.
     * @param  \Uccello\Core\Models\Domain $domain
     * @param  \Uccello\Core\Models\Module $module
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Domain $domain, Module $module, Request $request)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:retrieve');

        // Get model model class
        $modelClass = $module->model_class;

        //TODO: Add search conditions

        // Filter on domain if column exists
        if (Schema::hasColumn((new $modelClass)->getTable(), 'domain_id')) {
            // Paginate results
            $result = $modelClass::where('domain_id', $domain->id)->paginate(self::ITEMS_PER_PAGE);
        } else {
            // Paginate results
            $result = $modelClass::paginate(self::ITEMS_PER_PAGE);
        }

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Uccello\Core\Models\Domain $domain
     * @param  \Uccello\Core\Models\Module $module
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Domain $domain, Module $module, Request $request)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:create');

        // Get model model class
        $modelClass = $module->model_class;
        $record = new $modelClass();

        if (Schema::hasColumn((new $modelClass)->getTable(), 'domain_id')) {
            // Paginate results
            $record->domain_id = $domain->id;
        }

        foreach ($request->all() as $fieldName => $value) {
            $field = $module->getField($fieldName);

            // If the field exists format the value and store it in the good model column
            if (!is_null($field)) {
                $column = $field->column;
                $record->$column = $field->uitype->getFormattedValueToSave($request, $field, $value, $record, $domain, $module);
            }
        }

        // Dispatch before save event
        event(new BeforeSaveEvent($domain, $module, $request, $record, 'create', true));

        // Save
        $record->save();

        // Dispatch after save event
        event(new AfterSaveEvent($domain, $module, $request, $record, 'create', true));

        return $modelClass::find($record->getKey()); // We do this to display also empty fields
    }

    /**
     * Display the specified resource.
     *
     * @param  \Uccello\Core\Models\Domain $domain
     * @param  \Uccello\Core\Models\Module $module
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain, Module $module, int $id)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:retrieve');

        // Get model model class
        $modelClass = $module->model_class;

        return $modelClass::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Uccello\Core\Models\Domain $domain
     * @param  \Uccello\Core\Models\Module $module
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Domain $domain, Module $module, int $id, Request $request)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:update');

        // Get model model class
        $modelClass = $module->model_class;
        $record = $modelClass::find($id);

        if (!$record) {
            return false;
        }

        foreach ($request->all() as $fieldName => $value) {
            $field = $module->getField($fieldName);

            // If the field exists format the value and store it in the good model column
            if (!is_null($field)) {
                $column = $field->column;
                $record->$column = $field->uitype->getFormattedValueToSave($request, $field, $value, $record, $domain, $module);
            }
        }

        // Dispatch before save event
        event(new BeforeSaveEvent($domain, $module, $request, $record, 'edit', true));

        // Save
        $record->save();

        // Dispatch after save event
        event(new AfterSaveEvent($domain, $module, $request, $record, 'edit', true));

        return $record;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Uccello\Core\Models\Domain $domain
     * @param  \Uccello\Core\Models\Module $module
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain, Module $module, $id, Request $request)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:delete');

        // Get model model class
        $modelClass = $module->model_class;

        $record = $modelClass::find($id);

        if (!$record) {
            return [
                "success" => false,
                "message" => 'Record not found'
            ];
        }

        // Dispatch before delete event
        event(new BeforeDeleteEvent($domain, $module, $request, $record, true));

        // Delete
        $record->delete();

        // Dispatch after delete event
        event(new AfterDeleteEvent($domain, $module, $request, $record, true));

        return [
            "success" => true,
            "message" => 'Record deleted',
            "id" => $id
        ];
    }
}