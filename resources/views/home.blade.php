@extends('layout.master')

@section('content')
@php
$param = request()->query('q');
@endphp
<div style="display: flex;justify-content:center">
    <div class="signup_page dashboard" style="gap: 0px" ;>
        <div class="signup_heading">Recommendations</div>
        <div class="tab-content" id="myTabContent" style="width: 100%">
            {{-- tv shows --}}
            <div class="tab-pane fade {{!@$restaurants && @!$param ? 'show active' : '' }}"
                id="tvshows-{{Route::currentRouteName()}}" role="tabpanel">
                {{-- removed recs from start here --}}
                {{-- <div class="signupText pad-10-15"><span class="rec_text">Recs from others who love the same shows
                        as you.</span>
                    <a href="{{route('howToWork')}}" target="_blank">How it works</a>
                </div>
                @if ($recommendations && $recommendations->isNotEmpty())
                <div style="width: 100%" id="recFromPeople">
                    <div style="width:100%" class="recommendation_box">
                        @foreach ($recommendations ?? [] as $recommendation)
                        <div class="selected_item_box content_show_box margin-last"
                            id="selected_item_box{{$recommendation->show_id}}">
                            <img class="border-left" width="42px" src="{{$recommendation->image}}" alt="">
                            <div class="itemBox">
                                @php
                                // parsing the year
                                $input = $recommendation->genres;
                                $parts = array_map('trim', explode(',', $input));
                                $year = preg_match('/^\d{4}$/', $parts[0]) ? array_shift($parts) : null;

                                $url = 'http://www.google.com/search?q=tv+'.$recommendation->name.' '.$year;
                                $url = str_replace(' ', '+', strtolower(trim($url)))

                                @endphp

                                <div class="item_name" style="color: #0B99FF;line-height: 1.5;">
                                    <a class="google_search substr_text " target="_blank" href="{{$url}}">
                                        {{$recommendation->name}}
                                    </a>
                                    <div class="substr_text"
                                        style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
                                        {{$recommendation->genres}}
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                        <img width="10px" height="10px" src="{{asset('assets/img/chevron-down.png')}}"
                                            alt="" style="cursor: pointer">
                                    </div>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item move_to_watchlist"
                                                data-id="{{$recommendation->show_id}}" type="button">
                                                <img width="20px" src="{{asset('assets/img/watchlist.svg')}}" alt="">
                                                <span class="dropdown_text">Move to Watchlist</span>
                                            </button></li>
                                        <li><button class="dropdown-item move_to_shows_i_love"
                                                data-id="{{$recommendation->show_id}}" type="button">
                                                <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}"
                                                    alt=""><span class="dropdown_text"> Move to Shows I love</span>
                                            </button></li>
                                        <li>
                                            <div class="dropdown_border"></div>
                                        </li>
                                        <li><button
                                                class="dropdown-item dropdown_remove_button remove_show_from_the_list"
                                                data-id="{{$recommendation->show_id}}" type="button">
                                                Remove</button>
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
                    <div class="no_list">No recs from other people yet.</div>
                    <div class="no_list_other_text">When people join that love the same shows as you, you’ll start
                        getting recommendations from them here.
                        {{-- <span><a href="{{route('add-friend')}}">Invite friends.</a></span> --}}
                        {{-- </div>
                </div>
                <div style="color: black;font-weight:600;font-size:14px;text-align:center;">
                    Try getting
                    <span><a class="inline-url" id="openAiTabBtn" style="cursor: pointer;">recs from AI</a></span> or
                    seeing what --}}
                    {{-- <span><a class="inline-url" href="{{route('friends')}}">shows your friends love!</a></span>
                </div>
                @endif --}}
                {{-- removed recs from start here --}}
                <div class="signupText pad-10-15"><span class="rec_text">Based on the </span>
                    <a href="{{route('shows-loved')}}">shows you love.</a>
                </div>
                <div>
                    <div class="button_ai">
                        <button id="get_ai_recs" class="show_recommendations_button" onclick="get_ai_recommendations()"
                            type="button">
                            <div class="btn-html">
                                <img class="btn-img" width="16px" src="{{asset('assets/img/stars.png')}}" alt="">
                                <span class=" btn-txt"> Generate recommendation</span>
                            </div>

                        </button>
                    </div>
                    <div id="ai_rec_box">
                        @if($ai_recs && $ai_recs->isNotEmpty())
                        @foreach ( $ai_recs as $ai_recommendation)
                        <div class="rec-from-ai" id="rec_from_ai{{$ai_recommendation->show_id}}">
                            <div class="rec_from_ai_item">
                                <img class="border-left" width="42px" src="{{$ai_recommendation->image}}" alt="">
                                <div class="itemBox">
                                    @php
                                    // parsing the year
                                    @$input = $ai_recommendation->genres;
                                    @$parts = array_map('trim', explode(',', $input));
                                    @$year = preg_match('/^\d{4}$/', $parts[0]) ? array_shift($parts) : null;

                                    @$url = 'http://www.google.com/search?q=tv+'.$ai_recommendation->name.' '.$year;
                                    @$url = str_replace(' ', '+', strtolower(trim($url)))

                                    @endphp

                                    <div class="item_name" style="color: #0B99FF;line-height: 1.5;">
                                        <a class="google_search substr_text " target="_blank" href="{{$url}}">
                                            {{$ai_recommendation->name}}
                                        </a>
                                        <div class="substr_text"
                                            style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
                                            {{$ai_recommendation->genres}}
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                            <img width="10px" height="10px"
                                                src="{{asset('assets/img/chevron-down.png')}}" alt=""
                                                style="cursor: pointer">
                                        </div>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item ai_rec_move_to_watchlist"
                                                    data-id="{{$ai_recommendation->show_id}}" type="button">
                                                    <img width="20px" src="{{asset('assets/img/watchlist.svg')}}"
                                                        alt="">
                                                    <span class="dropdown_text">Move to Queue</span>
                                                </button></li>

                                            <li><button class="dropdown-item ai_rec_move_to_bookmarks "
                                                    data-id="{{$ai_recommendation->show_id}}" type="button">
                                                    <img width="20px" height="23px"
                                                        src="{{asset('assets/img/bookmark.svg')}}" alt=""
                                                        style="scale: 0.90">
                                                    <span class="dropdown_text">Save for later</span></button></li>
                                            <li><button class="dropdown-item ai_recs_move_to_shows_i_love"
                                                    data-id="{{$ai_recommendation->show_id}}" type="button">
                                                    <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}"
                                                        alt=""><span class="dropdown_text"> Move to Shows I love</span>
                                                </button></li>
                                            <li>
                                                <div class="dropdown_border"></div>
                                            </li>
                                            <li><button
                                                    class="dropdown-item dropdown_remove_button ai_recs_remove_show_from_the_list"
                                                    data-id="{{$ai_recommendation->show_id}}" type="button">
                                                    Remove</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="rec_desc">{{$ai_recommendation->summary}}</div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
            {{-- restaurants --}}
            <div class="tab-pane fade {{@$restaurants || @$param ? 'show active' : '' }} "
                id="restaurants-{{Route::currentRouteName()}}" role="tabpanel">
            
                    <input type="hidden" name="fromAddRestaurants" value="true">
                    <div class="signupText pad-10-15"><span class="rec_text">Based on the </span>
                        <a href="{{route('shows-loved' ,['q' => 'restaurant'])}}" style="color: #0B99FF ; cursor: pointer;">restaurants you love.</a>
                    </div>
            

                {{-- if restaurant added --}}
                @if ($isRestaurantsAdded)
                <div class="rest_info">
                    <div class="filter_fields">
                        <div class="filter_field">

                            <input type="text" id="input_restaurants" class="search_box"
                                placeholder="City" autocomplete="off">

                            <span class="filter_img" id="openFilters">
                                <img height="15px" width="auto" src="{{asset('assets/img/filter.png')}}" alt=""> Filters
                                <span id="filterCount"></span>
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="button_ai">
                            <button id="get_restaurants_recs" onclick="getAiRecsRestaurants()"
                                class="show_recommendations_button" type="button">
                                <div class="btn-html">
                                    <img class="btn-img rest_btn_img" width="16px"
                                        src="{{asset('assets/img/stars.png')}}" alt="">
                                    <span class="btn-txt rest_btn_txt "> Generate recommendation</span>
                                </div>
                            </button>
                        </div>
                        <div id="selectedRestaurants">
                            @foreach ($restaurantsName as $restaurant)
                            <div class="selected_item_box restaurant_box" id="selectedRestaurants{{$restaurant->id}}"
                                style="flex-direction: column;">
                                <div class="itemBox restBox">
                                    <div class="rest_name">
                                        <div class="item_name">
                                            <a class="google_search substr_text " target="_blank" href="http://www.google.com/search?q=restaurant+{{ urlencode($restaurant->name . ' ' . $restaurant->city) }}">
                                                {{ $restaurant->name }}
                                            </a>

                                        </div>
                                        <div class="item_name_gray substr_text">{{$restaurant->city}}</div>
                                    </div>
                                    <div class="btn-group">
                                        <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                            <img width="10px" height="10px"
                                                src="{{asset('assets/img/chevron-down.png')}}" alt=""
                                                style="cursor: pointer">
                                        </div>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item " onclick="submitajaxRequest({{$restaurant->id}},'ai_rest_moveToQueue','' ,'remove')"
                                                    type="button">
                                                    <img width="20px" src="{{asset('assets/img/watchlist.svg')}}"
                                                        alt="">
                                                    <span class="dropdown_text">Move to Queue</span>
                                                </button></li>

                                            <li><button class="dropdown-item" onclick="submitajaxRequest({{$restaurant->id}},'ai_rest_saveToBookmark' , '' ,'remove')"
                                                    type="button">
                                                    <img width="20px" height="23px"
                                                        src="{{asset('assets/img/bookmark.svg')}}" alt=""
                                                        style="scale: 0.90">
                                                    <span class="dropdown_text">Save for later</span></button></li>
                                            <li><button class="dropdown-item " onclick="submitajaxRequest({{$restaurant->id}},'ai_rest_moveToRestaurantILove' ,'','remove' )"
                                                    type="button">
                                                    <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}"
                                                        alt=""><span class="dropdown_text"> Move to Restaurant I
                                                        love</span>
                                                </button></li>
                                            <li>
                                                <div class="dropdown_border"></div>
                                            </li>
                                            <li><button class="dropdown-item dropdown_remove_button"type="button"
                                                onclick="submitajaxRequest({{$restaurant->id}},'ai_rest_remove','Successfully Removed', 'remove')">
                                                    Remove</button>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                                <div class="rec_desc rest_desc">
                                    {{$restaurant->summary}}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                {{-- if no restaurant added --}}
                <div class="rest_info">
                    <div style="color: black;font-weight:600;font-size:14px;text-align:center;">
                        Add restaurants you love to get restaurant recommendations.
                    </div>
                    <div style="width:100%">
                        <a href="{{route('shows-loved' ,['q' => 'restaurant'])}}" class="add_more_show btn_bg_blue ">
                            Add restaurants I love
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    // get_all_ai_get_ai_recommendations();
// function get_all_ai_get_ai_recommendations(){
//     $.ajax({
//             type: "GET",
//             url: "{{route('get-all-recommendations')}}",
//             data: {
//                 _token: "{{csrf_token()}}"
//             },
//             success: function (response) {
//                 console.log(response);
//                 var recomendationData = '';
//                 response.recommendations.forEach(function(rec) {


