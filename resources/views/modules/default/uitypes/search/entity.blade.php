<div class="form-group">
    <div class="form-line">
        <?php
            $relatedModule = null;
            $entities = [ ];
            if (!empty($column['data']->module)) {
                $relatedModule =  ucmodule($column['data']->module);
                $modelClass = $relatedModule->model_class;

                $entities = $modelClass::take(10)->get();
            }
        ?>
        <select class="form-control" multiple data-live-search="true" data-none-selected-text="{{ uctrans('search', $module) }}" @if ($relatedModule) data-abs-ajax-url="{{ ucroute('uccello.autocomplete', $domain, $relatedModule) }}"@endif>
            @foreach ($entities as $entity)
            <option value="{{ $entity->getKey() }}">{{ $entity->recordLabel }}</option>
            @endforeach
        </select>
    </div>
</div>