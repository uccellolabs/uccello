<div id="exportModal" class="modal">
    <div class="modal-content">
        <h4>
            <i class="material-icons left primary-text">cloud_download</i>
            {{ uctrans('modal.export.title', $module) }}
        </h4>

        <p>{{ uctrans('modal.export.description', $module) }}</p>

        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <select id="export_format">
                        <option value="xlsx">{{ uctrans('modal.export.format.xlsx', $module) }}</option>
                        <option value="xls">{{ uctrans('modal.export.format.xls', $module) }}</option>
                        <option value="ods">{{ uctrans('modal.export.format.ods', $module) }}</option>
                        <option value="csv">{{ uctrans('modal.export.format.csv', $module) }}</option>
                        <option value="html">{{ uctrans('modal.export.format.html', $module) }}</option>
                        <option value="pdf">{{ uctrans('modal.export.format.pdf', $module) }}</option>
                    </select>
                    <label>{{ uctrans('modal.export.format_label', $module) }}</label>
                </div>

                <p>
                    <label>
                        <input type="checkbox" id="export_keep_conditions" checked />
                        <span>{{ uctrans('modal.export.keep_conditions', $module) }}</span>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" id="export_keep_order" checked />
                        <span>{{ uctrans('modal.export.keep_sort', $module) }}</span>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" id="export_with_id" checked />
                        <span>{{ uctrans('modal.export.with_id', $module) }}</span>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" id="with_hidden_columns" />
                        <span>{{ uctrans('modal.export.with_hidden_columns', $module) }}</span>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" id="export_with_timestamps" />
                        <span>{{ uctrans('modal.export.with_timestamps', $module) }}</span>
                    </label>
                </p>

                <input type="hidden" id="export_with_descendants" @if ($seeDescendants) value="1" @else value="0" @endif />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" class="btn-flat waves-effect modal-close">{{ uctrans('button.cancel', $module) }}</a>
        <a href="javascript:void(0)" class="btn-flat waves-effect modal-close green-text export">{{ uctrans('button.export', $module) }}</a>
    </div>
</div>