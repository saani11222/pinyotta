@extends('admin.layouts.master')
@section('content')
      

      <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form method="post" action="{{ route('admin.preinstructions',@$id) }}" enctype="multipart/form-data">
                  @csrf
                 
	                    <div class="card-header">
	                      <h4>Pre Instruction</h4>
	                    </div>
                        <div class="card-body">

                              <div class="row">

                                  <div class="col-lg-6">
                                        <div class="form-group">
                                              <label>Type</label>
                                              @php
                                                  $selected_type = @$setting->type;
                                                    if(@$_GET['type']){
                                                        $selected_type = $_GET['type']; 
                                                    }
                                              @endphp
                                              <select name="type" class="type_pre_instruction form-control" required>
                                                
                                                  
                                                  <option {{$selected_type == 'shows' ? 'selected="selected"' : ''}} value="shows">Shows</option>
                                                  <option {{$selected_type == 'restaurants' ? 'selected="selected"' : ''}} value="restaurants">Restaurants</option>
                                                  
                                              </select>
                                        </div>

                                  </div>

                                  <div class="col-lg-12">
                                      <div class="form-group">
                                            <label>Pre Instruction</label>
                                            <textarea name="description" rows="15" cols="10" style="height: 100% !important;" class="form-control">{{ @$setting->description }}</textarea>
                                      </div> 
                                  </div>

                                  <input type="hidden" name="id" value="{{$setting ? @$setting->id : ''}}"/>

                                  <div class="col-lg-6">
                                      <div class="form-group">
                                            <label>Model</label>
                                            <select name="model_name" class="form-control" required>
                                                <option>Select</option>
                                                

                                                <option {{@$setting->model_name == 'gpt-4.1' ? 'selected="selected"' : ''}} value="gpt-4.1">gpt-4.1</option>
                                                <option {{@$setting->model_name == 'gpt-4' ? 'selected="selected"' : ''}} value="gpt-4">gpt-4</option>
                                                <option {{@$setting->model_name == 'gpt-4-1106-preview' ? 'selected="selected"' : ''}} value="gpt-4-1106-preview">gpt-4-1106-preview</option>
                                                <option {{@$setting->model_name == 'gpt-4-turbo-preview' ? 'selected="selected"' : ''}} value="gpt-4-turbo-preview">gpt-4-turbo-preview</option>
                                                <option {{@$setting->model_name == 'gpt-4-turbo' ? 'selected="selected"' : ''}} value="gpt-4-turbo">gpt-4-turbo</option>
                                                <option {{@$setting->model_name == 'gpt-4o' ? 'selected="selected"' : ''}} value="gpt-4o">gpt-4o</option>
                                                <option {{@$setting->model_name == 'gpt-4o-mini' ? 'selected="selected"' : ''}} value="gpt-4o-mini">gpt-4o-mini</option>


                                                <!-- <option {{@$setting->model_name == 'gpt-4-turbo' ? 'selected="selected"' : ''}} value="gpt-4-turbo">gpt-4-turbo</option> -->
                                            </select>
                                      </div>
                                  </div>

                                  <style>
                                          p {
                                            /*display: flex;*/
                                            align-items: center;
                                          }

                                          label {
                                            margin-right: 10px;
                                            font-weight: 600;
                                            color: #34395e;
                                            font-size: 12px;
                                            letter-spacing: 0.5px;
                                          }

                                          .slider_label {
                                            /*margin-left: 10px;*/
                                          }
                                  </style>

                                  <div class="col-lg-6">
                                      <div class="form-group">
                                          <p>
                                            <label for="range_weight">Temperature</label><br>
                                            <input type="range" style="margin-top: 3px;" name="temperature" class="slider form-control" min="0" max="1" value="{{ @$setting->temperature }}" step="0.1">
                                            <span class="slider_label"></span>
                                          </p>
                                      </div>   
                                  </div>

                                  <div class="col-lg-6">
                                      <div class="form-group">
                                          <p>
                                            <label for="range_weight">Maximum length</label><br>
                                            <input type="range" style="margin-top: 3px;" name="maximum_length" class="maximum_length form-control" min="0" max="128000" value="{{ @$setting->maximum_length }}" step="0">
                                            <span class="slider_maximum_length"></span>
                                          </p>
                                      </div>
                                  </div>

                                  <div class="col-lg-6">
                                      <div class="form-group">
                                          <p>
                                            <label for="range_weight">Top P</label><br>
                                            <input type="range" style="margin-top: 3px;" name="top_p" class="top_p form-control" min="0" max="1" value="{{ @$setting->top_p }}" step="0.1">
                                            <span class="slider_top_p"></span>
                                          </p>

                                      </div>
                                  </div>

                                  <div class="col-lg-6">
                                      <div class="form-group">
                                          <p>
                                            <label for="range_weight">Frequency penalty</label><br>
                                            <input type="range" style="margin-top: 3px;" name="frequency_penalty" class="frequency_penalty form-control" min="0" max="2" value="{{ @$setting->frequency_penalty }}" step="0.1">
                                            <span class="slider_frequency_penalty"></span>
                                          </p>
                                      </div>
                                  </div>

                                  <div class="col-lg-6">
                                      <div class="form-group">
                                          <p>
                                            <label for="range_weight">Presence penalty</label><br>
                                            <input type="range" style="margin-top: 3px;" name="presence_penalty" class="presence_penalty form-control" min="0" max="2" value="{{ @$setting->presence_penalty }}" step="0.1">
                                            <span class="slider_presence_penalty"></span>
                                          </p>
                                      </div>
                                  </div>

                                  <div class="col-lg-12">
                                      <div class="form-group">
                                          
                                            <label for="range_weight">System prompt</label>
                                            <textarea name="system_prompt" style="height: 146px;" class="form-control form-control">{{ @$setting->system_prompt }}</textarea>
                                      </div>                         
                                  </div>
                                  <!-- /.col-lg-6 (nested) --> 

                                  <div class="card-footer text-right">
                                    <button class="btn btn-primary mr-1" type="submit">Submit</button>
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


	    
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <script>
         
          $('.slider').on('input change', function(){
              $(this).next($('.slider_label')).html(this.value);
            });
          $('.slider_label').each(function(){
              var value = $(this).prev().attr('value');
              $(this).html(value);
          }); 

          $('.maximum_length').on('input change', function(){
              $(this).next($('.slider_maximum_length')).html(this.value);
            });
          $('.slider_maximum_length').each(function(){
              var value = $(this).prev().attr('value');
              $(this).html(value);
          });

          $('.top_p').on('input change', function(){
              $(this).next($('.slider_top_p')).html(this.value);
            });
          $('.slider_top_p').each(function(){
              var value = $(this).prev().attr('value');
              $(this).html(value);
          });

          $('.frequency_penalty').on('input change', function(){
              $(this).next($('.slider_frequency_penalty')).html(this.value);
            });
          $('.slider_frequency_penalty').each(function(){
              var value = $(this).prev().attr('value');
              $(this).html(value);
          });

          $('.presence_penalty').on('input change', function(){
              $(this).next($('.slider_presence_penalty')).html(this.value);
            });
          $('.slider_presence_penalty').each(function(){
              var value = $(this).prev().attr('value');
              $(this).html(value);
          });

          $(".type_pre_instruction").change(function(){
              var type =  $(this).val();
              window.location.href= "{{url('/admin/preinstructions')}}"+"/"+"{{@$id}}/?type="+type;
          });
          

    </script> 


@endsection