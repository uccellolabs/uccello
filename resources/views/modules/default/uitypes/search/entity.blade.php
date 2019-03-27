<div class="form-group">
    <div class="form-line">
        <?php
            $relatedModule = null;
            $autocompleteSearch = false;

            $entities = [ ];
            if (!empty($column['data']->module)) {
                $relatedModule =  ucmodule($column['data']->module);
                $modelClass = $relatedModule->model_class;
            }

            if (isset($column['data']->autocomplete_search) && $column['data']->autocomplete_search === true) {
                $autocompleteSearch = true;
            } else {
                $entities = $modelClass::all();
            }
        ?>
        <select class="form-control bs-placeholder field-search" multiple data-live-search="true" data-none-selected-text="{{ uctrans('search', $module) }}" @if ($autocompleteSearch) data-abs-ajax-url="{{ ucroute('uccello.autocomplete', $domain, $relatedModule) }}"@endif>
            @foreach ($entities as $entity)
            <option value="{{ $entity->getKey() }}" @if($searchValue && $searchValue == $entity->getKey())selected="selected"@endif>{{ $entity->recordLabel }}</option>
            @endforeach
        </select>
    </div>
</div>