@forelse($message_lists as $message_list) 
    @include('messages.partial.message_list_user_item') 
@empty
<a href="javascrit:void(0)" class="chat-unread"> 
    <div class="media-body"> 
        <div class="chat-text"> 
            <p class = "mar-no text-bold"> <i> No users to chat <i></p> 
        </div>
    </div>
</a> 
@endforelse