<div class="col s6 m4 l3">
    <p style="margin-top: 20px;">
        <label>
            <input id="checkbox-{{ $_module->id }}"
                type="checkbox"
                class="filled-in module-activation"
                data-module="{{ $_module->name }}"
                @if($_module->isActiveOnDomain($domain))checked="checked"@endif
                @if($_module->isMandatory() || !Auth::user()->canAdmin($domain, $_module))disabled="disabled"@endif>

            <span @if(!$_module->isMandatory() && Auth::user()->canAdmin($domain, $_module))class="black-text"@endif>
                <i class="material-icons left">{{ $_module->icon ?? 'extension' }}</i>
                {{ uctrans($_module->name, $_module) }}
            </span>
        </label>
    </p>
</div>