//                     var name = rec.name;
//                     var genres = rec.genres;
//                     var firstGenre = genres.split(',')[0].trim();

//                     var rawQuery = `tv ${name} ${firstGenre}`;
//                     var query = encodeURIComponent(rawQuery).replace(/%20/g, '+');
//                     var url = `https://www.google.com/search?q=${query}`;


//                     recomendationData+=`<div class="rec-from-ai" id="rec_from_ai${rec.show_id}">
//                         <div class="rec_from_ai_item">
//                         <img class="border-left" width="42px"
//                             src="${rec.image}" alt="">
//                         <div class="itemBox">
//                             <div class="item_name" style="color: #0B99FF;line-height: 1.5;">
//                                 <a class="google_search substr_text " target="_blank" href="${url}">
//                                     ${rec.name}
//                                 </a>
//                                 <div class="substr_text"
//                                     style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
//                                     ${rec.genres}
//                                 </div>
//                             </div>
//                             <div class="btn-group">
//                                 <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">
//                                     Actions
//                                     <img width="10px" height="10px" src="{{asset('assets/img/chevron-down.png')}}"
//                                         alt="" style="cursor: pointer">
//                                 </div>
//                                 <ul class="dropdown-menu dropdown-menu-end">
//                                     <li><button class="dropdown-item ai_rec_move_to_watchlist"
//                                             data-id="${rec.show_id}"  type="button">
//                                             <img width="20px" src="{{asset('assets/img/watchlist.svg')}}" alt="">
//                                             <span class="dropdown_text">Move to Watchlist</span>
//                                         </button></li>
//                                     <li><button class="dropdown-item ai_recs_move_to_shows_i_love"
//                                             data-id="${rec.show_id}" type="button">
//                                             <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}" alt=""><span
//                                                 class="dropdown_text"> Move to Shows I love</span>
//                                         </button></li>
//                                     <li>
//                                         <div class="dropdown_border"></div>
//                                     </li>
//                                     <li><button class="dropdown-item dropdown_remove_button ai_recs_remove_show_from_the_list"
//                                             data-id="${rec.show_id}"  type="button">
//                                             Remove</button>
//                                     </li>
//                                 </ul>
//                             </div>
//                         </div>
//                     </div>
//                     <div class="rec_desc">${rec.summary}</div>
//                      </div>`;

