@extends('layouts.app')
@section('title', $job->headline) 
@section('css') 
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('employer.jobs.jobdetail_topbar')
            <div class = "col-md-12 mar-top">
                <div class = "card freelancer_card">
                    <div class = "card-body pad-no"> 
                        <div class="panel mar-top freelancer_card_panel"> 
                            @include('employer.jobs.partial.invite_header') 
                            <div class="panel-body pad-no">
                                <div class="tab-content">
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @forelse($freelancers as $freelancer)
                                                @include('employer.jobs.partial.proposal_freelancer')
                                            @empty
                                                <div class = "clearfix mar-top   text-center">
                                                    <img src = "{!! asset('img/background/no-search-result.png') !!}" width = "12%" /> 
                                                    @if($subrequest == "suggested")
                                                    <h3 class = "text-dark"> You have no suggested freelancers yet </h3>
                                                    <p class = "pt-15 mar-btm pad-btm"> Please use another search criteria </p>
                                                    @endif

                                                    @if($subrequest == "pending")
                                                    <h3 class = "text-dark"> You have no invited freelancers yet </h3>
                                                    <p class = "pt-15 mar-btm pad-btm"> Please click the invite  button to invite the freelancer</p>
                                                    @endif
                                                    
                                                    @if($subrequest == "hires")
                                                    <h3 class = "text-dark"> You have no hired frelancers yet </h3>
                                                    <p class = "pt-15 mar-btm pad-btm"> Search freelancers who can help the gig is done. </p>
                                                    @endif

                                                    @if($subrequest == "saved")
                                                    <h3 class = "text-dark"> You have no saved freelancers yet </h3>
                                                    <p class = "pt-15 mar-btm pad-btm"> Please use the save feature to save freelancers </p>
                                                    @endif
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('employer.jobs.partial.savefreelancer_modal') 
    @include('employer.jobs.partial.employeer_action') 

    @if($subrequest == "pending") 
        @include('employer.jobs.partial.decline_invite')
    @endif 
@endsection
@section('javascript')
<script>
   
</script>
@stop