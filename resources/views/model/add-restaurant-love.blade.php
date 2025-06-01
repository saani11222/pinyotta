@extends('layout.model')

@section('model_content')


<div class="invite_model_box">
    <div>
        <div class="friend_name" style="line-height: 1.5; font-size: 24px">Add a restaurant</div>
    </div>

    <div class="search_head">
        <div class=" add_rest_box">
            <div class="row" style="margin-bottom: 25px;width:100%;">
                <div class="col-sm-6 col-12 pd_0" style="padding-left: 0px;padding-right:8px; margin-bottom: 12px;"><input type="text"
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
        </div>
    </div>

</div>
@endsection
@section('model_script')
<script>
    var queue = '{{$queue}}';


   function checkInputLenght(){
        
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
    // 
 $('#input_restaurants').on('input', function () {checkInputLenght();}); 
 $('#city').on('input', function () {checkInputLenght();}); 
    $(document).on('click','.add_restaurants',function(){
            var name= $('#input_restaurants').val().trim();
            var city = $('#city').val().trim();
            let type = 'signle-restaurant-add';
            if(queue){
                type  = 'signle-restaurant-add-queue';
            }
             $.ajax({
                type: "GET",
                url: "{{route('addRestaurantToQueue')}}",
                data: {
                    name: name,
                    city: city,
                    type:type,
                    _token: "{{csrf_token()}}"
                },
                success: function (response) {
                    notyf.success('Successfully Updated');
                    $('#input_restaurants').val('');
                    $('#city').val('');
                    checkInputLenght();
                },
                error: function (xhr, status, error) {
                    
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                    notyf.error(errorMessage);
                    
                }
            });
    });
</script>

@endsection