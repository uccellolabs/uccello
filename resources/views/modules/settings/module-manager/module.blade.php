<div class="col-md-3 col-sm-4">
    <div style="margin-top: 20px;">
        <input id="checkbox-{{ $_module->id }}"
            class="module-activation filled-in @if($_module->isMandatory())chk-col-grey @else chk-col-green @endif"
            type="checkbox"
            data-module="{{ $_module->name }}"
            @if($_module->isActiveOnDomain($domain))checked="checked"@endif
            @if($_module->isMandatory() || !Auth::user()->canAdmin($domain, $_module))disabled="disabled"@endif>
        <label for="checkbox-{{ $_module->id }}" class="checkbox-label">
            <i class="material-icons">{{ $_module->icon ?? 'extension' }}</i>
            <span class="icon-label">{{ uctrans($_module->name, $_module) }}</span>
        </label>
    </div>
</div>