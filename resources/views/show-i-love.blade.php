@extends('layout.master')

@section('content')
@php
$param = request()->query('q');
@endphp
<div style="display: flex;justify-content:center;flex-direction: column;
    align-items: center;">
    <div class="signup_page dashboard">

        <div class="tab-content" id="myTabContent" style="width: 100%">
            <div class="tab-pane fade {{!@$restaurants && @!$param ? 'show active' : '' }}" id="tvshows-{{Route::currentRouteName()}}" role="tabpanel">
                <div class="signup_text">
                    <div class="signup_heading">Shows I love</div>
                    <div class="signupText pad-10-15">The more shows you add, the better your recommendations will be.
                    </div>
                </div>
                <div style="width:100%">
                    <a href="{{route('add-show')}}" type="button" class="add_more_show btn_bg_blue">Add a show</a>
                </div>
                <div style="width: 100%">
                    <div style="width:100%" class="shows_love_box">
                        @foreach ( $showsList ?? [] as $show )
                        <div class="selected_item_box" id="remove_item_box{{$show->show_id}}">
                            <img class="border-left" width="40px" height="60px" src="{{$show->image}}" alt="">
                            <div class="itemBox">
                                <div class="item_name shows_loved" style="line-height: 1.5;">{{$show->name}}
                                    {{-- <div
                                        style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">2000,
                                        Comedy
                                    </div> --}}
                                    <div class="action_anchr  removefromshowsilove" data-id="{{$show->show_id}}">
                                        <img width="10px" height="10px" src="{{asset('assets/img/x.png')}}" alt=""
                                            style="cursor: pointer">
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- restaurants --}}
            <div class="tab-pane fade {{@$restaurants || @$param ? 'show active' : '' }}" id="restaurants-{{Route::currentRouteName()}}" role="tabpanel">
                {{--  --}}
                @if ($isRestaurantsAdded)

                <div class="signup_text">
                    <div class="signup_heading">Restaurants I Love</div>
                    <div class="signupText pad-10-15">The more you add, the better your recommendations will be.</div>
                </div>
                 <div style="width:100%">
                    <a href="{{route('add-restaurant-love')}}" type="button" class="add_more_show btn_bg_blue">Add a restaurant</a>
                </div>
                   <div id="selectedRestaurants" >
                    @foreach ($restaurantsName as $restaurant)
                    <div class="selected_item_box restaurant_box" id="selectedRestaurants{{$restaurant->id}}">
                        <div class="itemBox restBox">
                            <div class="rest_name">
                                <div class="item_name substr_text">{{$restaurant->name}}</div>
                                <div class="item_name_gray substr_text">{{$restaurant->city}}</div>
                            </div>
                            <div class="action_anchr" onclick="submitajaxRequest({{$restaurant->id}} , 'restaurant-remove-from-love','.saved_restaurants')" >
                                <img id="cross_icon" height="10px" width="10px" src="{{ asset('assets/img/x.png') }}" alt="" style="cursor: pointer">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @else

                <div class="signup_text rest_info_text">
                    Add restaurants you love to get restaurant recommendations.
                </div>
                {{-- input fields for adding restaurant --}}
                <div class="search_head">
                    <div class=" add_rest_box">
                        <div class="row" style="margin-bottom: 25px;width:100%;">
                            <div class="col-sm-6 col-12 pd_0" style="padding-left: 0px;padding-right:8px;margin-bottom:12px;"><input type="text"
                                    id="input_restaurants" class="search_box" placeholder="Enter a restaurant you love"
                                    autocomplete="off">
                            </div>
                            <div class="col-sm-4 col-8" style="padding-left: 0px;padding-right:8px;">
                                <input type="text" id="city" class="search_box" placeholder="City" autocomplete="off">
                            </div>
                            <div class="col-sm-2 col-4" style="padding: 0px">
                                <button type="button" class="add-rest" id="add_restaurants"
                                    style="cursor: default">Add</button>
                            </div>
                        </div>
                        <form id="selectedRestaurants" style="width: 100%"></form>
                        <div class="restbuttonSection" style="width: 100%"></div>
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
     function submitajaxRequest(id, type,targetElement){
            var id = id;
            var type = type;
            let count = $('#selectedRestaurants').find('.selected_item_box').length-1;
            var url = "{{route('addRestaurantToQueue')}}";
            var message = 'Successfully Updated!';
            var html = '';
            var removeItem = $('#selectedRestaurants'+id);
            let totalItems = $('#selectedRestaurants').find('.selected_item_box').length;
            if(totalItems > 5){
                dropdownAjaxRequest(url,id,count,message,html,removeItem,type);
            }else{
                 notyf.error('Minimum 5 restaurants required Please add one more for remove.');
            }
            
    }

    $(document).ready(function () {
        $(document).on('click','.removefromshowsilove', function(){
            // $('#loaderContainer').addClass('loading');
            var id = $(this).data('id');
            $('#remove_item_box'+id).remove();
            $.ajax({
                type: "GET",
                url: "{{route('remveshowfromshowsilove')}}",
                data: {
                    id: id,
                    _token: "{{csrf_token()}}"
                },
                success: function (response) {
                    // $('#loaderContainer').removeClass('loading');
                        notyf.success('Successfully Removed!');

                },
                error: function (xhr, status, error) {
                    // $('#loaderContainer').removeClass('loading');
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                    notyf.error(errorMessage);
                    
                }
            });
        });


        //  for restaurants
        $('#input_restaurants').on('input', function () {checkInputLenght('restaurants');}); //write in public/js/global.js
        $('#city').on('input', function () {checkInputLenght('restaurants');}); //write in public/js/global.js
       
        $(document).on('click','.add_restaurants', function(){
            addRestaurants(); //write in public/js/global.js
        });
        $(document).on('click','.whenEnabled', function(){
            var category = 'restaurant';
            var action = 'create';
            var route = "{{route('globalFunctionForSaveInDb')}}"
            globalAjaxRequest(category,action,route); //write in public/js/global.js
        });
        
    });
</script>

@endsection