<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Schema;

class ApiController extends Controller
{
    const ITEMS_PER_PAGE = 20;

    /**
     * Display a listing of the resources.
     * The result is formated differently if it is a classic query or one requested by datatable.
     * Filter on domain if domain_id column exists.
     * @param Domain $domain
     * @param Module $module
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Domain $domain, Module $module, Request $request)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:retrieve');

        if ($request->get('datatable')) {
            // If we don't use multi domains, find the first one
            if (!uccello()->useMultiDomains()) {
                $domain = Domain::first();
            }

            // Get data formated for Datatable
            $result = $this->getResultForDatatable($domain, $module, $request);
        } else {
            // Get model model class
            $modelClass = $module->model_class;

            // Filter on domain if column exists
            if (Schema::hasColumn((new $modelClass)->getTable(), 'domain_id')) {
                // Paginate results
                $result = $modelClass::where('domain_id', $domain->id)->paginate(self::ITEMS_PER_PAGE);
            } else {
                // Paginate results
                $result = $modelClass::paginate(self::ITEMS_PER_PAGE);
            }
        }

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:create');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:retrieve');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:update');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Check user permissions
        $this->middleware('uccello.permissions:delete');
    }

    protected function getResultForDatatable(Domain $domain, Module $module, Request $request)
    {
        $draw = (int) $request->get('draw');
        $start = (int) $request->get('start');
        $length = (int) $request->get('length');
        $order = $request->get('order');
        $columns = $request->get('columns');

        // Get model model class
        $modelClass = $module->model_class;

        // If the class exists, make the query
        if (class_exists($modelClass)) {

            // Filter on domain if column exists
            if (Schema::hasColumn((new $modelClass)->getTable(), 'domain_id')) {
                // Count all results
                $total = $modelClass::where('domain_id', $domain->id)->count();

                // Paginate results
                $query = $modelClass::where('domain_id', $domain->id)->skip($start)->take($length);
            } else {
                // Count all results
                $total = $modelClass::count();

                // Paginate results
                $query = $modelClass::skip($start)->take($length);
            }

            // Order results
            foreach ($order as $orderInfo) {
                $columnIndex = (int) $orderInfo["column"];
                $column = $columns[$columnIndex];
                $query = $query->orderBy($column["data"], $orderInfo["dir"]);
            }

            // Make the query
            $data = $query->get();

        } else {
            $data = [];
            $total = 0;
        }

        return [
            "data" => $data,
            "draw" => $draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
        ];
    }
}
