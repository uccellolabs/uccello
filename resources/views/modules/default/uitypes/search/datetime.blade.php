<div class="form-group">
    <div class="form-line">
        <input type="text" class="datetime-range-picker field-search" @if($searchValue)value="{{ $searchValue }}"@endif data-format="{{ config('uccello.format.js.datetime') }}">
    </div>
</div>