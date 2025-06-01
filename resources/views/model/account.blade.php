@extends('layout.model')
@section('model_content')
<div class="invite_model_box no_border">
    <div class="model_friends_show_list_top">
        <div class="model_firend_show account_model">
            <div class="friend_name" style="line-height: 1.5;">Account</div>
        </div>
        <div>
            <div class="btn-group">
                <div class="three_dots" data-bs-toggle="dropdown" aria-expanded="false"><img height="3px"
                        src="{{asset('assets/img/ellipsis.png')}}" alt=""> </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><button class="dropdown-item delete_account" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Delete account</button></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="invite_input_box">
            <div>
                <input type="text" value="{{explode(" ", $user->name)[0]}}" name="first_name" id="first_name" class="input_invite">
            </div>
            <div>
                <input type="text" value="{{explode(" ", $user->name)[1]}}" name="last_name" id="last_name" class="input_invite">
            </div>
        </div>
        <div class="signin_text">You are signed in with Google using <span>{{$user->email}}</span></div>
        <div>
        <button class="add_friend_ btn_bg_blue change_name ">Done</button>
        </div>
    </div>
    {{-- confirm model --}}
  
  <!-- Modal -->
  <style>
    .modal-backdrop{
        z-index: 0!important;
    }
    .modal-footer{
        padding: 3px!important;
    }
  </style>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                Are you sure you want to permanently delete your account?
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            <button type="button" class="btn btn-primary delete_account_yes" data-bs-dismiss="modal">Yes</button>
            </div>
        </div>
        </div>
    </div>
@endsection

@section('model_script')
<script>
    $(document).ready(function () {
        $(document).on('click','.change_name',function(){
            var first_name = $('input[name=first_name]').val();
            var last_name = $('input[name="last_name"]').val();
            var fullName = `${first_name} ${last_name}`;
                $.ajax({
                    type: "post",
                    url: "{{route('saveUserName')}}",
                    data: {
                        name: fullName,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        notyf.success('Successfully Updated!');
                        
                    },
                    error: function (xhr, status, error) {
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update';
                    notyf.error(errorMessage);
                    }
                });
        });

        $(document).on('click', '.delete_account_yes', function () {
            $.ajax({
                    type: "get",
                    url: "{{route('delete-user-account')}}",
                    data: {
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        notyf.success('Account has been deleted!');
                        setTimeout(function () {
                            window.location.href = "{{route('index')}}";
                        }, 2000);

                    },
                    error: function (xhr, status, error) {
                    const errorMessage = xhr.responseJSON?.message || 'Failed to Operate';
                    notyf.error(errorMessage);
                    }
                });
        });

    });
</script>
@endsection
