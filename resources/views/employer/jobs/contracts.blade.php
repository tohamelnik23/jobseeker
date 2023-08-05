@extends('layouts.app')
@section('title', 'Contracts')
@section('css') 
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>  
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mar-ver">
                <div class="card-header">
					Contracts
				</div>
                <div class="card-body pad-no">  
                    @forelse($contracts as $offer)
                        @php
                            $job        = $offer->getJob();
                            $freelancer = $offer->getFreelancer();
                        @endphp
                        <div class = "posting-item clearfix pad-hor">
                            <div class = "col-lg-4 col-md-4 ">
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <div class = "row">
                                            <div class = "col-md-12 d-flex ">
                                                <div class = "" >
                                                    <img class="img-circle img-md" src="{!! $freelancer->getImage() !!}"> 
                                                </div> 
                                                <div class = "m-lft-5  one-line-limit-noblock"> 
                                                    <div class = "text-dark col-xs-12  one-line-limit-noblock">
                                                        <a href = "{!! route('employer.contract_details', $offer->serial) !!}" class = "text-dark btn-link pt-17 text-bold">
                                                            @if(isset($job))
                                                                {{ $job->headline }}
                                                            @else
                                                                {{ $offer->contract_title }}
                                                            @endif
                                                        </a>
                                                    </div>
                                                    
                                                    <div class = "col-md-6">
                                                        <p class = "text-dark">
                                                            {!! $freelancer->accounts->name !!}
                                                        </p>
                                                    </div>
                                                    <div class = "col-md-6">
                                                        @if(isset($job))
                                                        <i class="fa fa-map-marker m-rgt-5"></i>  {!! Mainhelper::getStateFromValue( $job->state ) !!} 
                                                        @endif
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class = "col-md-12">
                                                <p class = "mar-top-5">
                                                    {!!  date('M d, Y', strtotime($offer->start_time))   !!}  
                                                    @if($offer->end_time) 
                                                        - {!!  date('M d, Y', strtotime($offer->end_time))  !!}
                                                    @else
                                                        - Present
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div> 
                            <div class = "col-lg-4 col-md-4 pt-15  text-dark"> 
                                @if($offer->status !== 10)
                                    @if($offer->payment_type == "hourly")
                                        <p class = " pad-no mar-btm-5"> ${!! number_format($offer->getTotalPaid(), 2) !!} | ${!! $offer->amount !!} /hr </p>
                                        <p class = " pad-no mar-btm-5">  {!!  $offer->totalTimeHours('this_week') !!} hrs this week </p> 
                                        @php
                                            $last_week_paid = $offer->getLastWeekPaid();
                                        @endphp
                                        <p class = " pad-no mar-btm-5"> ${!! number_format($last_week_paid, 2)  !!} in Review </p>
                                    @else
                                        <p class = " pad-no mar-btm-5"> <span class = "text-bold"> ${!! number_format($offer->amount, 2) !!} </span> Budget </p>
                                        <p class = " pad-no mar-btm-5"> <span class = "text-bold"> ${!! number_format($offer->getTotalEscrow(), 2) !!}</span>  in Escrow </p>
                                        @php
                                            $active_milestone = $offer->getActiveMilestone();
                                        @endphp
                                        @if(isset($active_milestone))
                                            <p class = " pad-no mar-btm-5"> Milestone: {!! $active_milestone->headline  !!} </p>
                                        @else
                                            <p class = " pad-no mar-btm-5"> No active milestones </p>
                                        @endif
                                    @endif
                                @else
                                    @if($offer->payment_type == "hourly")
                                        <p class = " pad-no mar-btm-5"> ${!! number_format($offer->getTotalPaid(), 2) !!} | ${!! $offer->amount !!} /hr </p>
                                        @php
                                            $total_start_time       =   $offer->totalTimeHours('since_start');
                                            list($hour, $minute)    =   explode(':', $total_start_time );
                                        @endphp   
                                        <p class = " pad-no mar-btm-5"> <span class = "text-bold"> {!! $hour . ":" . $minute  !!} hrs</span>  worked </p>
                                    @else
                                        <p class = " pad-no mar-btm-5"> <span class = "text-bold"> ${!! number_format($offer->amount, 2) !!} </span> Budget </p>
                                        <p class = " pad-no mar-btm-5"> <span class = "text-bold"> ${!! number_format($offer->getTotalPaid(), 2) !!}</span>  Paid </p>    
                                    @endif
                                    @if($offer->end_time) 
                                        <p class="mar-top-5 mar-btm-5  text-dark">  Completed {!! date('M j, Y', strtotime($offer->end_time)) !!}  </p>
                                    @endif 
                                    @php
                                        $freelancer_feedback    =  $offer->getFeedback( $offer->user_id );
                                        $client_feedback        =  $offer->getFeedback( $offer->employer_id  );
                                    @endphp 
                                    @if(isset($freelancer_feedback) && isset($client_feedback))
                                        <p  class = "total-rating" data-star = '{!! $freelancer_feedback->rate_total !!}'></p>
                                    @endif
                                @endif 
                            </div>
                            <div class = "col-lg-4 col-md-4  text-right">  
                                <div class="dropdown"> 
                                    @if($offer->payment_type == "hourly")
                                        <!-- a href = "#"  class = "btn btn-mint"> Review And Pay </a -->
                                    @else
                                        <!--a href = "#"  class = "btn btn-mint">  Review And Pay </a-->
                                    @endif
                                     <a href = "{{ route('employer.contract_details', $offer->serial) }}"  class = "btn btn-mint"> Review Details </a>
                                    <!--button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                        <i class = "demo-pli-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-lg">  
                                        <li><a href="#">Review Work</a></li>
                                        <li><a href="#">End Contract</a></li>
                                        <li><a href="#">Give Bonus</a></li>
                                        <li><a href="#">Give Feedback</a></li>
                                    </ul-->
                                </div> 
                            </div> 
                        </div>
                    @empty                       
                        <div class = "row mar-all pad-all">
							<div class="col-md-12 text-center pt-70">
                                <i class = "fa fa-file pad-rgt"> </i> 
							</div> 
                            <div class="col-md-12 text-center">
                                <h4> You haven't posted any gigs    yet.</h4>
                                <h4>
                                    <a href = "{!! route('employer.job.add') !!}" class = "text-mint btn-link text-bold">  Post a gig </a> 
                                    or <a href = "{!! route('employer.mypostings') !!}" class = "text-mint btn-link text-bold"> check out  who's applied </a> 
                                    to your existing gig posts.  
                                </h4>
                            </div> 
						</div> 
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript') 
<script src="{{asset('plugins/simplerating/jquery.star-rating-svg.js')}}"></script>
<script> 
    $(".total-rating").each(function(){
        $(this).starRating({
            initialRating: $(this).attr('data-star'),
            starSize:   21,
            totalStars: 5,  
            disableAfterRate: false,
            readOnly: true
        });
    }); 
</script>
@stop