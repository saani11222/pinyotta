<!DOCTYPE html>
<html lang="en">
<head>
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
</head>
<body>
    <div class="popup_model_overlay" >
        <div class="overlay_hide">
           <a href="javascript:void(0);" onclick="goBack()"> <img id="overlay_hide" width="20px"  height="19px"src="{{asset('assets/img/x.png')}}" alt=""></a>
        </div>
        <div class="model_container">
            <div class="model_container_inner">
                <div class="model_content_box _addi">
                    @yield('model_content')
                </div>
            </div>
            
        </div>
        <footer>
            @include('layout.include.footer')
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
        integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
        <script>
            function goBack() {
                if (document.referrer) {
                    window.location.href = document.referrer; // Go to the referring page
                } else {
                    window.history.back(); // Use browser history
                }
            }
        </script>
     @yield('model_script')
</body>
</html>
