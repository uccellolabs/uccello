@extends('layouts.uccello')

@section('page', 'detail')

@section('extra-meta')
<meta name="record" content="{{ $record->id }}">
@append

@section('breadcrumb')
    <div class="nav-wrapper">
        <div class="col s12">
            <div class="breadcrumb-container left">
                {{-- Admin --}}
                @if ($admin_env)
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.settings.dashboard', $domain) }}">
                        <i class="material-icons left">settings</i>
                        <span class="hide-on-small-only">{{ uctrans('breadcrumb.admin', $module) }}</span>
                    </a>
                </span>
                @endif

                {{-- Module icon --}}
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.list', $domain, $module) }}">
                        <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                        <span class="hide-on-small-only">{{ uctrans($module->name, $module) }}</span>
                    </a>
                </span>
                <span class="breadcrumb active">{{ $record->recordLabel }}</span>
            </div>
        </div>
    </div>
@endsection

{{-- Custom links --}}
@section('top-action-buttons')
    <div class="action-buttons right-align">
    @section ('custom-links')
        @include('uccello::modules.default.detail.links')
    @show
    </div>
@append

@section('content')
    {{-- Tab list --}}
    @include('uccello::modules.default.detail.tabs')

    <div class="detail-blocks">
        @section('default-tabs')
            {{-- Summary --}}
            @if ($widgets->count() > 0)
            <div id="summary" @if ((empty($selectedTabId) && empty($selectedRelatedlistId) && $widgets->count() > 0) || $selectedTabId === 'summary')class="active"@endif>
                @include('uccello::modules.default.detail.summary')
            </div>
            @endif

            {{-- Tabs and blocks --}}
            @foreach ($module->tabs as $i => $tab)
            <div id="tab_{{ $tab->id }}" @if ((empty($selectedTabId) && empty($selectedRelatedlistId) && $i === 0 && $widgets->count() === 0) || $selectedTabId === $tab->id)class="active"@endif>
                {{-- Blocks --}}
                @include('uccello::modules.default.detail.blocks')

                {{-- Related lists as blocks --}}
                @include('uccello::modules.default.detail.relatedlists.as-blocks')

                {{-- Other blocks --}}
                @if ($i === 0)
                    @yield('other-blocks')
                @endif
            </div>
            @endforeach

            {{-- Related lists as tabs --}}
            @include('uccello::modules.default.detail.relatedlists.as-tabs')

            {{-- Other tabs --}}
            @yield('other-tabs')
        @show
    </div>

    {{-- Action buttons --}}
    @section('page-action-buttons')
        @include('uccello::modules.default.detail.buttons')
    @show
@endsection

@section('extra-content')
    {{-- Relatedlist selection modal --}}
    @include('uccello::modules.default.detail.relatedlists.selection-modal.modal')
@append