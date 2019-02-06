<div class="row">
    <div class="col-sm-12">
        <div class="card block">
            <div class="header">
                <h2>
                    <div class="block-label-with-icon">

                        {{-- Icon --}}
                        <i class="material-icons">reorder</i>

                        {{-- Label --}}
                        <span>{{ trans($label) }}</span>
                    </div>
                </h2>
            </div>
            <div class="body">
                <div class="row display-flex">
                    <?php
                        $record = $config['record'];
                        $data = $config['data'];
                    ?>
                    @if (!empty($data) && is_array($data->fields))
                        @foreach ($data->fields as $fieldName)
                            <?php
                                $field = \Uccello\Core\Models\Field::where('module_id', $module->id)
                                        ->where('name', $fieldName)
                                        ->first();
                            ?>
                            @continue(is_null($field) || !$field->isDetailable())
                            <?php
                                // If a special template exists, use it. Else use the generic template
                                $uitypeViewName = sprintf('uitypes.detail.%s', $field->uitype->name);
                                $uitypeFallbackView = 'uccello::modules.default.uitypes.detail.text';
                                $uitypeViewToInclude = uccello()->view($field->uitype->package, $module, $uitypeViewName, $uitypeFallbackView);
                            ?>
                            @include($uitypeViewToInclude, ['forceLarge' => false])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>