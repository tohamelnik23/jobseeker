@php
    $client         = $message_list->getToUser(Auth::user()->id);
    $job            = $message_list->getJob(); 
    $last_message   = $message_list->getLastMessage(); 
    $total_messages = $message_list->caculateUnreadMessage(Auth::user()->id);  
@endphp

@if(!isset($show_content))
<a href="javacript:void(0)" class="@if($total_messages) chat-unread @endif chat-item-user {!! $message_list->serial !!} @if(isset($room_id))  @if($message_list->serial == $room_id)  first_be_click active   @if(isset($refresh)) init-active  @endif   @endif  @endif   " data-list = "{!! $message_list->serial !!}">
@endif
    <div class="media-left">
        <img class="img-circle img-sm" src="{!! $client->getImage() !!}" alt = "{!! $client->accounts->name !!}"> 
        <i class="badge badge-success badge-stat badge-icon pull-left message_list_new_stuff @if($total_messages) active  @endif"></i> 
    </div>
    <div class="media-body">
        <span class="chat-info">
            <span class="text-xs">{!! $last_message->created_at->diffForhumans() !!}</span> 
            <span class="badge badge-success message_list_new_stuff  message_list_total_messages  @if($total_messages) active  @endif">{!! $total_messages !!}</span> 
        </span>
        <div class="chat-text">
            <p class="chat-username mar-no">{!! $client->accounts->name !!}</p> 
            @if(isset($job))
                @if($message_list->type == "job")
                    <p class = "mar-no text-bold">{{ $job->headline }}</p>
                @elseif($message_list->type == "offer")
                    <p class = "mar-no text-bold">{{ $job->contract_title }}</p>
                @else  
                @endif
            @endif

            @if(isset($last_message))
                <p class = "mar-no last_message">{{ $last_message->message_content }}</p>
            @endif
        </div>
    </div> 
@if(!isset($show_content))
</a>
@endif