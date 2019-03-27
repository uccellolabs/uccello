<div class="form-group">
    <div class="form-line focused">
        <select class="form-control bs-placeholder field-search" multiple data-none-selected-text="{{ uctrans('search', $module) }}">
            @if ($column['data']->choices ?? false)
                @foreach ($column['data']->choices as $choice)
                    <option value="{{ $choice }}" @if($searchValue && in_array($choice, (array)$searchValue))selected="selected"@endif>{{ uctrans($choice, $module) }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>