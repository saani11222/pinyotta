@extends('admin.layouts.master')
@section('content')


<div id="wrapper">

     <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Admin Dashborad</h1>
                        </div>
                    </div>
                    

            <!-- ... Your content goes here ... -->
        </div>
    </div>

</div>

   <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <script>

        $(document).ready(function() {
            var startDate = $('#start').val();
            var endDate = $('#end').val();
            // alert(start_date);
            $('#dataTableQueries').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url("/admin/get-search-queries")}}?start_date=' + startDate + '&end_date=' + endDate,
                type:'get',
                "columns": 
                [
                    {
                        "data": "Sr"
                    },
                    {
                        "data": "Query"
                    },
                    {
                        "data": "# Queries"
                    },
                    // {
                    //     "data": "Action"
                    // }
                ],
                drawCallback: function(data) {
                var json = data.json;
                json.recordsTotal = json.total;
                json.recordsFiltered = json.total;
                json.data = json.list;
                $('#query_total').html(json.query_count);
                return JSON.stringify(json); // return JSON string
                }

             });

        });

    </script>

@endsection