{!! $offer->contract_title !!}
{!! $offer->work_details !!}
@if($offer->payment_type == "hourly") 
    <span class = "text-bold"> Rate: </span>  ${!! $offer->amount !!}/hr
    <span class = "text-bold"> Limit: </span> {!! $offer->weekly_limit !!} hrs/week
@else
    <span class = "text-bold"> Est. Budget:  </span>  ${!! $offer->amount !!}
@endif