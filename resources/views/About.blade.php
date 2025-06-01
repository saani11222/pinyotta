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
                    <div class="why-site"> Why this site exists</div>
                    At its best, watching a great TV show isn’t just entertainment — it’s connection. It’s stepping into
                    someone else’s world, feeling what they feel, and walking away just a little more human. Whether
                    it’s a moment that makes you laugh out loud, a plot twist that blows your mind, or a character who
                    feels like a friend, these stories stay with us.
                    <br><br>

                    We believe that when a show truly resonates with you, it doesn’t waste your time — it enriches it.
                    It gives your mind something to chew on, your heart something to hold, and sometimes, even brings
                    people together in ways you didn’t expect.
                    <br><br>
                    That’s why we built this place: to help you find the shows that speak to you. Because life’s too
                    short to watch shows that you don’t love.
                </div>
                <div class="_how_it_work">
                    <div class="why-site"> The Pinyotta name</div>
                    The name Pinyotta is a play on the word Piñata, which represents community, celebration, and
                    surprise (like discovering a hidden gem of a show)! The word Pin signifies “pinning” shows to your
                    account and a Yottabyte is the largest known unit of data (10²⁴ bytes), symbolizing the massive
                    amount of content we seek to organize.
                </div>

            </div>
        </div>
    </div>
</div>
@endsection