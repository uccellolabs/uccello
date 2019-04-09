<div id="routeLinkModal" class="modal">
    <div class="modal-content">
        <h4>
            <i class="material-icons left primary-text">link</i>
            {{ uctrans('menu_manager.modal.route.title', $module) }}
        </h4>

        <div class="row">
            <div class="col s12">
                {{-- Label --}}
                <div class="input-field">
                    <input id="label" type="text" name="label">
                    <label for="label" class="required">{{ uctrans('menu_manager.modal.link.label', $module) }}</label>
                </div>

                {{-- Module --}}
                <div class="input-field">
                        <input id="module" type="text" name="module">
                        <label for="module" class="required">{{ uctrans('menu_manager.modal.route.module', $module) }}</label>
                    </div>

                {{-- Route --}}
                <div class="input-field">
                    <input id="route" type="text" name="route">
                    <label for="route" class="required">{{ uctrans('menu_manager.modal.route.route', $module) }}</label>
                </div>

                {{-- Icon --}}
                <div class="input-field">
                    <input id="icon" type="text" name="icon">
                    <label for="icon" class="required">{{ uctrans('menu_manager.modal.link.icon', $module) }}</label>
                    <span class="helper-text">
                        {{ uctrans('menu_manager.label.see', $module) }} <a href="https://material.io/tools/icons" target="_blank">https://material.io/tools/icons</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <a class="btn-flat waves-effect modal-close">{{ uctrans('button.cancel', $module) }}</a>
        <a id="save-route-link" class="btn-flat waves-effect green-text">{{ uctrans('button.save', $module) }}</a>
    </div>
</div>