//                 });
//                 $("#ai_rec_box").html(recomendationData);
//                 // console.log(recomendationData);
//             }
//         });
// }
let aiRequestRunning = false;    
let triescount = 1;
function get_ai_recommendations(){
        if (aiRequestRunning) {
            // e.preventDefault();
            return false;
        }
         aiRequestRunning = true;
        // $('.btn-txt').addClass('typewriter');
        $('.show_recommendations_button').addClass('btn_onclick_gray');
        $('.btn-img').addClass('spin_image');
        $('.btn-txt').text('Generating recommendation...');
        
                          
        $.ajax({
            type: "GET",
            url: "{{route('ai-recommendation')}}",
            data: {
                _token: "{{csrf_token()}}"
            },
            success: function (response) {
                if(response.isAlreadyExists == true){
                    if(triescount < 6){
                        triescount = triescount + 1;
                        aiRequestRunning = false;
                        // console.log('Here');
                        get_ai_recommendations();
                        // return;
                    }else{
                        notyf.error("No new recommendation. Please try again later.");
                        aiRequestRunning = false;  
                        // $('.btn-txt').removeClass('typewriter');
                        $('.show_recommendations_button').removeClass('btn_onclick_gray');
                        $('.btn-img').removeClass('spin_image');
                        $('.btn-txt').text('Generate recommendation');  
                        return false;
                    }

                }else{
                    let name = response.recommendation.name;
                    let genres = response.recommendation.genres;
                    let firstGenre = genres.split(',')[0].trim();

                    let rawQuery = `tv ${name} ${firstGenre}`;
                    let query = encodeURIComponent(rawQuery).replace(/%20/g, '+');
                    let url = `https://www.google.com/search?q=${query}`;

                    $("#ai_rec_box").prepend(`<div class="rec-from-ai" id="rec_from_ai${response.recommendation.id}">
                        <div class="rec_from_ai_item">
                        <img class="border-left" width="42px"
                            src="${response.recommendation.image}" alt="">
                        <div class="itemBox">
                            <div class="item_name" style="color: #0B99FF;line-height: 1.5;">
                                <a class="google_search substr_text " target="_blank" href="${url}">
                                    ${response.recommendation.name}
                                </a>
                                <div class="substr_text"
                                    style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
                                    ${response.recommendation.genres}
                                </div>
                            </div>
                            <div class="btn-group">
                                <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <img width="10px" height="10px" src="{{asset('assets/img/chevron-down.png')}}"
                                        alt="" style="cursor: pointer">
                                </div>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item ai_rec_move_to_watchlist"
                                            data-id="${response.recommendation.id}"  type="button">
                                            <img width="20px" src="{{asset('assets/img/watchlist.svg')}}" alt="">
                                            <span class="dropdown_text">Move to Queue</span>
                                        </button></li>
                                    <li><button class="dropdown-item ai_rec_move_to_bookmarks"
                                       data-id="${response.recommendation.id}"  type="button">
                                                    <img width="20px" height="23px" 
                                                        src="{{asset('assets/img/bookmark.svg')}}" alt=""
                                                        style="scale: 0.90">
                                                    <span class="dropdown_text">Save for later</span></button></li>
                                    <li><button class="dropdown-item ai_recs_move_to_shows_i_love"
                                            data-id="${response.recommendation.id}" type="button">
                                            <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}" alt=""><span
                                                class="dropdown_text"> Move to Shows I love</span>
                                        </button></li>
                                    <li>
                                        <div class="dropdown_border"></div>
                                    </li>
                                    <li><button class="dropdown-item dropdown_remove_button ai_recs_remove_show_from_the_list"
                                            data-id="${response.recommendation.id}"  type="button">
                                            Remove</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="rec_desc">${response.summary}</div>
                     </div>
                `);  
                        aiRequestRunning = false;  
                
                        // $('.btn-txt').removeClass('typewriter');
                        $('.btn-img').removeClass('spin_image');
                        $('.show_recommendations_button').removeClass('btn_onclick_gray');
                        $('.btn-txt').text('Generate recommendation');  
                }

                    

            },
            error: function (xhr, status, error) {
                const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                const notyf = new Notyf({
                    duration: 4000,
                    ripple: true,
                    position: {
                        x: 'right',
                        y: 'top',
                    },
                });
                notyf.error(errorMessage);

                aiRequestRunning = false;  
                // $('.btn-txt').removeClass('typewriter');
                $('.btn-img').removeClass('spin_image');
                $('.show_recommendations_button').removeClass('btn_onclick_gray');
                $('.btn-txt').text('Generate recommendation');
            }
        });
       



}
   
    $(document).ready(function () {
        // for shows move to watchlist
        $(document).on('click', '.move_to_watchlist', function(){
            var id = $(this).data('id');
            let count = $('.recommendation_box').find('.selected_item_box').length-1;
            var url = "{{route('moveToWatchlist')}}";
            var message = 'Successfully Updated!';
            var html = `
                 <div style="width:100%; text-align: center;">
                    <div class="no_list">No recs from other people yet.</div>
                    <div class="no_list_other_text">When people join that love the same shows as you, you’ll start
                        getting recommendations from them here.
                    </div>
                </div>
                <div style="height:35px;"></div>
                <div style="color: black;font-weight:600;font-size:14px;text-align:center;">
                    Try getting
                    <span><a class="inline-url" id="openAiTabBtn" style="cursor: pointer;">recs from AI</a></span> or seeing what
                    <span><a class="inline-url" href="{{route('friends')}}">shows your friends love!</a></span>
                </div>
            `;
            var removeItem = $('#selected_item_box'+id);
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem);
        });
        // for shows move to section show i love 
        $(document).on('click', '.move_to_shows_i_love', function(){
            var id = $(this).data('id');
            let count = $('.recommendation_box').find('.selected_item_box').length-1;
            var url = "{{route('moveToShowsLove')}}";
            var message = 'Successfully Updated!';
            var html = `
                <div style="width:100%; text-align: center;">
                    <div class="no_list">No recs from other people yet.</div>
                    <div class="no_list_other_text">When people join that love the same shows as you, you’ll start
                        getting recommendations from them here.
                    </div>
                </div>
                <div style="height:35px;"></div>
                <div style="color: black;font-weight:600;font-size:14px;text-align:center;">
                    Try getting
                    <span><a class="inline-url" id="openAiTabBtn" style="cursor: pointer;">recs from AI</a></span> or seeing what
                    <span><a class="inline-url" href="{{route('friends')}}">shows your friends love!</a></span>
                </div>
            `;
            var removeItem = $('#selected_item_box'+id);
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem);
        });
        $(document).on('click', '.remove_show_from_the_list', function(){
            var id = $(this).data('id');
            let count = $('.recommendation_box').find('.selected_item_box').length-1;
            var url = "{{route('removeShow')}}";
            var message = 'Successfully Removed!';
            var html = `
               <div style="width:100%; text-align: center;">
                    <div class="no_list">No recs from other people yet.</div>
                    <div class="no_list_other_text">When people join that love the same shows as you, you’ll start
                        getting recommendations from them here.
                    </div>
                </div>
                <div style="height:35px;"></div>
                <div style="color: black;font-weight:600;font-size:14px;text-align:center;">
                    Try getting
                    <span><a class="inline-url" id="openAiTabBtn" style="cursor: pointer;">recs from AI</a></span> or seeing what
                    <span><a class="inline-url" href="{{route('friends')}}">shows your friends love!</a></span>
                </div>
            `;
            var removeItem = $('#selected_item_box'+id);
            // this function write in master.blade.php file 
            dropdownAjaxRequest(url,id,count,message,html,removeItem);
        });

        // $(document).on('click', '#get_ai_recs_not_use' , function(e){
        //     if (aiRequestRunning) {
        //         e.preventDefault();
        //         return;
        //     }
        // aiRequestRunning = true;
        //     // $('.btn-txt').addClass('typewriter');
        //     $('.btn-img').addClass('spin_image');
        //     $('.btn-txt').text('Generating...');
            
                              
        //     $.ajax({
        //         type: "GET",
        //         url: "{{route('ai-recommendation')}}",
        //         data: {
        //             _token: "{{csrf_token()}}"
        //         },
        //         success: function (response) {
        //             if(response.isAlreadyExists == true){
        //                 notyf.error("No new recommendation. Please try again later.");
        //                 aiRequestRunning = false;  
        //                 // $('.btn-txt').removeClass('typewriter');
        //                 $('.btn-img').removeClass('spin_image');
        //                 $('.btn-txt').text('Generate AI recommendation');  
        //                 return false;
        //             }
        //                 let name = response.recommendation.name;
        //                 let genres = response.recommendation.genres;
        //                 let firstGenre = genres.split(',')[0].trim();

        //                 let rawQuery = `tv ${name} ${firstGenre}`;
        //                 let query = encodeURIComponent(rawQuery).replace(/%20/g, '+');
        //                 let url = `https://www.google.com/search?q=${query}`;
                        
        //             $("#ai_rec_box").append(`
        //             <div class="rec-from-ai" id="rec_from_ai${response.recommendation.id}">
        //                 <div class="rec_from_ai_item">
        //                     <img class="border-left" width="42px"
        //                         src="${response.recommendation.image}" alt="">
        //                     <div class="itemBox">
        //                         <div class="item_name" style="color: #0B99FF;line-height: 1.5;">
        //                             <a class="google_search substr_text " target="_blank" href="${url}">
        //                                 ${response.recommendation.name}
        //                             </a>
        //                             <div class="substr_text"
        //                                 style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
        //                                 ${response.recommendation.genres}
        //                             </div>
        //                         </div>
        //                         <div class="btn-group">
        //                             <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">
        //                                 Actions
        //                                 <img width="10px" height="10px" src="{{asset('assets/img/chevron-down.png')}}"
        //                                     alt="" style="cursor: pointer">
        //                             </div>
        //                             <ul class="dropdown-menu dropdown-menu-end">
        //                                 <li><button class="dropdown-item ai_rec_move_to_watchlist"
        //                                         data-id="${response.recommendation.id}"  type="button">
        //                                         <img width="20px" src="{{asset('assets/img/watchlist.svg')}}" alt="">
        //                                         <span class="dropdown_text">Move to Watchlist</span>
        //                                     </button></li>
        //                                 <li><button class="dropdown-item ai_recs_move_to_shows_i_love"
        //                                         data-id="${response.recommendation.id}" type="button">
        //                                         <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}" alt=""><span
        //                                             class="dropdown_text"> Move to Shows I love</span>
        //                                     </button></li>
        //                                 <li>
        //                                     <div class="dropdown_border"></div>
        //                                 </li>
        //                                 <li><button class="dropdown-item dropdown_remove_button ai_recs_remove_show_from_the_list"
        //                                         data-id="${response.recommendation.id}"  type="button">
        //                                         Remove</button>
        //                                 </li>
        //                             </ul>
        //                         </div>
        //                     </div>
        //                 </div>
        //                 <div class="rec_desc">${response.summary}</div>
        //             </div>
        //             `);  
        //             aiRequestRunning = false;  
               
        //             // $('.btn-txt').removeClass('typewriter');
        //             $('.btn-img').removeClass('spin_image');
        //             $('.btn-txt').text('Generate AI recommendation');  
        //         },
        //         error: function (xhr, status, error) {
        //             const errorMessage = xhr.responseJSON?.message || 'Failed to update';
        //             const notyf = new Notyf({
        //                 duration: 4000,
        //                 ripple: true,
        //                 position: {
        //                     x: 'right',
        //                     y: 'top',
        //                 },
        //             });
        //             notyf.error(errorMessage);

        //             aiRequestRunning = false;  
        //             // $('.btn-txt').removeClass('typewriter');
        //             $('.btn-img').removeClass('spin_image');
        //             $('.btn-txt').text('Generate AI recommendation');
        //         }
        //     });
           
        // });

        // call to action from ai recs
        // *******move to watchlist 
        $(document).on('click','.ai_rec_move_to_watchlist', function(){
            let id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{route('moveToWatchlistFromAiRecs')}}",
                data: {
                    id: id,
                    _token: "{{csrf_token()}}"
                },
                success: function (response) {
                        notyf.success('Successfully Updated!');
                        $('#rec_from_ai' + id).remove();

                },
                error: function (xhr, status, error) {
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                    notyf.error(errorMessage);
                }
            });

        });
        // *******move to shows-love
        $(document).on('click','.ai_recs_move_to_shows_i_love', function(){
            // $('#loaderContainer').addClass('loading');
            let id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{route('moveToShowsloveFromAiRecs')}}",
                data: {
                    id: id,
                    _token: "{{csrf_token()}}"
                },
                success: function (response) {
                        $('#loaderContainer').removeClass('loading');
                        notyf.success('Successfully Updated!');
                        $('#rec_from_ai' + id).remove();

                },
                error: function (xhr, status, error) {
                    $('#loaderContainer').removeClass('loading');
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                    notyf.error(errorMessage);
                }
            });

        });
        
        // *******remove from recs 
        $(document).on('click','.ai_recs_remove_show_from_the_list', function(){
            let id = $(this).data('id');

       
            $.ajax({
                type: "GET",
                url: "{{route('removeFromAiRecs')}}",
                data: {
                    id: id,
                    _token: "{{csrf_token()}}"
                },
                success: function (response) {
                        notyf.success('Successfully Removed!');
                        $('#rec_from_ai' + id).remove();

                },
                error: function (xhr, status, error) {
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                    notyf.error(errorMessage);
                }
            });

        });

        
    });
