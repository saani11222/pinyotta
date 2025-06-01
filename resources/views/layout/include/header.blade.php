@php
$isHomePage = request()->routeIs(patterns: 'index');
$isDashboard = request()->routeIs('signup', 'save.session', 'howToWork','about' , 'terms' , 'privacy');
$sign_in = request()->routeIs('sign-in');

$param = request()->query('q');

@endphp

@if ($isHomePage)
<div class="header">
    <a href="{{route('sign-in')}}">Sign in</a>
</div>
@elseif($sign_in)
<div class="header">
    <div style="width: 100%"><img height="41px" src="{{asset('assets/img/pinyotta.png')}}" alt=""></div>
</div>
@else
@if (!$isDashboard)

<div class="header home-header">
    <script>
        function markHeaderSeen() {
            fetch('{{route("header.markSeen") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            }).then(res => res.json())
              .then(data => {
                // console.log(data.status);
            });
        }
    </script>
    @if ($hasHeaderDot && request()->routeIs('friends'))
    <script>
        markHeaderSeen();
    </script>
    @endif
    <div class="header_inner">
        <div><img height="41px" src="{{asset('assets/img/pinyotta.png')}}" alt=""></div>
        <div class="tabs">
            <a href="{{route('home' ,['q' => $param])}}" class="tab_link main-tabs-link"><img
                    class="{{request()->routeIs('home') ? 'active_tabs' : '' }}" src="{{asset('assets/img/star.svg')}}"
                    alt="" style="width: 23px;"></a>
            <a href="{{ route('friends', ['q' => $param]) }}" class="tab_link dot main-tabs-link" onclick="markHeaderSeen();"><img
                    class="{{request()->routeIs('friends') ? 'active_tabs' : '' }}"
                    src="{{asset('assets/img/people-fill.svg')}}" alt="">
                @if (@$hasHeaderDot)
                <img class="notification_dot" width="8px" height="8px" src="{{asset('assets/img/dot.png')}}" alt="">
                @endif
            </a>
            <a href="{{route('bookmarks' ,['q' => $param])}}" class="tab_link main-tabs-link"><img
                    class="{{request()->routeIs('bookmarks') ? 'active_tabs' : '' }}"
                    src="{{asset('assets/img/bookmark.svg')}}" alt="" style="margin-top: 2px;"></a>
            <a href="{{route('watch-list' ,['q' => $param])}}" class="tab_link main-tabs-link"><img
                    class="{{request()->routeIs('watch-list') ? 'active_tabs' : '' }}"
                    src="{{asset('assets/img/watchlist.svg')}}" alt="" style="margin-top: 2px;"></a>
            <a href="{{route('shows-loved' ,['q' => $param])}}" class="tab_link main-tabs-link"><img
                    class="{{request()->routeIs('shows-loved') ? 'active_tabs' : '' }}"
                    src="{{asset('assets/img/heart-fill.svg')}}" alt=""
                    style="width: 21px; display: inline-block;margin-top:5px;"></a>
        </div>
        <div class="btn-group">
            <div class="loggedIn" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                @$fullName = Auth::user()->name;
                @$name = strtoupper(substr($fullName, 0, 1));
                @endphp
                {{$name}}
            </div>
            <ul class="dropdown-menu dropdown-menu-end custom-padding">
                <li><a href="{{route('home')}}" class="dropdown-item" type="button"><img width="20px"
                            src="{{asset('assets/img/star.svg')}}" alt="">
                        <span class="dropdown_text">Recommendations</span></a></li>
                <li><a href="{{route('friends')}}" class="dropdown-item" type="button">
                        <img width="20px" src="{{asset('assets/img/people-fill.svg')}}" alt="" style="scale: 1.3;">
                        <span class="dropdown_text">Friends</span></a></li>
                <li><a href="{{route('bookmarks')}}" class="dropdown-item" type="button">
                        <img width="20px" height="23px" src="{{asset('assets/img/bookmark.svg')}}" alt="" style="scale: 0.90">
                        <span class="dropdown_text">Save for later</span></a></li>
                <li><a href="{{route('watch-list')}}" class="dropdown-item" type="button"><img width="20px"
                            src="{{asset('assets/img/watchlist.svg')}}" alt="">
                        <span class="dropdown_text">Queue</span></a></li>
                <li><a href="{{route('shows-loved')}}" class="dropdown-item" type="button"><img width="20px"
                            src="{{asset('assets/img/heart-fill.svg')}}" alt="">
                        <span class="dropdown_text"> Shows I love</span></a></li>
                <li>
                    <div class="dropdown_border_header"></div>
                </li>
                <li><a href="{{route('account')}}" class="dropdown-item dropdown_remove_button" id="account"
                        type="button"> Account</a></li>
                <li>
                    <div class="dropdown_border_header"></div>
                </li>
                <li><a href="{{route('logout')}}" class="dropdown-item dropdown_remove_button" type="button"> Sign
                        out</a></li>
            </ul>
        </div>
    </div>
    {{-- tabl --}}
    <div id="exTab2" style="align-self:center;" class="header_tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item " role="presentation">
                <button class="nav-link {{!@$restaurants && !$param ? 'active': ''}} signup_heading" data-bs-toggle="tab"
                    data-bs-target="#tvshows-{{Route::currentRouteName()}}" type="button" role="tab" onclick="removeQueryParameter()">TV Shows</button>
            </li>

            <li class="nav-item " role="presentation">
                <button class="nav-link {{@$restaurants || $param ? 'active': ''}} signup_heading" data-bs-toggle="tab"
                    data-bs-target="#restaurants-{{Route::currentRouteName()}}" type="button"
                    role="tab" onclick="passQueryParameter('restaurant')" >Restaurants</button>
            </li>
        </ul>
    </div>

</div>
@else
<div class="header">
    <div class="header_inner">
        <div><img height="41px" src="{{asset('assets/img/pinyotta.png')}}" alt=""></div>
        @if(Auth::user())
        <div class="btn-group">
            <div class="loggedIn" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                @$fullName = Auth::user()->name;
                @$name = strtoupper(substr($fullName, 0, 1));
                @endphp
                {{$name}}
            </div>
            <ul class="dropdown-menu dropdown-menu-end custom-padding">
                <li><a href="{{route('home')}}" class="dropdown-item" type="button"><img width="20px"
                            src="{{asset('assets/img/star.svg')}}" alt="">
                        <span class="dropdown_text">Recommendations</span></a></li>
                <li><a href="{{route('friends')}}" class="dropdown-item" type="button">
                        <img width="20px" src="{{asset('assets/img/people-fill.svg')}}" alt="" style="scale: 1.3;">
                        <span class="dropdown_text">Friends</span></a></li>
                <li><a href="{{route('bookmarks')}}" class="dropdown-item" type="button">
                    <img width="20px" height="23px" src="{{asset('assets/img/bookmark.svg')}}" alt="" style="scale: 0.90">
                        <span class="dropdown_text">Save for later</span></a></li>
                <li><a href="{{route('watch-list')}}" class="dropdown-item" type="button"><img width="20px"
                            src="{{asset('assets/img/watchlist.svg')}}" alt="">
                        <span class="dropdown_text">Queue</span></a></li>
                <li><a href="{{route('shows-loved')}}" class="dropdown-item" type="button"><img width="20px"
                            src="{{asset('assets/img/heart-fill.svg')}}" alt="">
                        <span class="dropdown_text"> Shows I love</span></a></li>
                <li>
                    <div class="dropdown_border_header"></div>
                </li>
                <li><a href="{{route('account')}}" class="dropdown-item dropdown_remove_button" id="account"
                        type="button"> Account</a></li>
                <li>
                    <div class="dropdown_border_header"></div>
                </li>
                <li><a href="{{route('logout')}}" class="dropdown-item dropdown_remove_button" type="button"> Sign
                        out</a></li>
            </ul>
        </div>
        @else
        <div><a class="signup_button" href="{{route('sign-in')}}">Sign in</a></div>
        @endif
    </div>
</div>
@endif

@endif