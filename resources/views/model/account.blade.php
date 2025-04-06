@extends('layout.model')
@section('model_content')
<div class="invite_model_box no_border">
    <div class="model_friends_show_list_top">
        <div class="model_firend_show account_model">
            <div class="friend_name" style="line-height: 1.5;">Account</div>
        </div>
        <div>
            <div class="btn-group">
                <div class="three_dots" data-bs-toggle="dropdown" aria-expanded="false"><img height="3px"
                        src="{{asset('assets/img/ellipsis.png')}}" alt=""> </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><button class="dropdown-item" type="button">Delete account</button></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="invite_input_box">
            <div>
                <input type="text" value="Johan" name="first_name" id="first_name" class="input_invite" >
            </div>
            <div>
                <input type="text" value="Smith" name="last_name" id="last_name" class="input_invite" >
            </div>
        </div>
        <div class="signin_text">You are signed in with Google</div>
        <div>
        <button class="add_friend_ btn_bg_blue ">Done</button>
        </div>
    </div>
@endsection