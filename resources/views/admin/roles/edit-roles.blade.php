@extends('admin.layouts.master')
@section('content')
      

      <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form method="post" action="{{ url('/admin/update-role/'.$role->id) }}" enctype="multipart/form-data">
                  @csrf
                  <div class="card-header">
                    <h4>Edit Role</h4>
                    <a href="{{ url('/admin/view-role') }}" style="margin-left:78%;" class="btn btn-success">View Role</a>
                  </div>
                        <div class="card-body">

                              <div class="row">


                                  <div class="col-lg-6">
                                          <div class="form-group">
                                              <label>Role</label>
                                              <input name="role" placeholder="Role" value="{{ $role->role }}" class="form-control @error('role') is-invalid @enderror">
                                              
                                                @error('role')
                                                <span class="invalid-feedback" role="alert">
                                                <strong style="color:red;display: flex">{{ $message }}</strong>
                                                </span>
                                                @enderror 
                                          </div>  
                                  </div>

                                  <style>
                                    .btn:focus {
                                        outline: thin dotted;
                                        outline: 5px auto #EEEEEE!important;
                                        outline-offset: -2px;
                                    }
                                  </style>

                                  <div class="col-12 col-md-12 col-lg-12">
                                    
                                        <div id="accordion">

                                          <div style="display:flex;">
                                            <label style="margin-bottom: 0px;">Check all permissions:</label>
                                            <input type="checkbox" style="width: 22px; height: 16px;margin-top: 3px;" name="all_select" onclick="Allselect(event)">
                                          </div><br>

                                          @foreach($modules as $module)
                                          @if($module->menu_item == '1')

                                            <div class="accordion">
                                              <div class="accordion-header" role="button" data-toggle="collapse" data-target="#{{$module->id}}panel"
                                                aria-expanded="true">
                                                <!-- <span class="fa fa-caret-left" style="float: right;"></span> -->
                                                <h4>{{ $module->name }}</h4>
                                              </div>
                                              <div class="accordion-body collapse show" id="{{ $module->id}}panel" data-parent="#accordion">
                                                
                                                @foreach($module->pages as $key => $page)
                                                    <div class="" style="margin-left: 20px;">
                                                        <input class="form-check-input" name="admin_module_page_id[]" type="checkbox" value="{{ $page->id }}" id="{{ $page->id }}" data-module-id="{{ $module->id }}" @if(in_array($page->id, $savedPermissions)) checked @endif>
                                                        <input type="hidden" name="module_id[{{ $page->id }}]" value="{{ $module->id }}">
                                                        <input type="hidden" name="page_route[{{ $page->id }}]" value="{{ $page->page_route }}">
                                                        <label class="form-check-label" for="{{ $page->page_name }}">
                                                            {{ $page->page_name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                
                                              </div>
                                            </div>
                                          
                                          @endif
                                          @endforeach
                                          
                                        </div>

                                      
                                  </div>
                                
                                  <div class="card-footer text-right">
                                    <button class="btn btn-primary mr-1" type="submit">Update</button>
                                    <!-- <button class="btn btn-secondary" type="reset">Reset</button> -->
                                  </div>
                                

                              </div>
                        </div>
                  </form>
              </div>
                
                
            </div>
              
          </div>
        </div>
      </section>


	    
      <script>
        $(document).ready(function() {
          $('.collapse').on('shown.bs.collapse', function() {
            $(this).prev().find('.fa').removeClass('fa-caret-left').addClass('fa-caret-down');
          });
          $('.collapse').on('hidden.bs.collapse', function() {
            $(this).prev().find('.fa').removeClass('fa-caret-down').addClass('fa-caret-left');
          });
        });
      </script>

      <script>
        function Allselect(event){

          var checkboxes = document.querySelectorAll(".form-check-input");

          // Loop through the checkboxes and set their "checked" property to true
          for (var i = 0; i < checkboxes.length; i++) {
              checkboxes[i].checked = true;
          }

        }
      </script>


@endsection