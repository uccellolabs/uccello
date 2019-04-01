<div id="addFilterModal" class="modal">
    <div class="modal-content">
        <h4>
            <i class="material-icons primary-text">filter_list</i>
            {{ uctrans('modal.add_filter.title', $module) }}
        </h4>

        <p>{{ uctrans('modal.add_filter.description', $module) }}</p>

        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <input id="add_filter_filter_name" type="text">
                    <label for="add_filter_filter_name" class="required">{{ uctrans('modal.add_filter.name', $module) }}</label>
                </div>

                <p>
                    <label>
                        <input type="checkbox" checked disabled />
                        <span>{{ uctrans('modal.add_filter.save_columns', $module) }}</span>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" checked disabled />
                        <span>{{ uctrans('modal.add_filter.save_conditions', $module) }}</span>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" id="add_filter_save_order" checked />
                        <span>{{ uctrans('modal.add_filter.save_order', $module) }}</span>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" id="add_filter_save_page_length" checked />
                        <span>{{ uctrans('modal.add_filter.save_page_length', $module) }}</span>
                    </label>
                </p>

                {{-- <p>
                    <label>
                        <input type="checkbox" id="add_filter_is_public" />
                        <span>{{ uctrans('modal.add_filter.is_public', $module) }}</span>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" id="add_filter_is_default" />
                        <span>{{ uctrans('modal.add_filter.is_default', $module) }}</span>
                    </label>
                </p> --}}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" class="btn-flat waves-effect modal-close">{{ uctrans('button.cancel', $module) }}</a>
        <a href="javascript:void(0)" class="btn-flat waves-effect modal-close green-text save">{{ uctrans('button.save', $module) }}</a>
    </div>
</div>