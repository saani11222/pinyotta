

@extends('admin.layouts.master')
@section('content')

        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form method="post" action="{{ url('/admin/update-admin/'.$admin->id) }}" enctype="multipart/form-data">
                  @csrf
                  <div class="card-header">
                    <h4>Edit Admin</h4>
                    <a href="{{ route('admin.view-admin') }}" style="margin-left:78%;" class="btn btn-success">View Admin</a>
                  </div>
                  <div class="card-body">
                   <div class="row">
                    
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" placeholder="Name" value="{{ $admin->name }}" class="form-control @error('name') is-invalid @enderror" required>
                                
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                <strong style="color:red; display:flex;">{{ $message }}</strong>
                                </span>
                                @enderror 
                            </div>
                                
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input name="email" type="email" value="{{ $admin->email }}" class="form-control @error('email') is-invalid @enderror" required>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                <strong style="color:red; display:flex;">{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input name="password" type="text" class="form-control @error('password') is-invalid @enderror">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                <strong style="color:red; display:flex;">{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Select Role</label>
                                <select name="role_id" class="form-control" required>
                                @foreach($roles as $role)
                                <option value="{{$role->id}}" {{ $role->id ==  $admin->role_id ? 'selected' : ''}}>{{$role->role}}</option>
                                @endforeach
                                </select>

                                @error('role_id')
                                <span class="invalid-feedback" role="alert">
                                <strong style="color:red; display:flex;">{{ $message }}</strong>
                                </span>
                                @enderror
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


@endsection