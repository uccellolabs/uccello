<?php
$moduleList = collect();

foreach ($modules as $_module) {
    if (auth()->user()->canRetrieve($domain, $_module)) {
        // Ignore admin modules if necessary
        if ($_module->isAdminModule() && isset($field->data->admin) && $field->data->admin === false) {
            continue;
        }

        $moduleList[] = [
            'id' => $_module->id,
            'name' => uctrans($_module->name, $_module)
        ];
    }
}
?>
<div class="form-group">
    <div class="form-line">
        <select class="field-search" multiple data-constrain-width="false" data-container=".card-content:parent div" data-alignment="right">
            {{-- Sort modules by translated names --}}
            @foreach ($moduleList->sortBy('name') as $_module)
                <option value="{{ $_module["id"] }}" @if($searchValue && in_array($_module["id"], (array)$searchValue))selected="selected"@endif>{{ $_module["name"] }}</option>
            @endforeach
        </select>
    </div>
</div>
