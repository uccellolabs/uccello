@section('user-info')
<div class="user-info">
    <div class="image">
        <img src="@section('user-avatar')
        /images/vendor/uccello/user.png
        @show" width="48" height="48" alt="" />
    </div>
    <div class="info-container">
        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@section('user-name')John DOE @show</div>
        <div class="email">@section('user-email')john.doe@domain.tld @show</div>
        
        @section('user-dropdown-menu')
        <div class="btn-group user-helper-dropdown">
            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
            <ul class="dropdown-menu pull-right">
                {{-- <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                <li role="seperator" class="divider"></li>
                <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                <li role="seperator" class="divider"></li> --}}
                <li><a href="{{ route('logout') }}"><i class="material-icons">input</i>{{ uctrans('logout', $module) }}</a></li>
            </ul>
        </div>
        @show
    </div>
</div>
@show