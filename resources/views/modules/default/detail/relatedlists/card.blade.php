<div class="card relatedlist">
    <div class="card-content">
        {{-- Title --}}
        <span class="card-title">
            {{-- Icon --}}
            <i class="material-icons left primary-text">{{ $relatedlist->icon ?? $relatedlist->relatedModule->icon }}</i>

            {{-- Label --}}
            {{ uctrans($relatedlist->label, $module) }}
        </span>

        {{-- Table --}}
        @include('uccello::modules.default.detail.relatedlists.table', [ 'datatableId' => $datatableId])
    </div>
</div>