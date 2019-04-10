<div class="form-group">
    <div class="form-line">
        <select class="field-search" multiple data-constrain-width="false" data-container=".card-content:parent div" data-alignment="right">
            <option value="true" @if($searchValue && in_array('true', (array)$searchValue))selected="selected"@endif>{{ uctrans('yes', $module) }}</option>
            <option value="false" @if($searchValue && in_array('false', (array)$searchValue))selected="selected"@endif>{{ uctrans('no', $module) }}</option>
        </select>
    </div>
</div>