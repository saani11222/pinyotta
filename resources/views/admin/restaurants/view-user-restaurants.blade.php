@extends('admin.layouts.master')
@section('content')

	<section class="section">
	  <div class="section-body">
	    <div class="row">
	      <div class="col-12">
	        <div class="card">
	          <div class="card-header">
	            <h4>User Restaurants</h4>
	          </div>
	          <div class="card-body">
	            <div class="table-responsive">
	              <table class="table table-striped table-bordered" id="shows-table">
	                <thead>
	                  <tr>
	                    <th>Sr</th>
	                    <th>Name</th>
	                    <th>City</th>
	                    <!-- <th>Image</th> -->
	                  </tr>
	                </thead>
	                <tbody></tbody>
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
	            $('#shows-table').DataTable({
	                processing: true,
	                serverSide: true,
	                ajax: {
				        url: '{{ url("/admin/get-user-restaurants") }}',
				        data: {
				            user_id: '{{ request()->id }}',
				        }
				    },
			        "columns": 
	                [
	                    {
	                        "data": "Sr"
	                    },
	                    {
	                        "data": "Name"
	                    },
	                    {
	                        "data": "City"
	                    },
	                    // {
	                    //     "data": "Image"
	                    // },
	                    
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

@endsection
