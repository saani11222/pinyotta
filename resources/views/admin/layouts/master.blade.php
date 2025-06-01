<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Pinyotta - Admin Dashboard</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets_admin/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets_admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_admin/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('assets_admin/css/custom.css') }}">
    <link rel="shortcut icon" href="{{asset('assets/img/pinyotta.png')}}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{asset('assets/img/pinyotta.png')}}" sizes="180x180">
    <link rel="stylesheet" href="{{ asset('assets_admin/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_admin/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            @include('admin.layouts.navbar')

            @include('admin.layouts.sidebar')

            <section class="main-content">
                @yield('content')
            </section>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            @if(Session::has('success'))
            <script>
            swal("Success", "{{session('success')}}", "success");
            </script>
            @endif

            @if(Session::has('error'))
            <script>
            swal("Error", "{{session('error')}}", "error");
            </script>
            @endif

            @include('admin.layouts.footer')


        </div>
        

        <style>
            .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #ced4da;
            }
            .select2-container--default .select2-selection--multiple {
            border: 1px solid #ced4da;
            }
            .select2-container{
                width:100%!important;
            }
            .select2-selection--multiple{
            min-height: 40px!important;
            }
            .select2-selection_choice_display{
            padding-left: 16px!important;
            }
            .select2-container .select2-search--inline{
            height: 0px!important;
            }
            .select2-selection__choice__display{
            color: black;
            }
            .select2-container .select2-search--inline textarea{
            height: 0px!important;
            }
        </style>


        <style>
            .modal-backdrop{
                position: unset!important;
            }
            .modal-backdrop.show{
                opacity: 0!important;
            }
            .modal-dialog{
                margin-top: 115px!important;
            }
        </style>
        
        
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <!-- General JS Scripts -->
        <script src="{{ asset('assets_admin/js/app.min.js') }}"></script>
        <!-- JS Libraies -->
        <script src="{{ asset('assets_admin/bundles/apexcharts/apexcharts.min.js') }}"></script>
        <!-- Page Specific JS File -->
        <script src="{{ asset('assets_admin/js/page/index.js') }}"></script>
        <!-- Template JS File -->
        <script src="{{ asset('assets_admin/js/scripts.js') }}"></script>
        <!-- Custom JS File -->
        <script src="{{ asset('assets_admin/js/custom.js') }}"></script>
        <script src="{{ asset('assets_admin/bundles/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('assets_admin/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
        
        <script>
            $(document).ready( function () {
                $('#table-1').DataTable();
            });
        </script>
    
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#categoryportfolio').select2({
                placeholder: "Select Category",
                allowClear: true
                });
            });
            $(document).ready(function(){
                $('#technologiesporfolio').select2();
            });
        </script>
        

        @stack('scripts')
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->

</html>
