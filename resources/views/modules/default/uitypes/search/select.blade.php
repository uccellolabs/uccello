<div class="form-group">
    <div class="form-line">
        <select class="field-search" multiple data-constrain-width="false" data-container=".card-content:parent div" data-alignment="right">
            @if ($column['data']->choices ?? false)
                @foreach ($column['data']->choices as $choice)
                    <option value="{{ $choice }}" @if($searchValue && in_array($choice, (array)$searchValue))selected="selected"@endif>{{ uctrans($choice, $module) }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>