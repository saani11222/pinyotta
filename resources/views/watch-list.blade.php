@php
$watch_list = true;
@endphp
@extends('layout.master')

@section('content')
<div style="display: flex;justify-content:center">
    <div class="signup_page dashboard">
        <div class="signup_text">
            <div class="signup_heading">Watchlist</div>
            <div class="signupText"></div>
        </div>
        @if ($watch_list)
        <div style="width: 100%">
            <div style="width:100%">
                <div class="selected_item_box" id="selected_item_box">
                    <img width="40px" height="60px" src="{{ asset('assets/img/No Image.png') }}" alt="">
                    <div class="itemBox">
                        <div class="item_name" style="color: #0B99FF;line-height: 1.5;">Lupin
                            <div style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">2000, Comedy
                            </div>
                        </div>
                        <div class="btn-group">
                            <div class="action_anchr" data-bs-toggle="dropdown" aria-expanded="false">Actions <img
                                    width="10px" height="10px" src="{{asset('assets/img/chevron-down.png')}}" alt=""
                                    style="cursor: pointer"></div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" type="button">
                                        <img width="20px" src="{{asset('assets/img/heart-fill.svg')}}" alt=""><span
                                            class="dropdown_text"> Move to Shows I love</span>
                                    </button></li>
                                <li>
                                    <div class="dropdown_border"></div>
                                </li>
                                <li><button class="dropdown-item dropdown_remove_button" type="button"> Remove</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div style="width:100%; text-align: center;">
            <div class="no_list" >No shows in your Watchlist.</div> 
            <div class="no_list_other_text">You can manually add a show to your Watchlist or from 
                <span><a href="{{route('home')}}">Recommendations</a></span> and 
                <span><a href="{{route('friends')}}">Friends.</a></span>
            </div>
        </div>
        @endif
        
        <a href="{{route('add-show')}}" type="button" class="add_more_show btn_bg_blue" >Add a show</a>
        
    </div>
</div>
@endsection


