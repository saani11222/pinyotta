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
            @foreach ($shows ?? [] as $show )
            <div class="selected_item_box content_show_box" >
                <img class="border-left" width="40px" src="{{@$show->image}}" alt="">
                <div class="itemBox">
                    <div class="item_name" style="color: #0B99FF;line-height: 1.5;">
                        @php
                        // parsing the year
                        $input = $show->genres;
                        $parts = array_map('trim', explode(',', $input));
                        $year = preg_match('/^\d{4}$/', $parts[0]) ? array_shift($parts) : null;

                        $url = 'http://www.google.com/search?q=tv+'.$show->name.' '.$year;
                        $url = str_replace(' ', '+', strtolower(trim($url)))

                    @endphp
                        <a class="google_search substr_text" target="_blank" href="{{$url}}">{{@$show->name}}</a>
                        <div class="substr_text" style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
                            {{@$show->genres}}
                        </div>
                    </div>
                    {{-- <div class="btn-group"> --}}
                        <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">Actions <img
                                width="10px" height="10px" src="{{asset('assets/img/chevron-down.png')}}" alt=""
                                style="cursor: pointer"></div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item makeWatchlist" data-id="{{$show->show_id}}" type="button">
                                    <img width="20px" src="{{asset('assets/img/watchlist.svg')}}" alt=""> <span
                                        class="dropdown_text">Add to Queue</span>
                                </button></li>

                            <li><button class="dropdown-item ai_rec_move_to_bookmarks" data-id="{{$show->show_id}}" type="button">
                                    <img width="20px" height="23px"
                                        src="{{asset('assets/img/bookmark.svg')}}" alt=""style="scale: 0.90">
                                    <span class="dropdown_text">Save to Bookmarks</span></button></li>

                            <li><button  class="dropdown-item makeShowILove" data-id="{{$show->show_id}}" type="button">
                                    <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}" alt=""><span
                                        class="dropdown_text"> Add to Shows I love</span>
                                </button></li>
                        </ul>
                    {{-- </div> --}}
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
            let count = '';
            var url = "{{route('moveToWatchlist')}}";
            var message = 'Successfully Updated!';
            var html = '';
            var removeItem = '';
            
            dropdownAjaxRequest(url,id,count,message,html,removeItem);
        });
        // for shows move to section show i love 
        $(document).on('click', '.makeShowILove', function(){
            // $('#loaderContainer').addClass('loading');
            var id = $(this).data('id');
            let count = '';
            var url = "{{route('moveToShowsLoveFromFriends')}}";
            var message = 'Successfully Updated!';
            var html = '';
            var removeItem = '';
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem);
        });
        // for Bookmarks
        $(document).on('click', '.ai_rec_move_to_bookmarks', function(){
            var id = $(this).data('id');
            let count = '';
            var url = "{{route('moveToBookMarksFromFriends')}}";
            var message = 'Successfully Updated!';
            var html = '';
            var removeItem = '';
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem);
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