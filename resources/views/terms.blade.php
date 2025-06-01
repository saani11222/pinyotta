@extends('layout.master')

@section('content')
<div style="display: flex;justify-content:center">
    <div class="dashboard">
        <div class="how_it_work">
            <div class="signup_heading ">How it works</div>
            <div class="how_it_work_text" >
                <div class="how_it_work_">Our algorithm is based on the idea that the best recommendations come from
                    people
                    that have the same taste
                    as you.</div>
                <div class="how_it_work__">Our system finds other people that love the same shows as you and then
                    recommends
                    shows that they’ve
                    watched but you haven’t.</div>
                <div class="how_it_work__">For example, if you and another user each have 10 shows listed as shows you
                    love
                    and 9 of those shows are
                    the same, it is highly likely that you’ll both like the show that the other hasn’t watched.</div>
            </div>
        </div>
    </div>
</div>
@endsection