@php
$isHomePage = request()->routeIs(patterns: 'index');
$isDashboard = request()->routeIs('signup', 'save.session', 'howToWork');
$sign_in = request()->routeIs('sign-in');
@endphp

@if ($isHomePage)
<div class="header">
    <a href="{{route('sign-in')}}">Sing In</a>
</div>
@elseif($sign_in)
<div class="header">
    <div style="width: 100%"><img height="41px" src="{{asset('assets/img/pinyotta.png')}}" alt=""></div>
</div>
@else
@if (!$isDashboard)
<div class="header">
    <div class="header_inner">
        <div><img height="41px" src="{{asset('assets/img/pinyotta.png')}}" alt=""></div>
        <div class="tabs">
            <a href="{{route('home')}}" class="tab_link"><img
                    class="{{ request()->routeIs('home') ? 'active_tabs' : '' }}" src="{{asset('assets/img/star.svg')}}"
                    alt=""></a>
            <a href="{{route('friends')}}" class="tab_link dot"><img
                    class="{{ request()->routeIs('friends') ? 'active_tabs' : '' }}"
                    src="{{asset('assets/img/people-fill.svg')}}" alt="">
                <img class="notification_dot" width="8px" height="8px" src="{{asset('assets/img/dot.png')}}" alt="">
            </a>
            <a href="{{route('watch-list')}}" class="tab_link"><img
                    class="{{ request()->routeIs('watch-list') ? 'active_tabs' : '' }}"
                    src="{{asset('assets/img/watchlist.svg')}}" alt=""></a>
            <a href="{{route('shows-loved')}}" class="tab_link"><img
                    class="{{ request()->routeIs('shows-loved') ? 'active_tabs' : '' }}"
                    src="{{asset('assets/img/heart-fill.svg')}}" alt=""></a>
        </div>
        <div class="btn-group">
            <div class="loggedIn" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                @$fullName = Auth::user()->name;
                @$name = strtoupper(substr($fullName, 0, 1));  
                @endphp
                {{$name}}
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a href="{{route('home')}}" class="dropdown-item" type="button"><img width="20px"
                            src="{{asset('assets/img/star.svg')}}" alt="">
                        <span class="dropdown_text">Recommendations</span></a></li>
                <li><a href="{{route('friends')}}" class="dropdown-item" type="button">
                        <img width="20px" src="{{asset('assets/img/people-fill.svg')}}" alt="">
                        <span class="dropdown_text">Friends</span></a></li>
                <li><a href="{{route('watch-list')}}" class="dropdown-item" type="button"><img width="20px"
                            src="{{asset('assets/img/watchlist.svg')}}" alt="">
                        <span class="dropdown_text">Watchlist</span></a></li>
                <li><a href="{{route('shows-loved')}}" class="dropdown-item" type="button"><img width="20px"
                            src="{{asset('assets/img/heart-fill.svg')}}" alt="">
                        <span class="dropdown_text"> Shows I love</span></a></li>
                <li>
                    <div class="dropdown_border"></div>
                </li>
                <li><a href="{{route('account')}}" class="dropdown-item dropdown_remove_button" id="account"
                        type="button"> Account</a></li>
                <li>
                    <div class="dropdown_border"></div>
                </li>
                <li><a href="{{route('logout')}}" class="dropdown-item dropdown_remove_button" type="button"> Sign out</a></li>
            </ul>
        </div>
    </div>
</div>
@else
<div class="header">
    <div class="header_inner">
        <div><img height="41px" src="{{asset('assets/img/pinyotta.png')}}" alt=""></div>
        <div><a class="signup_button" href="{{route('sign-in')}}">Sing In</a></div>
    </div>
</div>
@endif

@endif