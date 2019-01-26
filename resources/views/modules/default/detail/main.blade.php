@extends('layouts.app')

@section('page', 'detail')

@section('extra-meta')
<meta name="record" content="{{ $record->id }}">
@endsection

@section('content')

    @section('breadcrumb')
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="breadcrumb pull-left">
                {{-- Module icon --}}
                <a href="{{ ucroute('uccello.list', $domain, $module) }}" class="pull-left module-icon">
                    <i class="material-icons">{{ $module->icon ?? 'extension' }}</i>
                </a>

                <ol class="breadcrumb pull-left">
                    @if ($admin_env)<li><a href="{{ ucroute('uccello.settings.dashboard', $domain) }}">{{ uctrans('breadcrumb.admin', $module) }}</a></li>@endif
                    <li><a href="{{ ucroute('uccello.list', $domain, $module) }}">{{ uctrans($module->name, $module) }}</a></li>
                    <li class="active">{{ $record->recordLabel ?? $record->getKey() }}</li>
                </ol>
            </div>
        </div>

        {{-- Custom links --}}
        @section ('custom-links')
            @include('uccello::modules.default.detail.links')
        @show
    </div>
    @show

    {{-- Tab list --}}
    @include('uccello::modules.default.detail.tabs')

    <div class="detail-blocks">
        @section('default-blocks')
            <div class="tab-content">
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
            </div>
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