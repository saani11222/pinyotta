@extends('layout.master')
@section('content')
@php
$param = request()->query('q');
@endphp
<div style="display: flex;justify-content:center;flex-direction: column;align-items: center;">
    <div class="signup_page dashboard">
        <div class="signup_text">
            <div class="signup_heading">Saved</div>
            {{-- <div class="signupText pad-10-15">What’s up next</div> --}}
        </div>
        <div class="tab-content" id="myTabContent" style="width: 100%">
            <div class="tab-pane fade {{@!$param ? 'show active' : '' }}" id="tvshows-{{Route::currentRouteName()}}" role="tabpanel">

                {{-- <div style="width: 100%">
                    <a href="{{route('add-show-watchlist')}}" type="button" class="add_more_show btn_bg_blue">Add a
                        show</a>
                </div> --}}
                @if ($saved && $saved->isNotEmpty())
                <div style="width: 100%">
                    <div style="width:100%" class="recommendation_box">
                        @foreach ( $saved as $bookmark )
                        <div class="selected_item_box content_show_box watch_list_box"
                            id="selected_item_box{{@$bookmark->show_id}}">
                            <img class="border-left" width="40px" src="{{@$bookmark->image}}" alt="">
                            <div class="itemBox">
                                @php
                                // parsing the year
                                @$input = $bookmark->genres;
                                @$parts = array_map('trim', explode(',', $input));
                                @$year = preg_match('/^\d{4}$/', $parts[0]) ? array_shift($parts) : null;

                                @$url = 'http://www.google.com/search?q=tv+'.$bookmark->name.' '.$year;
                                @$url = str_replace(' ', '+', strtolower(trim($url)))

                                @endphp
                                <div class="item_name " style="color: #0B99FF;line-height: 1.5;">
                                    <a class="google_search substr_text " target="_blank" href="{{$url}}">
                                        {{@$bookmark->name}}
                                    </a>

                                    <div class="substr_text"
                                        style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
                                        {{@$bookmark->genres}}
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">Actions
                                        <img width="10px" height="10px" src="{{asset('assets/img/chevron-down.png')}}"
                                            alt="" style="cursor: pointer">
                                    </div>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item makeWatchlist"
                                                data-id="{{@$bookmark->show_id}}" data-type="{{$bookmark->type}}"
                                                type="button">
                                                <img width="20px" src="{{asset('assets/img/watchlist.svg')}}" alt="">
                                                <span class="dropdown_text">Move to Queue</span>
                                            </button></li>
                                        <li><button class="dropdown-item move_show_to_i_love" type="button"
                                                data-id="{{@$bookmark->show_id}}" data-type="{{$bookmark->type}}">
                                                <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}"
                                                    alt=""><span class="dropdown_text"> Move to Shows I love</span>
                                            </button></li>
                                        <li>
                                            <div class="dropdown_border"></div>
                                        </li>
                                        <li><button class="dropdown-item dropdown_remove_button remove_show"
                                                data-type="{{$bookmark->type}}" data-id="{{@$bookmark->show_id}}"
                                                type="button"> Remove</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div style="width:100%; text-align: center;">
                    <div class="no_list">No shows saved.</div>
                    <div class="no_list_other_text">You can save shows from
                        <span><a href="{{route('home')}}">Recommendations</a></span> and
                        <span><a href="{{route('friends')}}">Friends.</a></span>
                    </div>
                </div>
                @endif
            </div>
            {{-- restaurants --}}
            <div class="tab-pane fade {{@$param ? 'show active' : '' }}" id="restaurants-{{Route::currentRouteName()}}" role="tabpanel">
                <div class="saved_restaurants">
                    @if($savedRestaurants && $savedRestaurants->isNotEmpty())
                    @foreach ($savedRestaurants as $restaurant )
                    <div class="selected_item_box content_show_box" id="saved_restaurants{{@$restaurant->id}}">
                        <div class="itemBox restBox">
                            <div class="item_name" style="color: #0B99FF;line-height: 1.5;">
                                <div class="rest_name">
                                    <div class="item_name substr_text">
                                    <a class="google_search substr_text " target="_blank" href="http://www.google.com/search?q=restaurant+{{ urlencode($restaurant->name . ' ' . $restaurant->city) }}">
                                                {{@$restaurant->name }}
                                        </a>
                                    </div>
                                    <div class="item_name_gray substr_text">{{@$restaurant->city}}</div>
                                </div>
                            </div>
                            <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">Actions <img
                                    width="10px" height="10px" src="{{asset('assets/img/chevron-down.png')}}" alt=""
                                    style="cursor: pointer"></div>
                            <ul class="dropdown-menu dropdown-menu-end">

                                <li><button class="dropdown-item "
                                        onclick="submitajaxRequest({{$restaurant->id}} , 'restaurant-move-to-queue','.saved_restaurants')"
                                        type="button">
                                        <img width="20px" src="{{asset('assets/img/watchlist.svg')}}" alt=""> <span
                                            class="dropdown_text">Move to Queue</span>
                                    </button></li>

                                <li><button class="dropdown-item "
                                        onclick="submitajaxRequest({{$restaurant->id}} , 'restaurant-move-to-love','.saved_restaurants')"
                                        type="button">
                                        <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}" alt=""><span
                                            class="dropdown_text"> Move to Restaurant I love</span>
                                    </button></li>
                                <li>
                                    <div class="dropdown_border"></div>
                                </li>
                                <li><button class="dropdown-item dropdown_remove_button" type="button"
                                        onclick="submitajaxRequest({{$restaurant->id}} , 'restaurant-remove-from-bookmark','.saved_restaurants')">
                                        Remove</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                @if(!$isRestaurantsAdded)
                <div class="rest_info">
                    <div style="color: black;font-weight:600;font-size:14px;text-align:center;">
                        Once you add restaurants you love you’ll see your saved restaurants here.
                    </div>
                    <div style="width:100%">
                          <a href="{{route('shows-loved' ,['q' => 'restaurant'])}}" class="add_more_show btn_bg_blue ">
                            Add restaurants I love
                        </a>
                    </div>
                </div>
                @elseif($isRestaurantsAdded)
                <div style="width:100%; text-align: center;">
                    <div class="no_list">No restaurants saved.</div>
                    <div class="no_list_other_text">You can save restaurants from
                        <span><a href="{{route('home',['q' => 'restaurant'])}}">Recommendations</a></span> and
                        <span><a href="{{route('friends',['q' => 'restaurant'])}}">Friends.</a></span>
                    </div>
                </div>
                @endif

                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function submitajaxRequest(id, type,targetElement){
        
            var id = id;
            var type = type;
            let count = $('.saved_restaurants').find('.selected_item_box').length-1;
            var url = "{{route('addRestaurantToQueue')}}";
            var message = 'Successfully Updated!';
            var html = `
                        <div style="width:100%; text-align: center;">
                            <div class="no_list">No restaurants saved.</div>
                            <div class="no_list_other_text">ou can save restaurants from
                                <span><a href="{{route('home',['q' => 'restaurant'])}}">Recommendations</a></span> and
                                <span><a href="{{route('friends',['q' => 'restaurant'])}}">Friends.</a></span>
                            </div>
                        </div>
                        `;

            var removeItem = $('#saved_restaurants'+id);
            var htmlElement = $(targetElement);
            dropdownAjaxRequest(url,id,count,message,html,removeItem,type,htmlElement);
    }
    $(document).ready(function () {
        // remove the show
        $(document).on('click', '.remove_show', function(){
            var id = $(this).data('id');
            var type = $(this).data('type');
            let count = $('.recommendation_box').find('.selected_item_box').length-1;
            var url = "{{route('removeShow')}}";
            var message = 'Successfully Removed!';
            var html = `
                <div style="width:100%; text-align: center;">
                    <div class="no_list">No shows saved.</div>
                    <div class="no_list_other_text">You can save shows from
                        <span><a href="{{route('home')}}">Recommendations</a></span> and
                        <span><a href="{{route('friends')}}">Friends.</a></span>
                    </div>
                </div>
            `;
            var removeItem = $('#selected_item_box'+id);
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem,type);
        });

        $(document).on('click', '.move_show_to_i_love', function(){
            var id = $(this).data('id');
            var type = $(this).data('type');
            let count = $('.recommendation_box').find('.selected_item_box').length-1;
            var url = "{{route('moveToShowsLove')}}";
            var message = 'Successfully Updated!';
            var html = `
                 <div style="width:100%; text-align: center;">
                    <div class="no_list">No shows saved.</div>
                    <div class="no_list_other_text">You can save shows from
                        <span><a href="{{route('home')}}">Recommendations</a></span> and
                        <span><a href="{{route('friends')}}">Friends.</a></span>
                    </div>
                </div>
            `;
            var removeItem = $('#selected_item_box'+id);
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem,type);
        });

        $(document).on('click', '.makeWatchlist', function(){
            var id = $(this).data('id');
            var type = $(this).data('type');
            let count = $('.recommendation_box').find('.selected_item_box').length-1;
            var url = "{{route('moveToWatchlistFromAiRecs')}}";
            var message = 'Successfully Updated!';
            var html = `
                 <div style="width:100%; text-align: center;">
                    <div class="no_list">No shows saved.</div>
                    <div class="no_list_other_text">You can save shows from
                        <span><a href="{{route('home')}}">Recommendations</a></span> and
                        <span><a href="{{route('friends')}}">Friends.</a></span>
                    </div>
                </div>
            `;
            var removeItem = $('#selected_item_box'+id);
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem,type);
        });
        
    });
</script>
@endsection