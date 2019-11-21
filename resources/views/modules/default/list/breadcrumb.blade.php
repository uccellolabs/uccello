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

            {{-- Filters list --}}
            <span class="breadcrumb" href="#">
                <a class="dropdown-trigger btn-flat" href="#" data-target="filters-list" data-constrain-width="false">
                    <i class="material-icons right">arrow_drop_down</i>
                    <span class="primary-text">{{ $displayTrash ? uctrans('filter.trash', $module) : uctrans($selectedFilter->name, $module) }}</span>
                </a>
                <ul id="filters-list" class="dropdown-content">
                    @foreach ($filters as $filter)
                    <li @if(!$displayTrash && $filter->id === $selectedFilter->id)class="active"@endif><a href="{{ ucroute('uccello.list', $domain, $module, ['filter' => $filter->id]) }}" data-name="{{ $filter->name }}">{{ uctrans($filter->name, $module) }}</a></li>
                    @endforeach

                    @if ($usesSoftDeleting)
                    <li @if($displayTrash)class="active"@endif><a href="{{ ucroute('uccello.list', $domain, $module, ['filter' => 'trash']) }}" data-name="filter.trash">{{ uctrans('filter.trash', $module) }}</a></li>
                    @endif
                </ul>
            </span>

            {{-- Filters management --}}
            <a class="dropdown-trigger btn-floating btn-small waves-effect green z-depth-0 hide-on-small-only" href="#" data-target="filters-management" data-constrain-width="false"  data-position="top" data-tooltip="{{ uctrans('button.manage_filters', $module) }}">
                <i class="material-icons">filter_list</i>
            </a>
            <ul id="filters-management" class="dropdown-content">
                <li>
                    <a href="#addFilterModal" class="add-filter modal-trigger">
                        {{-- <i class="material-icons left">add</i> --}}
                        {{ uctrans('button.add_filter', $module) }}
                    </a>
                </li>

                <li @if ($selectedFilter->readOnly)style="display: none"@endif>
                    <a href="javascript:void(0)" class="delete-filter">
                        {{-- <i class="material-icons left">delete</i> --}}
                        {{ uctrans('button.delete_filter', $module) }}
                    </a>
                </li>
            </ul>

            {{-- Export --}}
            <a href="#exportModal" class="btn-floating btn-small waves-effect primary z-depth-0 modal-trigger hide-on-small-only" data-position="top" data-tooltip="{{ uctrans('button.export', $module) }}">
                <i class="material-icons">cloud_download</i>
            </a>

            {{-- Link to Tree View --}}
            @if (app('uccello')->isTreeModule($module))
            <a href="{{ ucroute('uccello.tree', $domain, $module) }}"
                class="btn-floating btn-small waves-effect orange z-depth-0 hide-on-small-only"
                data-position="top"
                data-tooltip="{{ uctrans('button.treeview', $module) }}">
                    <i class="material-icons">account_tree</i>
            </a>
            @endif
        </div>
    </div>
</div>