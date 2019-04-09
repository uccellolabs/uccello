<ul class="tabs transparent detail-tabs" role="tablist">
    {{-- Summary --}}
    @if ($widgets->count() > 0)
    <li class="tab">
        <a href="#summary" @if ((empty($selectedTabId) && empty($selectedRelatedlistId) && $widgets->count() > 0) || $selectedTabId === 'summary')class="active"@endif>
            <i class="material-icons left">dashboard</i> <span>{{ uctrans('tab.summary', $module) }}</span>
        </a>
    </li>
    @endif
    {{-- Tabs --}}
    @foreach ($module->tabs as $i => $tab)
    <li class="tab">
        <a href="#{{ $tab->id }}" @if ((empty($selectedTabId) && empty($selectedRelatedlistId) && $i === 0 && $widgets->count() === 0) || $selectedTabId === $tab->id)class="active"@endif>
            <i class="material-icons left">{{ $tab->icon ?? 'info' }}</i> <span>{{ uctrans($tab->label, $module) }}</span>
        </a>
    </li>
    @endforeach

    {{-- One tab by related list --}}
    @foreach ($module->relatedlists as $relatedlist)
    @continue(!empty($relatedlist->tab_id) || !Auth::user()->canRetrieve($domain, $relatedlist->relatedModule) || !$relatedlist->isVisibleAsTab)
    <li class="tab">
        <a href="#relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}" @if ($selectedRelatedlistId === $relatedlist->id)class="active"@endif>
            {{-- Icon --}}
            <i class="material-icons left">{{ $relatedlist->icon ?? $relatedlist->relatedModule->icon }}</i>

            {{-- Label --}}
            <span>{{ uctrans($relatedlist->label, $module) }}</span>

            {{-- Badge --}}
            <?php
                $relatedModule = $relatedlist->relatedModule;
                $countMethod = $relatedlist->method . 'Count';

                $model = new $relatedModule->model_class;
                $count = $model->$countMethod($relatedlist, $record->id);
            ?>
            @if ($count > 0)
            <span class="badge green">{{ $count }}</span>
            @endif
        </a>
    </li>
    @endforeach

    @yield('other-tabs-links')
</ul>