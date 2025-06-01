@extends('layout.model')

@section('model_content')
<div class="invite_model_box">
    <div>
        <div class="friend_name" style="line-height: 1.5; font-size: 24px">Add Friend</div>
        <div class="friend_invite_text">{{$text}}</div>
    </div>
    <div class="invite_input_box">
        <div>
            <input type="text" name="first_name" id="first_name" class="input_invite" placeholder="Friend’s first name">
        </div>
        <div>
            <input type="text" name="last_name" id="last_name" class="input_invite" placeholder="Friend’s last name">
        </div>
    </div>
    <div>
    <button class="add_friend_ btn_bg_gray ">Generate invite link</button>
    </div>
    <div class="guide_text" style="font-weight: 500;font-size: 12px;color: #A1A1A1;text-align:center;" ></div>
</div>
@endsection
@section('model_script')
<script>

    $(document).ready(function () {
        const id = {{Auth::user()->id }};
        function checkInvite(){
                var $firstName= $('#first_name').val().trim();
                var $lastName = $('#last_name').val().trim();
                let button =  $('.add_friend_');
                if($firstName && $lastName){
                    button.removeClass('btn_bg_gray');
                    button.addClass('btn_bg_blue shareInvitation');
                }else{
                    button.removeClass('btn_bg_blue shareInvitation');
                    button.addClass('btn_bg_gray');
                }

            }
            
            $('#first_name').on('input', checkInvite);
            $('#last_name').on('input', checkInvite);
        // share and copy to clipboard invitation
            function copyToClipboard(text) {
            const tempInput = $('<textarea>'); // Create a temporary textarea
            $('body').append(tempInput);      // Append to the body
            tempInput.val(text).select();     // Set value and select it
            document.execCommand('copy');     // Copy to clipboard
            tempInput.remove();               // Remove the textarea
            }
            // generate unique random string
            function generateUniqueString() {
                const randomPart = Math.random().toString(36).substring(2, 15) + 
                                Math.random().toString(36).substring(2, 15);
                const timestamp = Date.now().toString(36); // current time in ms
                return randomPart + timestamp;
            }

            $(document).on('click', '.shareInvitation' , function(){
                var firstName= $('#first_name').val().trim();
                var lastName = $('#last_name').val().trim();
                var  string = generateUniqueString();
                // Hey ${firstName},
                var shareText = '{{$shareText}}';
                var text = `${shareText}\nhttps://pinyotta.com?fid=${string}`;
                copyToClipboard(text);
                $(this).html('Invitation link copied to clipboard!');
                $('.guide_text').html('Share the link with ' + firstName );
                
                $.ajax({
                    type: "post",
                    url: "{{route('saveInvitations')}}",
                    data: {
                        name: firstName + " " + lastName,
                        id: id,
                        invite_token : string,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        // setTimeout(() => {
                        //     goBack(); 
                        // }, 2000);
                        $('.add_friend_').removeClass('shareInvitation');
                        $('.add_friend_').on('click',function(){
                            copyToClipboard(text);
                        });
                    }
                });

            });
        
    });
</script>

@endsection