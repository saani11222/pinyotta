@extends('admin.layouts.master')
@section('content')

        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>View Role</h4>
                    <a href="{{ url('admin/add-role') }}" style="margin-left: 78%;" class="btn btn-success"> Add Role</a>
                  </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Sr</th>
                                        <th>Name</th>
                                        <!-- <th>Bio</th> -->
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
         
                    </div>
                </div>
              </div>
            </div> 
          </div>
        </section>
            
            <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		    <script>
		    
		        $(document).ready(function() {
		            $('#table-1').DataTable({
		                processing: true,
		                serverSide: true,
		                ajax: '{{url("/admin/get-role")}}',
		                "columns": 
		                [
		                    {
		                        "data": "Sr"
		                    },
		                    {
		                        "data": "Name"
		                    },
		                    // {
		                    //     "data": "Bio"
		                    // },
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

		    </script>

            <script>
                function block(id) {
                    var val = $("#option" + id).data('val');
                    $('#option' + id).prop('disabled', true);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'post',
                        url: '{{ url("/admin/role-status") }}',
                        data: {
                            id: id,
                            val: val
                        },
                        success: function(data) {
                            data = JSON.parse(data);
                            // console.log(data.value);return false;
                            if (data.value == 1) {
                                $("#option" + data.id).removeClass('btn-success');
                                $("#option" + data.id).addClass('btn-danger');
                                $("#option" + data.id).data('val',0);
                                $("#option" + data.id).html('Inactive');
                                $('#option' + data.id).prop('disabled', false);
                                swal("Success", "Status Successfully Inactive", "success");
                            } else {
                                $("#option" + data.id).removeClass('btn-danger');
                                $("#option" + data.id).addClass('btn-success');
                                $("#option" + data.id).data('val',1);
                                $("#option" + data.id).html('Active');
                                $('#option' + data.id).prop('disabled', false);
                                swal("Success", "Status Successfully Active", "success");
                            }

                        }

                    });

                }
           </script>

@endsection