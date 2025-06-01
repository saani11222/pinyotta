@extends('layout.master')

@section('content')
<style>
    .why-site{
        font-weight: 700;
        font-size: 14px;
        line-height: 100%;
    }
    ._how_it_work{
        font-weight: 400;
        font-size: 14px;
        line-height: 100%;
    }

</style>
<div style="display: flex;justify-content:center">
    <div class="dashboard">
        <div class="how_it_work">
            <div class="signup_heading ">About Pinyotta</div>
            <div class="how_it_work_text">
                <div class="_how_it_work">
                    <div class="why-site"> Why This Site Exists</div>
                    There’s more content, more choice, and more noise than ever.
                    <br><br>
                    Finding something genuinely worth your time — something aligned with your taste, your interests, your standards — has become increasingly harder.
                    <br><br>
                    This site exists to solve that problem. 
                    <br><br>
                    By starting with what you already love and connecting with friends in a new way, Pinyotta surfaces recommendations that are likely to resonate.
                </div>
                <div class="_how_it_work">
                    <div class="why-site"> The Pinyotta name</div>
                    The name Pinyotta is a play on the word Piñata, which represents community, celebration, and surprise (like discovering a hidden gem of a show or restaurant)! The word Pin signifies "pinning" things you love to your account and a Yottabyte is the largest known unit of data (1024 bytes), symbolizing the massive amount of content we seek to organize.
                </div>

            </div>
        </div>
    </div>
</div>
@endsection











