@foreach ($tab->blocks as $block)
    <div class="card">
        <div class="card-content">
            {{-- Title --}}
            <div class="card-title">
                {{-- Icon --}}
                @if($block->icon)
                <i class="material-icons left primary-text">{{ $block->icon }}</i>
                @endif

                {{-- Label --}}
                {{ uctrans($block->label, $module) }}

                {{-- Description --}}
                @if ($block->description)
                <small class="with-icon">{{ uctrans($block->description, $module) }}</small>
                @endif
            </div>

            <div class="row display-flex">
                {{-- Display all block's fields --}}
                <?php $i_col = 0; ?>
                @foreach ($block->fields as $field)
                    @continue(!$field->isDetailable())
                    <?php
                        // If a special template exists, use it. Else use the generic template
                        $uitype = uitype($field->uitype_id);
                        $uitypeViewName = sprintf('uitypes.detail.%s', $uitype->name);
                        $uitypeFallbackView = 'uccello::modules.default.uitypes.detail.text';
                        $uitypeViewToInclude = uccello()->view($uitype->package, $module, $uitypeViewName, $uitypeFallbackView);

                        // Count columns
                        $isLarge = $field->data->large ?? false;
                        $i_col += $isLarge ? 2 : 1;
                    ?>
                    {{-- Add an empty div if necessary if the next one is large --}}
                    @if ($isLarge && $i_col % 2 !== 0)
                        <?php $i_col++; ?>
                        <div class="col s6 hide-on-small-only">&nbsp;</div>
                    @endif

                    @include($uitypeViewToInclude)
                @endforeach

                {{-- Add an empty div if necessary --}}
                @if ($i_col % 2 !== 0)
                    <div class="col s6 hide-on-small-only">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
@endforeach