<div class="row">
    {{-- Widgets --}}
    @if (count($widgets) > 0)
        <ul id='movable'>
            @foreach($widgets as $i => $widget)
                @php ($pos = $widget->getOriginal())
                <li class="col s12 l6 item" data-seq=@php(print $pos['pivot_sequence']) data-widget=@php(print $pos['pivot_widget_id'])>
                    @widget($widget->class, [ 'domain' => $domain->slug, 'module' => $module->name, 'record_id' => $record->id, 'data' => json_decode($widget->pivot->data), 'labelForTranslation' => $widget->labelForTranslation ])
                </li>
                @endforeach
        </ul>

        {{-- No widget --}}
    @else
        <div class="col s12">
            <div class="card">
                <div class="body">
                    <div class="row">
                        <div class="col s12 p-t-20 text-center">
                            <i class="material-icons left orange-text" style="font-size: 60px">widgets</i><br />
                            <span>{{ uctrans('summary.no_widget', $module) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>
