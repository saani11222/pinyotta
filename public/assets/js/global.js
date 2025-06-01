function checkInputLenght(param){
    if(param == 'restaurants'){
        var $restaurants= $('#input_restaurants').val().trim();
        var $city = $('#city').val().trim();
        let button =  $('#add_restaurants');
            if($restaurants && $city){
            button.removeClass('btn_bg_gray');
            button.addClass('btn_bg_blue add_restaurants');
            button.css('cursor','pointer');
            }else{
                button.removeClass('btn_bg_blue add_restaurants');
                button.addClass('btn_bg_gray');
                button.css('cursor','default');
            }   
    }
     
} 

function addRestaurants(){
    let imgSrc =  window.appImage?.xImage; //write in master.blade.php
    var restaurants= $('#input_restaurants').val().trim();
    var city = $('#city').val().trim();
    $('#selectedRestaurants').append(`
        <div class="selected_item_box">
            <div class="itemBox restBox">
                <div class="rest_name">
                <div class="item_name substr_text">${restaurants}</div>
                <div class="item_name_gray substr_text">${city}</div>
                </div>
                <div class="action_anchr removeselectedItem" ><img id="cross_icon" width="10px" height="10px" src="${imgSrc}" alt="" style="cursor: pointer"></div>
                <input type="hidden" name="restaurants[]" value="${restaurants}">
                <input type="hidden" name="city[]" value="${city}">
            </div>
        </div>
    `);
    $('#input_restaurants').val('');
    $('#city').val('');
    checkInputLenght('restaurants');
    checkItems();
    $('#input_restaurants').focus();
}
function checkItems() {
    let items = $('#selectedRestaurants').children('.selected_item_box');
    let imgSrc =  window.appImage?.vector; //write in master.blade.php
    let routeName = window.appRoute?.homeRoute;
    let csrf_Token = window.appRoute?.csrfToken;
    
    if (items.length === 0) {
        $('.restbuttonSection').html("");
    } else if (items.length < 5) {
        var count = 5-items.length;
        $('.restbuttonSection').html(`
            <div>
                <div class="buttonTextBox">
                    <span class="text_one">Add at least ${count} more restaurant${count === 1 ? '' : 's'}</span>
                </div>
                <div class="button_"><button style="cursor:not-allowed;width:100%;" class="show_recommendations">Continue</button></div>
            </div>
        `);
    } else {
        $('.restbuttonSection').html(`
            <div>
                <div class="buttonTextBox">
                    <span><img height="16px" width="16px" src="${imgSrc}"></span>
                    <span class="text_two">The more you add, the better your recommendations will be.</span>
                </div>
                <div class="button_">
                <form method="POST" action="${routeName}" id="redirectForm">
                    <input type="hidden" name="_token" value="${csrf_Token}">
                    <input type="hidden" name="fromAddRestaurants" value="true">
                    <button type="button" class="show_recommendations whenEnabled" style="width:100%;">Continue</button>
                </form>
                </div>
            </div>
        `);
    }
}
function removeselectedRestaurants(){
    $(document).on('click', '.removeselectedItem', function () {
        $(this).closest('.selected_item_box').remove();
        checkItems();
    });
}
removeselectedRestaurants();

function globalAjaxRequest(category,action,route){
    let formData = null;
    if(category == 'restaurant' && (action == 'create' || 'waitThencreate')){
        formData = new FormData($('#selectedRestaurants')[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('action', action);
        formData.append('category', category);
    }

    
    $.ajax({
        type: "POST",
        url: route,
        data: formData,
        processData: false, 
        contentType: false, 
        success: function (response) {
            
            if(action == 'create'){
                $('#redirectForm').submit();
            }
            if(action == 'waitThencreate'){
                window.location.href = '/public/signup';
            }
              
            
        },
        error: function (xhr, status, error) {
            const errorMessage = xhr.responseJSON?.message || 'Failed to update';
            notyf.error(errorMessage);
        }
    });
}

