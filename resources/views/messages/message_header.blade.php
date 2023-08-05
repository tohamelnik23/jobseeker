<div class="media-left">
    <img class="img-circle img-xs" src="{!! $to_user->getImage() !!}" alt = "{!! $to_user->accounts->name !!}">
</div>
<div class="media-body">
    <p class="mar-no text-main text-bold text-lg">{!! $to_user->accounts->name !!}</p>
    <small class="text-muteds typing_status hidden">Typing....</small>
</div>