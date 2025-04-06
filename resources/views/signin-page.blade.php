@extends('layout.master')

@section('content')
<div class="signup_page">
    <div class="signup_text">
        <div class="signup_heading">Welcome back!</div>
        <div class="signupText">See your recommendations, friends’ shows, watchlist, and more.</div>
    </div>
    <div>
        <div>
            <a class="google_button" href="{{ route('google.login') }}">
            <div><img height="23px" src="{{asset('assets/img/g-icon.png')}}" alt=""></div>
            <div class="g-text">Continue with Google</div>
            </a>
        </div>
    </div>
</div>
@endsection