<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Everpage">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | Liquor Website</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('admin_asset/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{asset('admin_asset/css/metisMenu.min.css')}}" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="{{asset('admin_asset/css/timeline.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{asset('admin_asset/css/startmin.css')}}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{{asset('admin_asset/css/morris.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{asset('admin_asset/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link href="{{asset('admin_asset/css/dataTables/dataTables.bootstrap.css')}}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- DataTables Responsive CSS -->
    <link href="{{asset('admin_asset/css/dataTables/dataTables.responsive.css')}}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
    #side-menu a{
        color:black !important;
    }
</style>
</head>

<body>
  
   @include('admin.layouts.header')
   @yield('content')
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

   
    <script type="text/javascript" src='https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js'></script>
    <script>
    tinymce.init({
        selector: "#editor",
    });

    tinymce.init({
        selector: "#editor1",
    });

    tinymce.init({
        selector: "#editor_pro",
    });
    tinymce.init({
        selector: "#edit_pro_faqs",
    });
    </script>
 
  
    <!-- DataTables JavaScript -->
    <script src="{{asset('admin_asset/js/dataTables/jquery.dataTables.min.js')}}"></script>
            
    <script src="{{asset('admin_asset/js/dataTables/dataTables.bootstrap.min.js')}}"></script>
    @stack('body-scripts')
    <!-- jQuery -->
    <!-- <script src="{{asset('admin_asset/js/jquery.min.js')}}"></script> -->
    <script src="https://unpkg.com/maxlength-contenteditable@1.0.1/dist/maxlength-contenteditable.js"></script>
    <script>
        maxlengthContentEditableModule.maxlengthContentEditable();

    </script>


      <!-- PersonTable -->
    <!-- <script>



    
        $(document).ready(function() {
            $('#PersonTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url("/admin/get-person")}}',
                "columns": 
                [
                    {
                        "data": "Sr"
                    },
                    {
                        "data": "Url"
                    },
                    // {
                    //     "data": "Bio"
                    // },
                    {
                        "data": "Date"
                    },
                    {
                        "data": "Action"
                    }
                ],
                dataFilter: function(data) {
                var json = jQuery.parseJSON(data);
                json.recordsTotal = json.total;
                json.recordsFiltered = json.total;
                json.data = json.list;

                return JSON.stringify(json); // return JSON string
                }

             });
        });
    </script>

    <script>

    
        $(document).ready(function() {
            $('#dataTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url("/admin/get-question")}}',
                "columns": 
                [
                    {
                        "data": "Sr"
                    },
                    {
                        "data": "Title"
                    },
                    {
                        "data": "Placeholder"
                    },
                    {
                        "data": "Posistion"
                    },
                    {
                        "data": "Mark as fixed"
                    },
                    {
                        "data": "Action"
                    }
                ],
                dataFilter: function(data) {
                var json = jQuery.parseJSON(data);
                json.recordsTotal = json.total;
                json.recordsFiltered = json.total;
                json.data = json.list;

                return JSON.stringify(json); // return JSON string
                }

             });
        });
    </script>

    <script>


        $(document).ready(function() {
            



             $('#dataTablelinks').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url("/admin/get-links")}}',
                "columns": 
                [
                    {
                        "data": "Sr"
                    },
                    {
                        "data": "link_name"
                    },
                    {
                        "data": "title"
                    },
                    {
                        "data": "discription"
                    },
                    {
                        "data": "Action"
                    }
                ],
                dataFilter: function(data) {
                var json = jQuery.parseJSON(data);
                json.recordsTotal = json.total;
                json.recordsFiltered = json.total;
                json.data = json.list;

                return JSON.stringify(json); // return JSON string
                }

             });
        });
    </script> -->
    
    <!-- UsersnoterifyTable -->

    <!-- <script>
        $(document).ready(function() {
            $('#UsersnoterifyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url("/admin/get-noverify-users")}}',
                "columns": 
                [
                    {
                        "data": "Sr"
                    },
                    {
                        "data": "Name"
                    },
                    {
                        "data": "Email"
                    },
                    {
                        "data": "Status"
                    },
                    {
                        "data": "Action"
                    }
                ],
                dataFilter: function(data) {
                var json = jQuery.parseJSON(data);
                json.recordsTotal = json.total;
                json.recordsFiltered = json.total;
                json.data = json.list;

                return JSON.stringify(json); // return JSON string
                }

             });
        });
    </script> -->
    
    <script>
    $(document).ready(function(){

        $('.select2').select2({
        
        });

        $('.select2ProductView').select2({
        
       });

        $('.select3').select2({
        
        });

        $('.selectinmodel').select2({
        
        });

        $('.selectcolumn').select2({
            placeholder: 'Select column',
            allowClear: true, // This allows clearing the selection
        });

        $('.js-example-basic-single').select2();

    });
    
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('admin_asset/js/bootstrap.min.js')}}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{asset('admin_asset/js/metisMenu.min.js')}}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{asset('admin_asset/js/startmin.js')}}"></script>

</body>
</html>
