<div id="linkModal" class="modal">
    <div class="modal-content">
        <h4>
            <i class="material-icons primary-text">link</i>
            {{ uctrans('menu.button.add_link', $module) }}
        </h4>

        <div class="row">
            <div class="col s12">
                {{-- Label --}}
                <div class="input-field">
                    <input id="label" type="text" name="label">
                    <label for="label" class="required">{{ uctrans('menu.link.label', $module) }}</label>
                </div>

                {{-- Url --}}
                <div class="input-field">
                    <input id="url" type="url" name="url">
                    <label for="url" class="required">{{ uctrans('menu.link.url', $module) }}</label>
                </div>

                {{-- Icon --}}
                <div class="input-field">
                    <input id="icon" type="text" name="icon">
                    <label for="icon" class="required">{{ uctrans('menu.link.icon', $module) }}</label>
                    <span class="helper-text">
                        {{ uctrans('menu.icon.see', $module) }} <a href="https://material.io/tools/icons" target="_blank">https://material.io/tools/icons</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <a class="btn-flat waves-effect modal-close">{{ uctrans('button.cancel', $module) }}</a>
        <a id="save-link" class="btn-flat waves-effect green-text">{{ uctrans('button.save', $module) }}</a>
    </div>
</div>