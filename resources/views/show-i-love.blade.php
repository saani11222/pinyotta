
@extends('layout.master')

@section('content')
<div style="display: flex;justify-content:center;flex-wrap: wrap;">
    <div class="signup_page dashboard">
        <div class="signup_text">
            <div class="signup_heading">Shows I love</div>
            <div class="signupText">The more shows you add, the better your recommendations will be.</div>
        </div>
        <div style="width: 100%">
            <div style="width:100%">
                @foreach ( $showsList ?? [] as $show  )
                <div class="selected_item_box" id="selected_item_box">
                    <img width="40px" height="60px" src="{{$show->image}}" alt="">
                    <div class="itemBox">
                        <div class="item_name shows_loved" style="line-height: 1.5;">{{$show->name}}
                            {{-- <div style="color:#7B7F83; font-weight:600; font-size:11px; line-height: 100%;">2000, Comedy
                            </div> --}}
                            <div class="action_anchr" >
                                <img width="10px" height="10px" src="{{asset('assets/img/x.png')}}" alt="" style="cursor: pointer">
                            </div>
                        </div>
                        
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="dashboard">
        <a href="{{route('add-show')}}" type="button" class="add_more_show btn_bg_blue mg-t" >Add a show</a>
    </div>
</div>
@endsection

