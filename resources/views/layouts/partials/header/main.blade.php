<div class="navbar-header navbar-fixed">
    <nav class="header">
        <div class="nav-wrapper">
            <ul class="left">
                <li>
                    <a href="#" class="sidenav-trigger" data-target="sidenav-menu" style="margin-left: 0">
                        <i class="material-icons right">menu</i>
                    </a>
                </li>
            </ul>

            <a class="brand-logo" href="/" style="padding: 7px; max-height: 50px">{{ Html::image(ucasset('images/logo-uccello-white.png'), null, ['style' => 'max-width: 150px;']) }}</a>

            {{-- Display current domain name and a link to open domains list --}}
            @if (uccello()->useMultiDomains() && isset($domain))
            <ul class="right">
                <li>
                    <a href="#" class="sidenav-trigger show-on-large" data-target="sidenav-domains" style="margin-right: 0">
                        <span class="hide-on-small-only">{{ $domain->name }}</span>
                        <i class="material-icons right">arrow_drop_down</i>
                    </a>
                </li>
            </ul>
            @endif
        </div>
    </nav>
</div>

@section('navbar-top')
<header class="navbar-fixed navbar-top">
    <nav class="transparent z-depth-0">
        <div class="row">
            <div class="col s12 m8 l6">
                @section('breadcrumb')&nbsp;@show
            </div>
            <div class="col s12 m4 l6 hide-on-small-only">
                @section('top-action-buttons')&nbsp;@show
            </div>
        </div>
    </nav>
</header>
@show