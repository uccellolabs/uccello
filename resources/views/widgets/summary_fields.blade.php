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
                        <?php $i_col = 0; ?>
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

                                // Count columns
                                $isLarge = $field->data->large ?? false;
                                $i_col += $isLarge ? 2 : 1;
                            ?>
                            {{-- Add an empty div if necessary if the next one is large --}}
                            @if ($isLarge && $i_col % 2 !== 0)
                                <?php $i_col++; ?>
                                <div class="col s6 hide-on-small-only">&nbsp;</div>
                            @endif

                            @include($uitypeViewToInclude, ['forceLarge' => false])
                        @endforeach

                        {{-- Add an empty div if necessary --}}
                        @if ($i_col % 2 !== 0)
                            <div class="col s6 hide-on-small-only">&nbsp;</div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>