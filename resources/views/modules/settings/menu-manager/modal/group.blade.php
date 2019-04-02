<div id="groupModal" class="modal">
    <div class="modal-content">
        <h4>
            <i class="material-icons left primary-text">filter_list</i>
            {{ uctrans('menu.button.add_group', $module) }}
        </h4>

        <div class="row">
            <div class="col s12">
                {{-- Label --}}
                <div class="input-field">
                    <input id="label" type="text" name="label">
                    <label for="label" class="required">{{ uctrans('menu.group.label', $module) }}</label>
                </div>

                {{-- Icon --}}
                <div class="input-field">
                    <input id="icon" type="text" name="icon">
                    <label for="icon" class="required">{{ uctrans('menu.group.icon', $module) }}</label>
                    <span class="helper-text">
                        {{ uctrans('menu.icon.see', $module) }} <a href="https://material.io/tools/icons" target="_blank">https://material.io/tools/icons</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <a class="btn-flat waves-effect modal-close">{{ uctrans('button.cancel', $module) }}</a>
        <a id="save-group" class="btn-flat waves-effect green-text">{{ uctrans('button.save', $module) }}</a>
    </div>
</div>