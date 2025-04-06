<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pinyotta</title>
    <link rel="shortcut icon" href="{{asset('assets/img/pinyotta.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('assets/css/pinyotta.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">


</head>

<body>
    {{-- <div class="popup_model_overlay" >
        <div class="overlay_hide">
            <img id="overlay_hide" width="20px"  height="19px"src="{{asset('assets/img/x.png')}}" alt="">
        </div>
        <div class="model_container">
            <div class="model_container_inner">
                <div class="model_content_box _addi"></div>
            </div>
            
        </div>
    </div> --}}
    <header>
        @include('layout.include.header')
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
    @include('layout.include.footer')
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
        integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function () {
            // var height = $('header').height();
            // $($('header')).height(height);
            $('#overlay_hide').on('click', function(){
                $('.popup_model_overlay').hide()
            });

        // on click account button
        $('#account').on('click', function(){
            $('.model_content_box').addClass('serach_model_content_box');
        $('.popup_model_overlay').show();
        $('.model_content_box').html(`
        
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
            
        `);
        });
        });
    </script>
    @yield('script')
</body>

</html>