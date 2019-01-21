<div class="form-group">
    <div class="form-line">
        <select class="form-control">
            <option value="">&nbsp;</option>
            @if ($column['data']->choices ?? false)
                @foreach ($column['data']->choices as $choice)
                    <option value="{{ $choice }}" @if($searchValue && $choice === $searchValue)selected="selected"@endif>{{ uctrans($choice, $module) }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>