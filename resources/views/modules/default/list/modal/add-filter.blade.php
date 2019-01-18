<div class="modal fade" id="addFilterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h4 class="modal-title">
                    {{ uctrans('modal.add_filter.title', $module) }}
                    <small>{{ uctrans('modal.add_filter.description', $module) }}</small>
                </h4> --}}

                <h4>
                    <div class="block-label-with-icon">
                        <i class="material-icons">filter_list</i>
                        <span>{{ uctrans('modal.add_filter.title', $module) }}</span>
                    </div>
                    <small>{{ uctrans('modal.add_filter.description', $module) }}</small>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="form-group form-float m-t-10">
                            <div class="input-field">
                                {{-- Add icon if defined --}}
                                @if($field->icon ?? false)
                                <i class="material-icons prefix">{{ $field->icon }}</i>
                                @endif

                                <div class="form-line">
                                    <label class="form-label required">{{ uctrans('modal.add_filter.name', $module) }}</label>
                                    <input class="form-control" required="required" id="add_filter_filter_name" name="filter_name" type="text">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control" type="checkbox" checked disabled>
                        <label class="checkbox-label">{{ uctrans('modal.add_filter.save_columns', $module) }}</label>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control" type="checkbox" checked disabled>
                        <label class="checkbox-label">{{ uctrans('modal.add_filter.save_conditions', $module) }}</label>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control chk-col-green" id="add_filter_save_order" type="checkbox" checked>
                        <label for="add_filter_save_order" class="checkbox-label">{{ uctrans('modal.add_filter.save_sort', $module) }}</label>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control chk-col-green" id="add_filter_save_rows_number" type="checkbox" checked>
                        <label for="add_filter_save_rows_number" class="checkbox-label">{{ uctrans('modal.add_filter.save_rows_number', $module) }}</label>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control chk-col-green" id="add_filter_is_public" type="checkbox">
                        <label for="add_filter_is_public" class="checkbox-label">{{ uctrans('modal.add_filter.is_public', $module) }}</label>
                    </div>

                    <div class="col-sm-12">
                        <input class="form-control chk-col-green" id="add_filter_is_default" type="checkbox">
                        <label for="add_filter_is_default" class="checkbox-label">{{ uctrans('modal.add_filter.is_default', $module) }}</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{ uctrans('button.cancel', $module) }}</button>
                <button type="button" class="btn btn-link col-green waves-effect save">{{ uctrans('button.save', $module) }}</button>
            </div>
        </div>
    </div>
</div>