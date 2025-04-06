@extends('layout.model')

@section('model_content')
<div class="invite_model_box">
    <div>
        <div class="friend_name" style="line-height: 1.5; font-size: 24px">Add a show</div>
    </div>

    <div class="search_head">
        <div class="input_search_box">
            <input type="search" id="searchBox" class="search_box" name="" id=""
                placeholder="Enter the name of a TV show you love">
            <div class="margin_bottom"></div>
            <div id="showResults" class="show_results"></div>
        </div>
    </div>
    <div><button class="add_friend_ btn_bg_gray ">Add</button></div>
</div>
@endsection
@section('model_script')
<script>
    $(document).ready(function () {
    let selectedIndex = -1; 
    let typingTimer;
    const delay = 500; // Delay in milliseconds (0.5 seconds)
    // invate the search popup

    $(document).on("input", "#searchBox" , function () {
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
        $('.input_search_box').css({'position': 'absolute', 'border': '0.75px solid #D6D6D6','box-shadow': '0px 2px 10px 0px rgba(0, 0, 0, 0.1)','border-radius': '22px',});
        $('.margin_bottom').css({'border': '0.75px solid #E8EAED','margin': '0 20px'});

    }


    function RemoveCss(){
        $('.search_box').css({'border': '0.75px solid #D6D6D6','box-shadow': '0px 2px 10px 0px rgba(0, 0, 0, 0.1)','border-radius': '50px'});
        $('.input_search_box').css({ 'position': 'unset', 'border': 'none','box-shadow': 'none'});
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
                            data-image="${item.show.image ? item.show.image.medium : '{{ asset('assets/img/No Image.png') }}'}"
                            data-name = "${item.show.name}"
                            >
                                <img height="40px" src="${item.show.image ? item.show.image.medium : '{{ asset('assets/img/No Image.png') }}'}">
                                    <div style="font-weight: 500; font-size: 14px; line-height: 100%;display: flex; flex-direction: column; align-items: flex-start; justify-content: center; gap: 3px;">${item.show.name}
                                        <div>
                                        <span style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">${item.show.premiered ? item.show.premiered.split('-')[0] : 'N/A'},</span>
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
        let element = $('#selected_item_box' + id); 
        if (element.length) {  
            element.remove();
        }
        $('.search_head').html(`
        <form id="selectedShowBox">
            <div class="selected_item_box" id="selected_item_box${id}" >
                    <img height="60px" src="${image}" alt="">
                    <div class="itemBox">
                        <div class="item_name">${name}</div>
                        <div><img  width="10px" height="10px" class="removeselectedItem" id="removeSelection${id}" data-id='${id}' src="{{asset('assets/img/x.png')}}"alt="" style="cursor: pointer"></div>
                        <input type="hidden" name="item_id" value = "${id}">
                        <input type="hidden" name="item_name" value = "${image}">
                        <input type="hidden" name="item_image" value = "${name}">
                    </div>
            </div>
        </form>
        `);    
        $('#searchBox').val('');
        $("#showResults").html("");
        RemoveCss();
        selectedIndex = -1; 
    });

     // append the slected item in the box via arrow keys and enter button
     $(document).on("keydown", '#searchBox', function (e) {
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
            let element = $('#selected_item_box' + id); 
            if (element.length) {  
                element.remove();
            } 
            $('.search_head').html(`
                <form id="selectedShowBox">
                    <div class="selected_item_box" id="selected_item_box${id}" >
                            <img height="60px" src="${image}" alt="">
                            <div class="itemBox">
                                <div class="item_name">${name}</div>
                                <div><img  width="10px" height="10px" class="removeselectedItem" id="removeSelection${id}" data-id='${id}' src="{{asset('assets/img/x.png')}}"alt="" style="cursor: pointer"></div>
                                <input type="hidden" name="item_id" value = "${id}">
                                <input type="hidden" name="item_name" value = "${image}">
                                <input type="hidden" name="item_image" value = "${name}">
                            </div>
                    </div>
                </form>
            `); 
            $('#searchBox').val('');
            $("#showResults").html("");
            RemoveCss();
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
        $('.model_content_box').html(`
        
            <div class="invite_model_box">
                <div>
                    <div class="friend_name" style="line-height: 1.5; font-size: 24px">Add a show</div>
                </div>
                
                <div class="search_head">
                    <div class="input_search_box">
                        <input type="search" id="searchBox" class="search_box" name="" id=""
                            placeholder="Enter the name of a TV show you love">
                        <div class="margin_bottom"></div>
                        <div id="showResults" class="show_results"></div>
                    </div>
                </div>
            <div><button class="add_friend_ btn_bg_gray ">Add</button></div>    
            </div>
            
            
        `);

    });


});
</script>

@endsection