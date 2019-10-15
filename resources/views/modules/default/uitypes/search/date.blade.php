<div class="form-group">
    <div class="form-line">
        <input type="text" class="date-range-picker field-search" @if($searchValue)value="{{ $searchValue }}"@endif data-format="{{ config('uccello.format.js.date') }}" data-range="true">
    </div>
</div>