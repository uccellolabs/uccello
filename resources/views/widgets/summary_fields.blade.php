<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                {{-- Title --}}
                <span class="card-title">
                    {{-- Icon --}}
                    <i class="material-icons left primary-text">reorder</i>

                    {{-- Label --}}
                    {{ trans($label) }}
                </span>

                <div class="row display-flex">
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