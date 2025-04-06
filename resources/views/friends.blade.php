
@extends('layout.master')
@section('content')
<div style="display: flex;justify-content:center">
    <div class="signup_page dashboard">
        <div class="signup_text">
            <div class="signup_heading">Friends</div>
            <div class="signupText">See what shows your friends love.</div>
        </div>
        <div style="width: 100%">
            <div style="width:100%">
                @if ($friends)
                @foreach ($friends as $friend )
                <a href="{{route('friendsShowsList', ['id' => @$friend->id] )}}" class="goto_friends_list">
                <div class="selected_item_box friends_list" id="selected_item_box"
                    style="background: transparent;align-items:center;cursor: pointer;">
                    <div class="notification_dot_in_friends">
                        <img width="8px" height="8px" src="{{asset('assets/img/dot.png')}}" alt="">
                    </div>
                    <div style="padding: 12px" class="loggedIn">
                        {{ strtoupper(substr(@$friend->name, 0, 1)) }}

                    </div>
                    <div class="itemBox">
                        <div class="friend_name" style="line-height: 1.5;">{{@$friend->name}}
                           
                            <div style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
                                {{count(@$friend->shows)}} shows,
                                updated {{ \Carbon\Carbon::parse(@$friend->latest_show->updated_at)->format('n/j/y') }}
                            </div>
                           
                            
                        </div>
                        <div class="action_anchr" id="friendShowslist">
                            <img width="7px" height="10px" src="{{asset('assets/img/path.png')}}" alt=""
                                style="cursor: pointer">
                        </div>
                    </div>
                </div></a>
                @endforeach
                {{-- PENDING REQUESTS --}}
                <div class="selected_item_box" id="pending_request" style="background: transparent;display:block;">
                    <div class="pending_request" style="display: flex;">
                        <div class="notification_dot_in_friends"></div>
                        <div style="padding: 12px" class="loggedIn pending_">S</div>
                        <div class="itemBox">
                            <div class="friend_name" style="line-height: 1.5;color: #8B8B8B;">Ella Montgomery (pending)
                            </div>
                            <div class="action_anchr">
                                <img width="10px" height="10px" src="{{asset('assets/img/x.png')}}" alt=""
                                    style="cursor: pointer">
                            </div>
                        </div>
                    </div>
                    <div class="re_invite_box">
                        <button class="re_invite" type="button">Re-share invite</button>
                    </div>
                </div>
                @else
                <div style="width:100%; text-align: center;">
                    <div class="no_list" >No friends yet.</div> 
                    <div class="no_list_other_text">When your friends join, you'll be able to see and share recommendations with them. Invite friends now!</div>
                </div> 
                @endif

                {{-- add friend button --}}
                <div>
                    <a href="{{route('add-friend')}}" type="button" class="add_friend_button btn_bg_blue" style="margin-top: 25px;">Add friend</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection







