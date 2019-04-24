<?php

namespace Uccello\Core\Http\Controllers\Core;

use Spatie\Searchable\Search;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class SearchController extends Controller
{
    protected $viewName = 'search.main';

    /**
     * @var \Uccello\Core\Models\Domain
     */
    protected $domain;

    /**
     * Search records in all modules where user has retrieve capability.
     * 
     * @param \Uccello\Core\Models\Domain
     * @return \Illuminate\Support\Collection
     */
    public function search(?Domain $domain)
    {
        // Pre-process. We force module to be 'home'
        $this->preProcess($domain, ucmodule('home'), request());

        if (!request('q')) {
            return collect();
        }

        $searchResults = $this->getSearchResults();
        

        return $this->autoView(compact('searchResults'));
    }

    /**
     * Search records in all autorized modules
     * 
     * @return \Illuminate\Support\Collection
     */
    protected function getSearchResults()
    {
        $searchResults = new Search();

        // Get all modules in autorized modules
        $modules = $this->getAutorizedModules();

        foreach ($modules as $module) {
            $modelClass = $module->model_class;
            
            if (method_exists($modelClass, 'getSearchResult') && property_exists($modelClass, 'searchableColumns')) {                
                $searchResults->registerModel($modelClass, (array) (new $modelClass)->searchableColumns);
            }
        }

        $searchQuery = request('q');

        return $searchResults
            ->search($searchQuery)
            ->take(config('uccello.search.max_results', 50));
    }

    /**
     * Retrieve all modules in witch the user can search.
     * 
     * @return \Illuminate\Support\Collection
     */
    protected function getAutorizedModules()
    {
        $autorizedModules = collect();

        // Restrct on CRUD modules activated on the domain
        $query = $this->domain->modules()->whereNotNull('model_class');
        
        // If we want to restrict the search in a module add the condition
        if (request('module')) {
            $query = $query->where('name', request('module'));
        }

        // Get all module
        $modules = $query->get();

        foreach ($modules as $module) {
            if (auth()->user()->canRetrieve($this->domain, $module)) {
                $autorizedModules->push($module);
            }
        }

        return $autorizedModules;
    }
}
