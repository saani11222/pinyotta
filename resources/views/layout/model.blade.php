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
        <!-- Notyf CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
        <style>
        /* Overlay container */
        #loaderContainer {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* semi-transparent black */
        display: none; /* hidden by default */
        align-items: center;
        justify-content: center;
        z-index: 9999; /* on top of everything */
        }

        /* Spinner itself */
        .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #ccc;
        border-top-color: #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        }

        @keyframes spin {
        to { transform: rotate(360deg); }
        }

        /* Show overlay when .loading is added */
        .loading {
        display: flex !important; /* flex to center the spinner */
        }
    </style>
    </head>
</head>
<body>
    <div id="loaderContainer">
        <div class="spinner"></div>
    </div>
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
    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    
    

    
        <script>
            function goBack() {
                if (document.referrer) {
                    window.location.href = document.referrer; // Go to the referring page
                } else {
                    window.history.back(); // Use browser history
                }
            }
        </script>
    

    <script>
            // global function for call the ajax for shows move to watchlist or shows i love action perfom in dropdown buttons 
            function dropdownAjaxRequest(url,id,count=null,message=null, html=null,removeItem=null,type=null) {
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: id,
                    type:type,
                    _token: "{{csrf_token()}}"
                },
                success: function (response) {
                    $('#loaderContainer').removeClass('loading');
                    if (message){
                        notyf.success(message);
                    } 

                    if (removeItem && removeItem.length) {
                        removeItem.remove();
                    } 
                    // removeItem.remove();
                    // if (count == 0) {
                    //     $('.recommendation_box').html(html);
                    // }
                    if (count !== null && count == 0 && html) {
                        $('.recommendation_box').html(html);
                    }
                    if(response.unfriend){
                        setTimeout(() => {
                            window.location.href = '{{route('friends')}}';
                        }, 2000);
                    }

                },
                error: function (xhr, status, error) {
                    $('#loaderContainer').removeClass('loading');
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                    notyf.error(errorMessage);
                    // console.error('AJAX error:', xhr.responseText);
                }
            });
        } 

    </script>
    <script>
        const notyf = new Notyf({
            duration: 2000,
            ripple: true,
            position: {
                x: 'right',
                y: 'top',
            },
            types: [
                {
                type: 'success',
                background: '#0B99FF',
                icon: {
                    className: 'notyf__icon--success',
                    tagName: 'i',
                }
                },
                {
                type: 'error',
                background: '#f44336',
                icon: {
                    className: 'notyf__icon--error',
                    tagName: 'i',
                }
                }
            ]
            });
    </script>
     @yield('model_script')
</body>
</html>
