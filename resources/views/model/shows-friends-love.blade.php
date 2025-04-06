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
                    <li><button class="dropdown-item" type="button">Unfriend</button></li>
                </ul>
            </div>
        </div>
    </div>
    <div style="width: 100%">
        <div style="width:100%" class="over_flow">
            @foreach ($shows ?? [] as $show )
            <div class="selected_item_box" >
                <img class="border-left" width="40px" height="60px" src="{{@$show->image}}" alt="">
                <div class="itemBox">
                    <div class="item_name" style="color: #0B99FF;line-height: 1.5;">{{@$show->name}}
                        <div style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
                            2000, {{@$show->genres}}
                        </div>
                    </div>
                    <div class="btn-group">
                        <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">Actions <img
                                width="10px" height="10px" src="{{asset('assets/img/chevron-down.png')}}" alt=""
                                style="cursor: pointer"></div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" type="button">
                                    <img width="20px" src="{{asset('assets/img/watchlist.svg')}}" alt=""> <span
                                        class="dropdown_text">Move to Watchlist</span>
                                </button></li>
                            <li><button class="dropdown-item" type="button">
                                    <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}" alt=""><span
                                        class="dropdown_text"> Move to Shows I love</span>
                                </button></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection