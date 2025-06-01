@extends('layout.master')

@section('content')

<div class="main_index_content">
    <div class="index-content">
        <div><img height="123px" src="{{asset('assets/img/pinyotta.png')}}" alt=""></div>
        <div class="rec-text">Get recommendations based on what you already love.</div>
        <div id="exTab1" style="align-self:center;">
            <ul class="nav nav-tabs" id="myTab" role="tablist">

                <li class="nav-item " role="presentation">
                    <button class="nav-link active signup_heading" data-bs-toggle="tab" data-bs-target="#tvshows"
                        type="button" role="tab">TV Shows</button>
                </li>

                <li class="nav-item " role="presentation">
                    <button class="nav-link signup_heading" data-bs-toggle="tab" data-bs-target="#restaurants"
                        type="button" role="tab">Restaurants</button>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="myTabContent" style="width: 100%">
            {{-- tv shows --}}
            <div class="tab-pane fade show active" id="tvshows" role="tabpanel">
                <div class="search_head">
                    <div class="input_search_box input_search">
                        <input type="search" id="searchBox" class="search_box" name="" id=""
                            placeholder="Enter a TV series you love (e.g. The White Lotus)" autocomplete="off">
                        <div class="margin_bottom"></div>
                        <div id="showResults" class="show_results"></div>
                    </div>
                    <form id="slectedItemBox"></form>
                </div>
                <div class="buttonSection"></div>
            </div>
            {{-- restaurants --}}
            <div class="tab-pane fade " id="restaurants" role="tabpanel">
                {{--  --}}
                <div class="search_head">
                    <div class="input_search_box input_search add_rest_box">
                        <div class="row" style="margin-bottom: 25px;width:100%;">
                            <div class="col-sm-6 col-12 pd_0" style="padding-left: 0px;padding-right:8px; margin-bottom:12px;"><input type="text"
                                    id="input_restaurants" class="search_box" placeholder="Enter a restaurant you love"
                                    autocomplete="off">
                            </div>
                            <div class="col-sm-4 col-8" style="padding-left: 0px;padding-right:8px;">
                                <input type="text" id="city" class="search_box" placeholder="City" autocomplete="off">
                            </div>
                            <div class="col-sm-2 col-4" style="padding: 0px">
                                <button type="button" class="add-rest" id="add_restaurants" style="cursor: default">Add</button>
                            </div>
                        </div>
                        <form id="selectedRestaurants" style="width: 100%"></form>
                        <div class="restbuttonSection" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('script')
    @if($errors->has('error'))
    <script>
        notyf.error("{{$errors->first('error')}}");
    </script>
    @endif
    <script>
        $(document).ready(function () {
    
    let selectedIndex = -1; 
    let typingTimer;
    const delay = 500; // Delay in milliseconds (0.5 seconds)

    $("#searchBox").on("input", function () {
        clearTimeout(typingTimer); // Clear previous timer
        let query = $(this).val().trim();

        if (query.length > 1) {
            typingTimer = setTimeout(() => {
                fetchShow(query);
            }, delay);
        } else {
            $("#showResults").html("");
            RemoveCss(); // Clear results if input is empty
        }
    });


    function Addcss(){
        $('.search_box').css({'border': 'none','box-shadow': 'none'});
        $('.input_search_box').css({'border': '0.75px solid #D6D6D6','box-shadow': '0px 2px 10px 0px rgba(0, 0, 0, 0.1)','border-radius': '22px',});
        $('.margin_bottom').css({'border': '0.75px solid #E8EAED','margin': '0 20px'});

    }


    function RemoveCss(){
        $('.search_box').css({'border': '0.75px solid #D6D6D6','box-shadow': '0px 2px 10px 0px rgba(0, 0, 0, 0.1)','border-radius': '50px'});
        $('.input_search_box').css({'border': 'none','box-shadow': 'none'});
        $('.margin_bottom').css({'border': 'none','margin': '0'});
    }

    // fetch the seach data and call api
    function fetchShow(query) {
        $.ajax({
            url: `https://api.tvmaze.com/search/shows?q=${query}`,
            method: "GET",
            success: function (response) {    
                if (response && response.length > 0) {
                    Addcss();
                    let resultHTML = response.map(item => `
                        <div class='resultBox' >
                            <div class = 'resultBoxItem' style="display: flex; gap: 8px;cursor:pointer" data-id="${item.show.id}"
                            data-image="${item.show.image ? item.show.image.medium : '{{asset('assets/img/No Image.png') }}'}"
                            data-name = "${item.show.name}" 
                            data-genres = "${ item.show.premiered ? item.show.premiered.split('-')[0] : 'N/A' }${item.show.genres?.length ? ', ' : ''}${ item.show.genres.join(", ") }">
                                <img height="40px" src="${item.show.image ? item.show.image.medium : '{{asset('assets/img/No Image.png') }}'}">
                                    <div style="font-weight: 500; font-size: 14px; line-height: 100%;display: flex; flex-direction: column; align-items: flex-start; justify-content: center; gap: 3px;">${item.show.name}
                                        <div>
                                        <span style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">${item.show.premiered ? item.show.premiered.split('-')[0] : 'N/A'}${item.show.genres?.length ? ',' : ''}</span>
                                        <span style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">${item.show.genres.join(", ")}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    $("#showResults").html(resultHTML);
                    
                    
                    

                }else{
                    Addcss();
                    $("#showResults").html("<p class='noResultFound'>No results found.</p>");
                }    
            },
            error: function () {
                $("#showResults").html("<p class='noResultFound'>No results found.</p>");
            }
        });
    }

    // hide and clear the search_box
    $('#searchBox').on('input', function () {
        if ($(this).val() === '') {
            $("#showResults").html("");
            RemoveCss();
            selectedIndex = -1; 
         }
    });

    // hide and clrer the seach box
    $(document).on("click", function (e) {
        if (!$("#searchBox").is(e.target) && !$(".input_search_box").is(e.target) && !$(".resultBox").is(e.target)) {
            $('#searchBox').val('');
            $("#showResults").html("");
            RemoveCss();
            selectedIndex = -1; 
        }
    });
   


     // append the slected item in the box via mouse click
    $(document).on('click', '.resultBoxItem' , function(){
        var $this = $(this);
        var id = $this.data('id');
        var image = $this.data('image');
        var name = $this.data('name');
        var genres = $this.data('genres');
        let element = $('#selected_item_box' + id); 
        if (element.length) {  
            element.remove();
        }
        $('#slectedItemBox').append(`
            <div class="selected_item_box" id="selected_item_box${id}" >
                <img class="border-left" height="60px" src="${image}" alt="">
                    <div class="itemBox">
                        <div class="item_name substr_text">${name}</div>
                        <div class="action_anchr removeselectedItem" data-id='${id}'><img  width="10px" height="10px"  id="removeSelection${id}"  src="{{asset('assets/img/x.png')}}"alt="" style="cursor: pointer"></div>
                        <input type="hidden" name="item_id[]" value = "${id}">
                        <input type="hidden" name="item_name[]" value = "${name}">
                        <input type="hidden" name="item_image[]" value = "${image}">
                        <input type="hidden" name="genres[]" value="${genres}">
                    </div>
            </div>
        `)    
        $('#searchBox').val('');
        $("#showResults").html("");
        RemoveCss();
        checkItems();
        selectedIndex = -1; 
    });


    // scroll input field into view 
    $('#searchBox').on('focus', function(){
    setTimeout(() => {
        document.getElementById('showResults')?.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
        });
    }, 250); 
});

     // append the slected item in the box via arrow keys and enter button
    $('#searchBox').on("keydown", function (e) {

        let dropdown = $("#showResults");
        let items = dropdown.children(".resultBox"); 
        if (!items.length || !dropdown.is(":visible")) return;

        if (e.key === "ArrowDown") {
            e.preventDefault();
            if (selectedIndex < items.length - 1) {
                selectedIndex++;
            }
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            if (selectedIndex > 0) {
                selectedIndex--;
            }
        } else if (e.key === "Enter" && selectedIndex > -1) {
            e.preventDefault();
            let selectedItem = items.eq(selectedIndex).children('.resultBoxItem');
            let id = selectedItem.data('id');
            let image = selectedItem.data('image');
            let name = selectedItem.data('name'); 
            let genres = selectedItem.data('genres'); 
            let element = $('#selected_item_box' + id); 
            if (element.length) {  
                element.remove();
            } 
            $('#slectedItemBox').append(`
                <div class="selected_item_box" id="selected_item_box${id}">
                    <img class="border-left" height="60px" src="${image}" alt="">
                    <div class="itemBox">
                        <div class="item_name substr_text">${name}</div>
                        <div class="action_anchr removeselectedItem" data-id='${id}'><img width="10px" height="10px"  id="removeSelection${id}"  src="{{asset('assets/img/x.png')}}" alt="" style="cursor: pointer"></div>
                        <input type="hidden" name="item_id[]" value="${id}">
                        <input type="hidden" name="item_name[]" value="${name}">
                        <input type="hidden" name="item_image[]" value="${image}">
                        <input type="hidden" name="genres[]" value="${genres}">
                    </div>
                </div>
            `);
            $('#searchBox').val('');
            $("#showResults").html("");
            RemoveCss();
            checkItems();
            selectedIndex = -1; 
            return;
        }
        // **Remove active class from all child elements inside .resultBoxItem**
        items.find('.resultBoxItem').removeClass("active_list");
        if (selectedIndex > -1) {
            let selectedItem = items.eq(selectedIndex);
            // **Add active_list class to the child element inside the selected .resultBoxItem**
            selectedItem.find('.resultBoxItem').addClass("active_list");
            // Ensure the selected item is visible
            dropdown.scrollTop(selectedItem.position().top + dropdown.scrollTop() - dropdown.height() / 2);
        }
    });



    // remove the appended items on click cross icon
    $(document).on('click', '.removeselectedItem' , function(){
        var id = $(this).data('id');
        $('#selected_item_box'+id).remove();
        checkItems();
    });



    // add the button section and text below the seach bar 
    function checkItems() {
        let items = $('#slectedItemBox').children('.selected_item_box');
        
        if (items.length === 0) {
            $('.buttonSection').html("");
        } else if (items.length < 5) {
            var count = 5-items.length;
            $('.buttonSection').html(`
                <div>
                    <div class="buttonTextBox">
                        <span class="text_one">Add at least ${count} more show${count === 1 ? '' : 's'}</span>
                    </div>
                    <div class="button_"><button style="cursor:not-allowed;" class="show_recommendations">Show recommendations!</button></div>
                </div>
            `);
        } else {
            $('.buttonSection').html(`
                <div>
                    <div class="buttonTextBox">
                        <span><img height="16px" width="16px" src="{{asset('assets/img/Vector.png') }}"></span>
                        <span class="text_two">The more shows you add, the better your recommendations will be.</span>
                    </div>
                    <div class="button_"><button class="show_recommendations whenEnabledTvShows">Show recommendations!</button></div>
                </div>
            `);
        }
    }
// <span class="text_two">(our algorithm needs 5+ shows in order to work).</span>
    // call ajax for saving data into session
    $(document).on('click','.whenEnabledTvShows', function(){
    let formData = new FormData($('#slectedItemBox')[0]);
        formData.append('_token', '{{csrf_token()}}');
    $.ajax({
        type: "post",
        url: "{{route('save.session')}}",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            window.location.href = '/signup';
            
        },
        error: function(xhr, status, error) {
        console.log('AJAX error:', xhr.responseText);
        console.log('Status:', status);
        console.log('Error:', error);
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
            var action = 'waitThencreate';
            var route = "{{route('globalFunctionForSaveInDb')}}"
            globalAjaxRequest(category,action,route); //write in public/js/global.js
        });
        
        
                
});
;
    </script>

    @endsection