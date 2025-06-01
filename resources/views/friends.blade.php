@extends('layout.master')
@section('content')
@php
$param = request()->query('q');
@endphp
<script>
    function markFriendsShowsSeen(friendId, type) { 
        fetch('{{ route("friendShowsMarkAsSeen") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                friend_id: friendId,
                type: type
            })
        })
        .then(res => res.json())
        .then(data => {
            // console.log(data.status);
        });
    }
</script>
{{-- tvshow tab --}}
<div style="display: flex;justify-content:center">
    <div class="signup_page dashboard">
        {{-- tab divs --}}
        <div class="tab-content" id="myTabContent" style="width: 100%">
            <div class="tab-pane fade {{@!$param ? 'show active' : '' }}" id="tvshows-{{Route::currentRouteName()}}" role="tabpanel">
                <div class="signup_text">
                    <div class="signup_heading">Friends</div>
                    <div class="signupText pad-10-15">See what shows your friends love.</div>
                </div>
                <div style="width: 100%">
                    <div style="width:100%">
                        {{-- add friend button --}}
                        <div style="width: 100%" class="pd_bt_30">
                            <a href="{{route('add-friend')}}" class="add_friend_button btn_bg_blue ">Add friend</a>
                        </div>

                        @if ($friends)
                        
                        
                        @foreach ($friends as $friend )
                        <a @if($friend->shows_count > 0)  
                            onclick="markFriendsShowsSeen({{$friend->id}} , 'shows')"
                            href="{{route('friendsShowsList', ['id' => @$friend->id] )}}" @endif 
                            class="goto_friends_list">
                            <div class="selected_item_box friends_list" id="selected_item_box"
                                style="background: transparent;align-items:center;">
                                <div class="notification_dot_in_friends">
                                    @php
                                    $notificationBadge = $hasNotificationBadge[$friend->id] ?? true
                                    @endphp
                                @if ($friend->shows_count > 0)
                                    @if(!$notificationBadge)
                                    <img width="8px" height="8px" src="{{asset('assets/img/dot.png') }}" alt="">
                                    @endif
                                @endif
                                    

                                </div>

                                <div style="padding: 12px" class="loggedIn">
                                    {{ strtoupper(substr(@$friend->name, 0, 1)) }}

                                </div>
                                <div class="itemBox">
                                    <div class="friend_name" style="line-height: 1.5;">{{@$friend->name}}
                                        @php
                                        $updatedDate = $friend->lastShowUpdatedAt() ?? null;
                                        @endphp
                                        <div style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">

                                             @if ($friend->shows_count > 0)
                                            {{@$friend->shows_count}} shows,
                                                @if($updatedDate)
                                                updated {{\Carbon\Carbon::parse($updatedDate)->format('n/j/y') }}
                                                @else
                                                updated N/A
                                                @endif
                                            @else
                                            No Shows yet
                                            @endif

                                        </div>
                                    </div>
                                    @if($friend->shows_count > 0)
                                    <div class="action_anchr" id="friendShowslist" style="cursor: {{ $friend->shows_count > 0 ? 'pointer' : 'default' }}" >
                                        <img width="7px" height="10px" src="{{asset('assets/img/path.png')}}" alt="">
                                    </div>    
                                    @endif
                                    
                                </div>
                            </div>
                        </a>
                        @endforeach
                        @else
                        <div style="width:100%; text-align: center; margin-bottom:18px;">
                            <div class="no_list">No friends yet.</div>
                            <div class="no_list_other_text">When your friends join, you'll be able to see and share
                                recommendations with them.</div>
                        </div>
                        @endif
                        {{-- PENDING REQUESTS --}}

                        @if(@$pendingRequest)
                        <div id="pending_request_main">
                            @foreach ($pendingRequest as $pendingRequests )
                            <div class="selected_item_box pending_request_main"
                                id="pending_request{{$pendingRequests->id}}"
                                style="background: transparent;display:block;">
                                <div class="pending_request" style="display: flex;">
                                    <div class="notification_dot_in_friends"></div>
                                    <div style="padding: 12px" class="loggedIn pending_">
                                        {{strtoupper(substr($pendingRequests->name
                                        ,0 ,1))}}</div>
                                    <div class="itemBox">
                                        <div class="friend_name" style="line-height: 1.5;color: #8B8B8B;">
                                            {{ucwords($pendingRequests->name)}} (pending)
                                        </div>
                                        <div class="action_anchr  "
                                            style="margin-top:-35px;padding: 10px;margin-right:-10px;">
                                            <img class="remove_request" data-id="{{$pendingRequests->id}}" width="10px"
                                                height="10px" src="{{asset('assets/img/x.png')}}" alt=""
                                                style="cursor: pointer">
                                        </div>
                                    </div>
                                </div>
                                <div class="re_invite_box">
                                    <button class="re_invite re_share_invite" data-name="{{$pendingRequests->name}}"
                                        data-token="{{$pendingRequests->random_token}}" type="button">Regenerate invite
                                        link</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- restaurants --}}
            <div class="tab-pane fade {{@$param ? 'show active' : '' }}" id="restaurants-{{Route::currentRouteName()}}" role="tabpanel">
                <div class="signup_text">
                    <div class="signup_heading">Friends</div>
                    <div class="signupText pad-10-15">See what restaurants your friends love.</div>
                </div>
                {{-- if no restaurant added --}}
                @if (!$youHaveRestaurant)
                <div class="rest_info">
                    <div>
                        {{-- if friends have restaurant and you haven't --}}
                        @php
                        if(!is_null($restaurant_Added_Friends)){
                        $count = count($restaurant_Added_Friends ?? []);
                        $firstFriend = @$restaurant_Added_Friends[0]->name ?? '';
                        $secondFriend = @$restaurant_Added_Friends[1]->name ?? '';
                        }else{
                        $count = count($friends ?? []);
                        $firstFriend = @$friends[0]->name ?? '';
                        $secondFriend = @$friends[1]->name ?? '';
                        }
                        @endphp

                        <div style="font-weight:500;font-size:14px; text-align:center;">
                            @if ($count === 1)
                            <span style="font-size: 14px;font-weight:700;">{{ $firstFriend }}</span>
                            @elseif ($count === 2)
                            <span style="font-size: 14px;font-weight:700;">{{ $firstFriend }}</span> and <span
                                style="font-size: 14px;font-weight:700;">{{ $secondFriend }}</span>
                            @elseif ($count > 2)
                            <span style="font-size: 14px;font-weight:700;">{{ $firstFriend }}, {{ $secondFriend
                                }}</span> + {{ $count - 2 }}
                            <span style="font-size: 14px;font-weight:700;">other friend{{ $count - 2 > 1 ? 's' : ''
                                }}</span>
                            @endif
                            @if ($firendsHaveRestaurant && !$youHaveRestaurant && $count > 0)
                            has shared restaurants with you!
                            @elseif(!$firendsHaveRestaurant && !$youHaveRestaurant && $count > 0)
                            would love to see your restaurants!
                            @endif
                        </div>


                        <div style="font-size:14px;text-align:center;font-weight:500;">
                            Add restaurants you love to see restaurants your friends love.
                        </div>
                    </div>
                    <div style="width:100%">
                      <a href="{{route('shows-loved' ,['q' => 'restaurant'])}}" class="add_more_show btn_bg_blue ">
                            Add restaurants I love
                        </a>
                    </div>
                </div>
                {{-- if you restaurant added --}}
                @elseif($youHaveRestaurant)
                <div>
                    <div style="width: 100%" class="pd_bt_30">
                        <a href="{{ route('add-friend', ['type' => 'restaurant']) }}" class="add_friend_button btn_bg_blue ">Add friend</a>
                    </div>
                    @if ($friends)
                    @foreach ($friends as $friend )
                    <a @if($friend->restaurants_count > 0) onclick="markFriendsShowsSeen({{$friend->id}} , 'restaurant')"  href="{{route('friendsRestaurantList', ['id' => @$friend->id] )}}  @endif " class="goto_friends_list"
                         style="cursor: {{ $friend->restaurants_count > 0 ? 'pointer' : 'default' }}" >
                        <div class="selected_item_box friends_list" id="selected_item_box"
                            style="background: transparent;align-items:center;">
                            <div class="notification_dot_in_friends">
                                @php
                                $notificationBadge = $restaurantNotification[$friend->id] ?? true
                                @endphp
                                @if($friend->restaurants_count > 0)
                                    @if(!$notificationBadge)
                                    <img width="8px" height="8px" src="{{asset('assets/img/dot.png') }}" alt="">
                                    @endif
                                @endif
                                
                            </div>

                            <div style="padding: 12px" class="loggedIn">
                                {{ strtoupper(substr(@$friend->name, 0, 1)) }}

                            </div>
                            <div class="itemBox">
                                <div class="friend_name" style="line-height: 1.5;">{{@$friend->name}}
                                    @php
                                    $updatedDate = $friend->lastShowUpdatedAt() ?? null;
                                    @endphp
                                    <div style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
                                        @if ($friend->restaurants_count > 0)
                                        {{@$friend->restaurants_count}} restaurants,
                                        @if($updatedDate)
                                        updated {{\Carbon\Carbon::parse($updatedDate)->format('n/j/y') }}
                                        @else
                                        updated N/A
                                        @endif
                                        @else
                                        No restaurants yet
                                        @endif
                                    </div>
                                </div>
                                @if($friend->restaurants_count > 0)
                                    <div class="action_anchr" id="friendShowslist" style="cursor: {{ $friend->restaurants_count > 0 ? 'pointer' : 'default' }}">
                                        <img width="7px" height="10px" src="{{asset('assets/img/path.png')}}" alt="">
                                    </div>    
                                @endif
                                
                            </div>
                        </div>
                    </a>
                    @endforeach
                    {{-- @else
                    <div style="width:100%; text-align: center; margin-bottom:18px;">
                        <div class="no_list">No friends yet.</div>
                        <div class="no_list_other_text">When your friends join, you'll be able to see and share
                            recommendations with them.</div>
                    </div> --}}
                    @endif
                </div>
                @endif

            </div>

        </div>
    </div>
</div>


@endsection
@section('script')
<script>
    $(document).ready(function () {
        $(document).on('click','.remove_request', function(){
            let id = $(this).data('id');
            var removeItem = $('#pending_request'+id);
            var url = "{{route('removeRequest')}}";
            var message = 'Successfully Removed!';
            var html = '';
            var count= '';
            dropdownAjaxRequest(url,id,count,message,html,removeItem);
        });
        // re share the invitation link
        function copyToClipboard(text) {
            const tempInput = $('<textarea>'); // Create a temporary textarea
            $('body').append(tempInput);      // Append to the body
            tempInput.val(text).select();     // Set value and select it
            document.execCommand('copy');     // Copy to clipboard
            tempInput.remove();               // Remove the textarea
            }
        $(document).on('click', '.re_share_invite', function(){
            let name = $(this).data('name');
            let token =$(this).data('token');
            // Hey ${name.split(" ")[0]},
            var text = `I’m using Pinyotta to discover TV shows and I wanted to invite you to be a friend so we can share shows we love with each other!\nhttps://pinyotta.com?fid=${token}`;
            copyToClipboard(text);
            $(this).html('Invitation link copied to clipboard!');            
        });
    });
    
</script>

@endsection