<div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>
                    <div class="block-label-with-icon">
                        <i class="material-icons">cloud_download</i>
                        <span>{{ uctrans('modal.export.title', $module) }}</span>
                    </div>
                    <small>{{ uctrans('modal.export.description', $module) }}</small>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="form-group form-choice">
                            <label class="form-label">{{ uctrans('modal.export.format', $module) }}</label>
                            <div class="input-field">
                                <select class="form-control" id="export_format">
                                    <option value="xlsx">{{ uctrans('modal.export.format.xlsx', $module) }}</option>
                                    <option value="xls">{{ uctrans('modal.export.format.xls', $module) }}</option>
                                    <option value="ods">{{ uctrans('modal.export.format.ods', $module) }}</option>
                                    <option value="csv">{{ uctrans('modal.export.format.csv', $module) }}</option>
                                    <option value="html">{{ uctrans('modal.export.format.html', $module) }}</option>
                                    <option value="pdf">{{ uctrans('modal.export.format.pdf', $module) }}</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-12">
                        <input class="form-control" type="checkbox" id="export_keep_conditions" checked>
                        <label for="export_keep_conditions" class="checkbox-label">{{ uctrans('modal.export.keep_conditions', $module) }}</label>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control chk-col-green" id="export_keep_order" type="checkbox" checked>
                        <label for="export_keep_order" class="checkbox-label">{{ uctrans('modal.export.keep_sort', $module) }}</label>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control" type="checkbox" id="export_with_id" checked>
                        <label for="export_with_id" class="checkbox-label">{{ uctrans('modal.export.with_id', $module) }}</label>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control" type="checkbox" id="export_hide_columns">
                        <label for="export_hide_columns" class="checkbox-label">{{ uctrans('modal.export.hide_columns', $module) }}</label>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control" type="checkbox" id="export_with_timestamps">
                        <label for="export_with_timestamps" class="checkbox-label">{{ uctrans('modal.export.with_timestamps', $module) }}</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{ uctrans('button.cancel', $module) }}</button>
                <button type="button" class="btn btn-link col-green waves-effect export">{{ uctrans('button.export', $module) }}</button>
            </div>
        </div>
    </div>
</div>