// save to bookMark
    $(document).on('click','.ai_rec_move_to_bookmarks', function(){
            let id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{route('moveToBookMarksFromRecs')}}",
                data: {
                    id: id,
                    _token: "{{csrf_token()}}"
                },
                success: function (response) {
                        notyf.success('Successfully Updated!');
                        $('#rec_from_ai' + id).remove();

                },
                error: function (xhr, status, error) {
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                    notyf.error(errorMessage);
                }
            });

        });
    
</script>
{{-- <script>
    document.getElementById('openAiTabBtn').addEventListener('click', function () {
        const aiTab = document.querySelector('[data-bs-target="#recFromAi"]');
        if (aiTab) {
            aiTab.click();
        }
    });
</script> --}}
@if($callaiFunction)
<script>
    $(document).ready(function () {
            get_ai_recommendations();
            $.ajax({
                type: "GET",
                url: "{{route('updateremebertoken')}}",
                data: {
                    _token: "{{csrf_token()}}"
                },
                success: function (response) {
                    console.log(response);
                    
                },
                error: function (xhr, status, error) {
                    console.log('XHR:', xhr);
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response Text:', xhr.responseText); 
                }
            });
        });
</script>
@endif
{{-- for fileter popup and get restaurant recommendation--}}
{{-- NOTE! the other js code related to popup wirte in layout.include.filterPopup.blade.php file --}}
<script>
    $(document).ready(function () {
        $(document).on('click','#openFilters',function(){
            $('#filterPopupOverLay').fadeIn(300);
        });
        // applying filter
        let fileterCount ;
        $(document).on('click','.apply_filter',function(){
            let checkedElement = $('input[type="checkbox"]:checked');
            fileterCount = checkedElement.length;
            $('#filterPopupOverLay').fadeOut(300);
            $('#filterCount').html(fileterCount);
            $('#filterCount').addClass('active_filter');
        });


   

        // $(document).on('click','#get_restaurants_recs',function(){
        //     let filterData = new FormData($('#selected_filters')[0]); //in layout.include.filterPopup.blade.php file
        //     let specific_description = $('#input_restaurants').val().trim();
               
        // }); 
    });
      function submitajaxRequest(id,type,messageText=null,isRemove=null){
            var id = id;
            var type = type;
            let count = $('#selectedRestaurants').find('.restaurant_box').length-1;
            var url = "{{route('addRestaurantToQueue')}}";
            var message = messageText || 'Successfully Updated!';
            var html = '';
            let removeItem = '';
            if(isRemove === 'remove'){
                removeItem = $('#selectedRestaurants'+id);
            }
            
            dropdownAjaxRequest(url,id,count,message,html,removeItem,type);
    }
    var isAiRequestRunning = false;
    
         function getAiRecsRestaurants(){
            if (isAiRequestRunning) {
                return false;
            }
            isAiRequestRunning = true;

            $('#get_restaurants_recs').addClass('btn_onclick_gray'); 
            $('.rest_btn_img').addClass('spin_image');
            $('.rest_btn_txt').text('Generating recommendation...');

            let filterData = new FormData($('#selected_filters')[0]); //in layout.include.filterPopup.blade.php file
            let specific_description = $('#input_restaurants').val().trim();

            // formData = new FormData();
            filterData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            filterData.append('location', specific_description);


          $.ajax({
            type: "POST",
            url: "{{route('ai-recommendation-restaurants')}}",
            contentType:false,
            processData:false,
            data:filterData,
            success: function (response) {
                    let rawQuery = `restaurant ${response.rest_name} ${response.city}`;
                    let query = encodeURIComponent(rawQuery).replace(/%20/g, '+');
                    let url = `https://www.google.com/search?q=${query}`;

                 $('#selectedRestaurants').prepend(
                    `   <div class="selected_item_box restaurant_box" id="selectedRestaurants${response.id}"
                                style="flex-direction: column;">
                                <div class="itemBox restBox">
                                    <div class="rest_name">
                                        <div class="item_name">
                                            <a class="google_search substr_text " target="_blank" href="${url}">
                                                ${response.rest_name}
                                            </a>
                                        </div>
                                        <div class="item_name_gray substr_text">${response.city}</div>
                                    </div>
                                    <div class="btn-group">
                                        <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                            <img width="10px" height="10px"
                                                src="{{asset('assets/img/chevron-down.png')}}" alt=""
                                                style="cursor: pointer">
                                        </div>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item " onclick="submitajaxRequest(${response.id},'ai_rest_moveToQueue','' ,'remove')"
                                                    type="button">
                                                    <img width="20px" src="{{asset('assets/img/watchlist.svg')}}"
                                                        alt="">
                                                    <span class="dropdown_text">Move to Queue</span>
                                                </button></li>

                                            <li><button class="dropdown-item" onclick="submitajaxRequest(${response.id},'ai_rest_saveToBookmark' , '' ,'remove')"
                                                    type="button">
                                                    <img width="20px" height="23px"
                                                        src="{{asset('assets/img/bookmark.svg')}}" alt=""
                                                        style="scale: 0.90">
                                                    <span class="dropdown_text">Save for later</span></button></li>
                                            <li><button class="dropdown-item " onclick="submitajaxRequest(${response.id},'ai_rest_moveToRestaurantILove' ,'','remove' )"
                                                    type="button">
                                                    <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}"
                                                        alt=""><span class="dropdown_text"> Move to Restaurant I
                                                        love</span>
                                                </button></li>
                                            <li>
                                                <div class="dropdown_border"></div>
                                            </li>
                                            <li><button class="dropdown-item dropdown_remove_button"type="button"
                                                onclick="submitajaxRequest(${response.id},'ai_rest_remove','Successfully Removed', 'remove')">
                                                    Remove</button>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                                <div class="rec_desc rest_desc">
                                    ${response.summary}
                                </div>
                            </div>
                    `
                 );

            $('.rest_btn_img').removeClass('spin_image');
            $('#get_restaurants_recs').removeClass('btn_onclick_gray');
            $('.rest_btn_txt').text('Generate recommendation');
            isAiRequestRunning= false;

            },
            error: function (xhr, status, error) {
                const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                const notyf = new Notyf({
                    duration: 4000,
                    ripple: true,
                    position: {
                        x: 'right',
                        y: 'top',
                    },
                });
                notyf.error(errorMessage);

                isAiRequestRunning = false;  
                
                $('.rest_btn_img').removeClass('spin_image');
                $('#get_restaurants_recs').removeClass('btn_onclick_gray');
                $('.rest_btn_txt').text('Generate recommendation');
                
            
            }
        });
            

        }

</script>

@endsection