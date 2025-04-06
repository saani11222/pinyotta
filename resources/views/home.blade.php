
@extends('layout.master')

@section('content')
<div style="display: flex;justify-content:center">
    <div class="signup_page dashboard">
        <div class="signup_text">
            <div class="signup_heading">Recommendations</div>
            <div class="signupText"><a href="{{route('howToWork')}}" target="_blank">How it works</a></div>
        </div>
        @if ($recommendations)
        <div style="width: 100%">
            <div style="width:100%">
                @foreach ($recommendations ?? [] as $recommendation)
                <div class="selected_item_box" id="selected_item_box">
                    <input type="hidden" name="show_id" value="{{$recommendation->show_id}}">
                    <img class="border-left" width="42px" height="60px" src=" {{$recommendation->image}}" alt="">
                    <div class="itemBox">
                        <div class="item_name" style="color: #0B99FF;line-height: 1.5;">{{$recommendation->name}}
                            <div style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">
                                {{$recommendation->genres}}
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
                                <li>
                                    <div class="dropdown_border"></div>
                                </li>
                                <li><button class="dropdown-item dropdown_remove_button" type="button"> Remove</button>
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
            <div class="no_list" >No recommendations yet.</div> 
            <div class="no_list_other_text">When people join that love the same shows as you,
                 you’ll start getting recommendations. 
                <span><a href="{{route('add-friend')}}">Invite friends.</a></span> 
            </div>
        </div> 
        @endif
        
         
 
    </div>
</div>

@endsection