<div id="addLinkModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ uctrans('menu.button.add_link', $module) }}</h4>
            </div>
            <div class="modal-body">
                {{-- Label --}}
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" name="label" class="form-control">
                        <label class="form-label">{{ uctrans('menu.link.label', $module) }}</label>
                    </div>
                </div>

                {{-- Icon --}}
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" name="icon" class="form-control">
                        <label class="form-label">{{ uctrans('menu.link.icon', $module) }}</label>
                    </div>
                    <div class="help-info">
                        {{ uctrans('menu.icon.see', $module) }} <a href="https://material.io/tools/icons" target="_blank">https://material.io/tools/icons</a>
                    </div>
                </div>

                {{-- Url --}}
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" name="url" class="form-control">
                        <label class="form-label">{{ uctrans('menu.link.url', $module) }}</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{ uctrans('button.cancel', $module) }}</button>
                <button type="button" id="add-link" class="btn btn-link waves-effect col-green">{{ uctrans('button.save', $module) }}</button>
            </div>
        </div>
    </div>
</div>