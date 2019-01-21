<div class="form-group">
    <div class="form-line">
        <select class="form-control">
            <option value="">&nbsp;</option>
            <option value="true" @if($searchValue && 1 === $searchValue)selected="selected"@endif>{{ uctrans('yes', $module) }}</option>
            <option value="false" @if($searchValue && 0 === $searchValue)selected="selected"@endif>{{ uctrans('no', $module) }}</option>
        </select>
    </div>
</div>