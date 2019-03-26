@extends('layouts.app')

@section('page', 'detail')

@section('extra-meta')
<meta name="record" content="{{ $record->id }}">
@endsection

@section('breadcrumb')
    <div class="nav-wrapper">
        <div class="col s12">
            <div class="breadcrumb-container left">
                @if ($admin_env)<a class="breadcrumb" href="{{ ucroute('uccello.settings.dashboard', $domain) }}">{{ uctrans('breadcrumb.admin', $module) }}</a>@endif

                {{-- Module icon --}}
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.list', $domain, $module) }}">
                        <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                        <span>{{ uctrans($module->name, $module) }}</span>
                    </a>
                </span>
                <span class="breadcrumb active">{{ $record->recordLabel }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{-- Tab list --}}
    @include('uccello::modules.default.detail.tabs')

    <div class="detail-blocks">
        @section('default-blocks')
            {{-- Summary --}}
            @if ($widgets->count() > 0)
            <div role="tabpanel" id="summary" class="tab-pane fade in @if ((empty($selectedTabId) && empty($selectedRelatedlistId) && $widgets->count() > 0) || $selectedTabId === 'summary')active @endif" >
                @include('uccello::modules.default.detail.summary')
            </div>
            @endif

            {{-- Tabs and blocks --}}
            @foreach ($module->tabs as $i => $tab)
            <div role="tabpanel" id="{{ $tab->id }}" class="tab-pane fade in @if ((empty($selectedTabId) && empty($selectedRelatedlistId) && $i === 0 && $widgets->count() === 0) || $selectedTabId === $tab->id)active @endif">
                {{-- Blocks --}}
                @include('uccello::modules.default.detail.blocks')

                {{-- Related lists as blocks --}}
                @include('uccello::modules.default.detail.relatedlists.as-blocks')
            </div>
            @endforeach

            {{-- Related lists as tabs --}}
            @include('uccello::modules.default.detail.relatedlists.as-tabs')

            {{-- Other tabs --}}
            @yield('other-tabs')
        @show

        {{-- Other blocks --}}
        @yield('other-blocks')
    </div>

    {{-- Action buttons --}}
    @section('page-action-buttons')
        @include('uccello::modules.default.detail.buttons')
    @show
@endsection

@section('extra-content')
    {{-- Relatedlist selection modal --}}
    @include('uccello::modules.default.detail.relatedlists.selection-modal.modal')
@endsection