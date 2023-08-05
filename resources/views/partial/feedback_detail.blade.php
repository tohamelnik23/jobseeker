@foreach($offers as $offer)
    @php
        $job = $offer->getJob();
    @endphp  
<div class = "row mb-20">
    <div class = "jobs-header col-md-12">
        @if( $job->status == 2)
            <h4 class = "text-dark"> {{ $job->headline }}  </h4>
        @else
            <h4> 
                <a href = "{!! route('jobs_details', $job->serial) !!}" class = "text-mint btn-link text-bold"> {{ $job->headline }}  </a>
            </h4>
        @endif

        @php
            $freelancer_feedback  = $offer->getFeedback( $offer->user_id );
        @endphp

        <p class = "">
            @if(isset($freelancer_feedback))
            <span  class = "total-rating"  data-size = "20"  data-star = '{!! $freelancer_feedback->rate_total !!}'></span>
            <span class = "pt-14 text-dark">{{ $freelancer_feedback->message }}</span>
            @else
                <span class = "pt-14"> No feedback given </span>
            @endif
        </p> 
    </div>
    <div class = "job-states col-md-12 text-dark pt-14">
            <p> {!! date('M Y', strtotime($offer->start_time)) !!} - {!! date('M Y', strtotime($offer->end_time)) !!} </p>
        @if($offer->payment_type == "fixed")
            <p> Fixed-price ${!! number_format($offer->getTotalPaid(), 2) !!} </p>
        @else 
            @php
                $total_start_time       =   $offer->totalTimeHours('since_start');
                list($hour, $minute)    =   explode(':', $total_start_time );
				$hour = (int) $hour;
            @endphp  
            <p class = "mar-no"> <span class = "text-bold"> {!! $hour !!} @if($hour == 1) hr @else hrs @endif </span> @ <span class = "text-bold"> ${!! $offer->amount !!}/hr </span> </p>
            <p> Billed: ${!! number_format($offer->getTotalPaid(), 2) !!} </p>
        @endif
    </div> 
    <div class = "col-md-12"> 
        <p class = "">
            <span class = "pt-14 text-bold text-dark"> To freelancer: </span>
            @php
                $freelancer = $offer->getFreelancer();
                $client_feedback  = $offer->getFeedback( $client->id );
            @endphp
            <span>
                <a class = "text-mint btn-link text-bold" href = "{!! route('freelancers.detail', $freelancer->serial) !!}"> {!! $freelancer->accounts->name !!} </a> 
            </span>
            @if(isset($client_feedback)) 
            <span  class = "total-rating" data-size = "17" data-star = '{!! $client_feedback->rate_total !!}'></span>
            <span class = "pt-14 text-dark"> {{ $client_feedback->message }} </span>
            @else
                <span>No feedback given </span>
            @endif
        </p> 
    </div>
</div>
@endforeach