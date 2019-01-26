<div class="row">
    @forelse($widgets as $widget)
        @widget($widget->class, [ 'record' => $record, 'data' => json_decode($widget->pivot->data), 'labelForTranslation' => $widget->labelForTranslation ])
    @empty
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