@section("user-info")
<li class="user">
    <div class="user-info">
        <div class="row valign-wrapper">
            <div class="col s3 user-image">
                <img src="@section('user-avatar')https://materializecss.com/images/yuna.jpg  @show" alt="" class="circle responsive-img valign">
            </div>
            <div class="col s9 dropdown-trigger" data-target="dropdown-user">
                <a href="#"><span class="name">@section('user-name')John Doe @show</span></a><br>
                <a href="#"><span class="email">@section('user-email')jdandturk@gmail.com @show</span></a>
                <a href="#"><i class="material-icons right">arrow_drop_down</i></a>
            </div>
        </div>

        <ul id="dropdown-user" class="dropdown-content">
            <li>
                <a href="{{ route('logout') }}">
                    <i class="material-icons">logout</i>
                    {{ uctrans('logout', $module) }}
                </a>
            </li>
        </ul>
    </div>
</li>
@show