<div class="form-group">
    <div class="form-line">
        <select class="form-control bs-placeholder field-search" multiple data-none-selected-text="{{ uctrans('search', $module) }}">
            <option value="true" @if($searchValue && in_array('true', (array)$searchValue))selected="selected"@endif>{{ uctrans('yes', $module) }}</option>
            <option value="false" @if($searchValue && in_array('false', (array)$searchValue))selected="selected"@endif>{{ uctrans('no', $module) }}</option>
        </select>
    </div>
</div>