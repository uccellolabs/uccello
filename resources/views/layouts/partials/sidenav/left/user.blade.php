@section("user-info")
<li class="user">
    <div class="user-info">
        <div class="row valign-wrapper">
            <div class="col s3 user-image">
                @section('user-avatar')
                    @if (in_array(auth()->user()->avatarType, [ 'image', 'gravatar' ]))
                        {{-- Image or Gravatar --}}
                        <img src="{{ auth()->user()->image }}" alt="" class="circle responsive-img">
                    @else
                        {{-- Initials --}}
                        <div class="circle user-initials">{{ auth()->user()->initials }}</div>
                    @endif
                @show
            </div>
            <div class="col s9 dropdown-trigger" data-target="dropdown-user" data-hover="true">
                <a href="javascript:void(0)"><span class="name">@section('user-name')John Doe @show</span></a><br>
                <a href="javascript:void(0)"><span class="email">@section('user-email')john@doe.com @show</span></a>
                <a href="javascript:void(0)"><i class="material-icons right">arrow_drop_down</i></a>
            </div>
        </div>

        <ul id="dropdown-user" class="dropdown-content">
            <li>
                <a href="{{ ucroute('uccello.user.account', $domain) }}">
                    <i class="material-icons">person</i>
                    {{ uctrans('button.user_account', $module) }}
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}">
                    <i class="material-icons">logout</i>
                    {{ uctrans('button.logout', $module) }}
                </a>
            </li>
        </ul>
    </div>
</li>
@show