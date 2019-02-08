@if (Auth::user()->canRetrieve($domain, $relatedModule))
    <div class="selection-modal-content">
        <div class="row">
            <div class="col-md-12">
                {{-- Table --}}
                @include('uccello::modules.default.edit.entity-modal.table')
            </div>
        </div>

        {{-- Paginator --}}
        <div class="paginator"></div>
    </div>
@endif