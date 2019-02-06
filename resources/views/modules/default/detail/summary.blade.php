<div class="row">
    {{-- Widgets --}}
    @if (count($widgets) > 0)
    <div class="col-md-6">
        @foreach($widgets as $i => $widget)
            @continue($i % 2 !== 0)
            @widget($widget->class, [ 'domain' => $domain->slug, 'module' => $module->name, 'record_id' => $record->id, 'data' => json_decode($widget->pivot->data), 'labelForTranslation' => $widget->labelForTranslation ])
        @endforeach
    </div>

    <div class="col-md-6">
        @foreach($widgets as $i => $widget)
            @continue($i % 2 !== 1)
            @widget($widget->class, [ 'domain' => $domain->slug, 'module' => $module->name, 'record_id' => $record->id, 'data' => json_decode($widget->pivot->data), 'labelForTranslation' => $widget->labelForTranslation ])
        @endforeach
    </div>
    {{-- No widget --}}
    @else
    <div class="col-sm-12">
        <div class="card block">
            <div class="body">
                <div class="row">
                    <div class="col-md-12 p-t-20 text-center">
                        <i class="material-icons col-orange" style="font-size: 60px">widgets</i><br />
                        <span>{{ uctrans('summary.no_widget', $module) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforelse
</div>