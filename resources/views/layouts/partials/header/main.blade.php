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

<header class="navbar-fixed">
    <nav class="navbar-breadcrumb ">
        <div class="nav-wrapper">
            <div class="col s12">
                <a href="#!" class="breadcrumb">First</a>
                <a href="#!" class="breadcrumb">Second</a>
                <a href="#!" class="breadcrumb">Third</a>
            </div>
        </div>
    </nav>
</header>