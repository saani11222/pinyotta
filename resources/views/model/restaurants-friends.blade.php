@extends('layout.model')
@section('model_content')
<div>
    <div class="model_friends_show_list_top">
        <div class="model_firend_show">
            <div style="padding: 12px" class="loggedIn">{{strtoupper(substr(@$user->name, 0, 1))}}</div>
            <div class="friend_name" style="line-height: 1.5;">{{@$user->name}}</div>
        </div>
        <div>
            <div class="btn-group">
                <div class="three_dots" data-bs-toggle="dropdown" aria-expanded="false"><img height="3px"
                        src="{{asset('assets/img/ellipsis.png')}}" alt=""> </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><button class="dropdown-item " id="unfriend" data-id="{{@$user->id}}" type="button">Unfriend</button></li>
                </ul>
            </div>
        </div>
    </div>
    <div style="width: 100%">
        <div style="width:100%" class="over_flow">
            @foreach ($restaurants ?? [] as $restaurant )
            <div class="selected_item_box content_show_box" >
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
                            <li><button class="dropdown-item makeWatchlist" data-id="{{$restaurant->id}}"
                                 type="button">
                                    <img width="20px" src="{{asset('assets/img/watchlist.svg')}}" alt=""> <span
                                        class="dropdown_text">Add to Queue</span>
                                </button></li>

                            <li><button class="dropdown-item ai_rec_move_to_bookmarks" data-id="{{$restaurant->id}}"
                                 type="button">
                                    <img width="20px" height="23px"
                                        src="{{asset('assets/img/bookmark.svg')}}" alt=""style="scale: 0.90">
                                    <span class="dropdown_text">Add to Bookmarks</span></button></li>

                            <li><button  class="dropdown-item makeShowILove" data-id="{{$restaurant->id}}" 
                                     type="button">
                                    <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}" alt=""><span
                                        class="dropdown_text"> Add to Restaurant I love</span>
                                </button></li>
                        </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@section('model_script')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.makeWatchlist', function(){
            var id = $(this).data('id');
            var type = 'restaurant-add-to-queue';
            let count = '';
            var url = "{{route('addRestaurantToQueue')}}";
            var message = 'Successfully Updated!';
            var html = '';
            var removeItem = '';
            
            dropdownAjaxRequest(url,id,count,message,html,removeItem,type);
        });
        
        // for Bookmarks
        $(document).on('click', '.ai_rec_move_to_bookmarks', function(){
            var id = $(this).data('id');
            var type = 'restaurant-add-to-bookmark';
            let count = '';
            var url = "{{route('addRestaurantToQueue')}}";
            var message = 'Successfully Updated!';
            var html = '';
            var removeItem = '';
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem ,type);
        });
        // for shows move to section show i love 
        $(document).on('click', '.makeShowILove', function(){
            // $('#loaderContainer').addClass('loading');
            var id = $(this).data('id');
            var type = 'restaurant-add-to-love';
            let count = '';
            var url = "{{route('addRestaurantToQueue')}}";
            var message = 'Successfully Updated!';
            var html = '';
            var removeItem = '';
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem,type);
        });

        $(document).on('click', '#unfriend', function(){
            var id = $(this).data('id');
            let count = '';
            var url = "{{route('unfriend')}}";
            var message = 'Unfriend Successfully!';
            var html = '';
            var removeItem = '';
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem);
        });
        });
    </script>
@endsection