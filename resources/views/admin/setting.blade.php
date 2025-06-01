@extends('admin.layouts.master')
@section('content')
      

      <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form method="post" action="{{ route('admin.create-setting') }}" enctype="multipart/form-data">
                  @csrf
                 
	                    <div class="card-header">
	                      <h4>Setting</h4>
	                    </div>
                        <div class="card-body">
                              <div class="row">

                                  <div class="col-lg-6">
                                      <div class="form-group">
                                          <label>Ai Type</label>
                                          <select name="type" class="form-control" required>
                                              <option>Select</option>
                                              <option {{ @$setting->type == 'chatgpt' ? 'selected="selected"' : '' }} value="chatgpt">ChatGPT</option>
                                              <option {{ @$setting->type == 'gemini' ? 'selected="selected"' : '' }} value="gemini">Gemini</option>
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

                                  <div class="card-footer text-right" style="margin-top: 10px;">
                                    <button class="btn btn-primary mr-1" type="submit">Submit</button>
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
          
    </script> 


@endsection