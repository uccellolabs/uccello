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

                <small>Une super descripton</small>
            </div>

            {{-- Description --}}
            @if ($block->description)
                <small>{{ uctrans($block->description, $module) }}</small>
            @endif

            <div class="row display-flex">
            {{-- Display all block's fields --}}
            @foreach ($block->fields as $field)
                @continue(!$field->isDetailable())
                <?php
                    // If a special template exists, use it. Else use the generic template
                    $uitypeViewName = sprintf('uitypes.detail.%s', $field->uitype->name);
                    $uitypeFallbackView = 'uccello::modules.default.uitypes.detail.text';
                    $uitypeViewToInclude = uccello()->view($field->uitype->package, $module, $uitypeViewName, $uitypeFallbackView);
                ?>
                @include($uitypeViewToInclude)
            @endforeach
            </div>
        </div>
    </div>
@endforeach