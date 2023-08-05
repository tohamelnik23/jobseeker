@for($i = count($messages) -1; $i >= 0 ; $i--)
    @php
        $message = $messages[$i];
    @endphp 
    @if($message->from_user == $me->id)
        <div class="chat-me chart-message-item {!! $date !!} mar-top" data-serial = "{!! $message->serial !!}">
            <div class="media-left">
                <img src="{!! $me->getImage() !!}" class="img-circle img-sm" alt="Profile Picture">
            </div>
            <div>
                <p class = "text-bold text-dark mar-top-5">{!! $me->accounts->name !!}</p>
            </div>
            <div class="media-body"> 
                <div>
                    <p>
                        {!! nl2br($message->message_content) !!}
                        <small>{!! date('h:i A', strtotime($message->message_sendtime)) !!}</small>  
                        @include('messages.partial.message_target_detail') 
                    </p> 
                </div>
            </div>
        </div>
    @else
        <div class="chat-user chart-message-item {!! $date !!} mar-top"  data-serial = "{!! $message->serial !!}">
            <div class="media-left">
                <img src="{!! $to_user->getImage() !!}" class="img-circle img-sm" alt="Profile Picture">
            </div>
            <div>
                <p class = "text-bold text-dark mar-top-5">{!! $to_user->accounts->name !!} </p>
            </div>
            <div class="media-body">  
                <div>
                    <p>
                        {!! nl2br($message->message_content) !!}
                        <small>{!! date('h:i A', strtotime($message->message_sendtime)) !!}</small>
                        @include('messages.partial.message_target_detail') 
                    </p>
                </div>
            </div>
        </div>
    @endif
@endfor 