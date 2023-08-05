@if($message->message_type == 1)
    <a href = "{!!   $message->generateURL($me->id)   !!}" target="_blank" class = "text-danger mar-top-5 text-bold pt-15 btn-link"> {!! $message->getDetailLabel() !!} </a>
@elseif($message->message_type == 3) 
    <a href = "{!!   $message->generateURL($me->id)   !!}" target="_blank" class = "text-danger mar-top-5 text-bold pt-15 btn-link"> {!! $message->getDetailLabel() !!} </a>
@elseif($message->message_type == 4) 
<a href = "{!!   $message->generateURL($me->id)   !!}" target="_blank" class = "text-danger mar-top-5 text-bold pt-15 btn-link"> {!! $message->getDetailLabel() !!} </a>
@endif