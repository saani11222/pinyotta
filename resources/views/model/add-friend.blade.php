@extends('layout.model')

@section('model_content')
<div class="invite_model_box">
    <div>
        <div class="friend_name" style="line-height: 1.5; font-size: 24px">Add Friend</div>
        <div class="friend_invite_text">Invite friends and see each other’s shows.</div>
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
    <button class="add_friend_ btn_bg_gray ">Share invite</button>
    </div>
</div>
@endsection
@section('model_script')
<script>

    $(document).ready(function () {
       
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
            $(document).on('click', '.shareInvitation' , function(){
                var firstName= $('#first_name').val().trim();
                var text = `Hey ${firstName}, I’m using Pinyotta to discover TV shows and I wanted to invite you to be a friend so we can share shows we love with each other!`;
                copyToClipboard(text);
                $(this).html('Invitation copied to clipboard!');
            });
        
    });
</script>

@endsection