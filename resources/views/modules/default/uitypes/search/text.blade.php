<div class="form-group">
    <div class="form-line">
        <input type="text" class="form-control" @if($searchValue)value="{{ $searchValue }}"@endif placeholder="{{ uctrans('search_by', $module) }} {{ strtolower(uctrans('field.'.$column['name'], $module)) }}">
    </div>
</div>