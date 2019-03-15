<div class="navbar-header navbar-fixed">
    <nav class="header">
        <div class="nav-wrapper">
            <a class="brand-logo" href="/" style="padding: 7px">{{ Html::image(ucasset('images/logo-uccello-white.png'), null, ['style' => 'width: 150px']) }}</a>

            {{-- Display current domain name and a link to open domains list --}}
            @if (uccello()->useMultiDomains() && isset($domain))
            <ul class="right">
                <li>
                    <a class="dropdown-trigger" href="#" data-target="dropdown-domains">
                        {{ $domain->name }}
                        <i class="material-icons right">arrow_drop_down</i>
                    </a>
                </li>
            </ul>

            <ul id="dropdown-domains" class="dropdown-content">
                @foreach(uccello()->getRootDomains() as $_domain)
                    <li>
                        <a href="#!">{{ $_domain->name }}</a>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
    </nav>
</div>

<header class="navbar-fixed navbar-top">
    <nav class="transparent z-depth-0">
        <div class="row">
            <div class="col s12 m6 l4">
                @section('breadcrumb')&nbsp;@show
            </div>
            <div class="col s12 m6 l8">
                @section('top-action-buttons')&nbsp;@show
            </div>
        </div>
    </nav>
</header>