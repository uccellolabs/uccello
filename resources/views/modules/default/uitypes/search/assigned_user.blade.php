<div class="form-group">
    <div class="form-line">
        <?php
            $autocompleteSearch = false;

            $entities = [ ];
            $relatedModule =  ucmodule('user');
            $modelClass = $relatedModule->model_class;

            if (isset($column['data']->autocomplete_search) && $column['data']->autocomplete_search === true) {
                $autocompleteSearch = true;
            } else {
                $entities = $modelClass::all();
            }
        ?>
        <select class="field-search" multiple  data-constrain-width="false" data-container=".card-content:parent div" data-alignment="right">
            <option value="me">{{ uctrans('me', $module) }}</option>
            @foreach ($entities as $entity)
                @continue($entity->getKey() === auth()->id())
                <option value="{{ $entity->getKey() }}" @if($searchValue && in_array($entity->getKey(), (array)$searchValue))selected="selected"@endif>{{ $entity->recordLabel }}</option>
                @endforeach
        </select>
    </div>
</div>