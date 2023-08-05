@extends('layouts.app')
@section('title', "Offer Sent")
@section('css')
@endsection
@section('content')

@php
    $freelancer = $offer->getFreelancer();
    $job        = $offer->getJob();
@endphp
 
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-10 col-md-offset-1">
            <div class="card m-md-top">
                <div class="card-body bg-gray pad-no"> 
                    <div class = "row">
                        <div class = "col-lg-4   pad-all bord-rgt"> 
                            <div class  = "row pad-all">
                                <div class = "col-md-12 text-center">
                                    <img src = "{!! asset('img/background/offer.png') !!}" width = "40%" />
                                </div>
                                <div class = "col-md-12 text-center">
                                    <h3 class = "text-dark"> Offer sent to {!! $freelancer->accounts->firstname !!} {!! $freelancer->accounts->lastname !!}</h3>
                                    <p class = "text-dark pt-15 "> 
                                        We'll notify you when {!! $freelancer->accounts->firstname !!} {!! $freelancer->accounts->lastname !!} responded to your offer.
                                    </p>
                                </div>
                            </div> 
                        </div>
                        <div class = "col-lg-8 bg-light ">
                            <div class = "pad-all">
                                <form   method="post" class="form-horizontal" action="{!! route('employer.jobs.morehiring', $offer->serial) !!}">
                                    @csrf
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h5 class = "text-dark pt-15"> 
                                                Are you done hiring for the gig. "{{ $job->headline }}"
                                            </h5>
                                        </div> 
                                        <div class="col-md-12">  
                                            <div class="radio">
                                                <input id="done_hiring-form-radio" class="magic-radio" type="radio" name="done_hiring" value = "done" checked="">
                                                <label for="done_hiring-form-radio" class = "text-dark text-bold">
                                                    I am done hiring for this gig 
                                                </label>
                                                <p class = "pad-lft mar-lft mar-top-5"> When the freelancer accepts, your gig post will be closed to new proposals. Don't worry - the original gig post, all the freelancers you messaged shortlisted or archived for this gig will be saved  </p>
                                            </div>
                                        </div> 
                                        <div class="col-md-12">
                                            <div class="radio ">
                                                <input id="done_hiring-form-radio-2"  class="magic-radio" type="radio" name="done_hiring" value = "more">
                                                <label for="done_hiring-form-radio-2" class = "text-dark text-bold">I plan to hire more freelancers for this gig</label>
                                                <p class = "pad-lft mar-lft mar-top-5"> Your gig post will remain open to new proposals. </p>
                                            </div> 
                                        </div> 
                                        <div class="col-md-12 mar-top text-center">
                                            <button type = "submit" class = "btn btn-mint"> Go to My Gigs </button>
                                        </div> 
                                    </div>
                                </form> 
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection
@section('javascript')
<script>

</script>
@stop