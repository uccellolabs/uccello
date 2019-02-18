<div class="form-group">
    <div class="form-line">
        <input type="text" class="form-control monthpicker" @if($searchValue)value="{{ $searchValue }}"@endif placeholder="{{ uctrans('search', $module) }}">
    </div>
</div>