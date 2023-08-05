@if($client->checkPaymentMethod())
    <p class = "text-dark text-bold mar-btm"> 
        <img class = "checkbox-img" src = "{!! asset('img/checkbox.png') !!}" />    Payment method verified 
    </p> 
@else
    <p class = " text-bold mar-btm"> 
        Payment method unverified 
    </p>
@endif 

@php
    $total_reviews =  $client->totalReviews();
@endphp
@if($total_reviews)
    @php
        $feedback_point  =  $client->getFeedBackPoint();
    @endphp 
    <p  class = "total-rating" data-size = "20"  data-star = '{!! $feedback_point !!}'></p>
    <p class = "text-bold">  {!! $feedback_point !!} of {!! $total_reviews !!} @if($total_reviews == 1) review @else reviews @endif </p>
@endif

@if($client->accounts->state )
    <p class = "text-bold text-dark mar-no"> Location </p>
    <p class = ""> {!! $client->accounts->city  !!}, {!! $client->accounts->state  !!}</p>  
@endif

@php
    $total_job_posts    =  $client->total_job_posted();
    $total_open_jobs    =  $client->total_job_posted('open');
    $total_hired_jobs   =  $client->total_job_posted('hired'); 
@endphp
<p class = "text-bold text-dark mar-no">{!! $total_job_posts !!}  @if($total_job_posts == 1) gig @else gigs @endif posted </p>
<p class = " ">  {!! round($total_hired_jobs / $total_job_posts, 2) * 100 !!}% hire rate, {!! $total_open_jobs !!} open @if($total_open_jobs == 1)  gig @else gigs @endif </p>
@php
    $total_spent  =  $client->getTotalSpent(); 
    $spent_money  =  Mainhelper::getAroundAmount($total_spent);
    $total_hires  =  $client->getTotalOffer();
    $total_opens  =  $client->getTotalOffer('open');
@endphp
<p class = "text-bold text-dark mar-no"> ${!! $spent_money !!} total spent </p>
<p class = " "> {!! $total_hires !!} hires, {!! $total_opens !!} active </p> 
@php
    $total_hours    = $client->getTotalHours();
@endphp 
@if($total_hours['total_worked'])
    <p class = "text-bold text-dark mar-no"> {{ number_format($total_hours['total_paid'] / $total_hours['total_worked'], 2)   }} /hr avg hourly rate paid</p>
    <p class = " "> {{ $total_hours['total_worked'] }} hours </p>
@endif
<p class = " ">  Member since {!! date('M d, Y', strtotime($client->created_at)) !!} </p>  