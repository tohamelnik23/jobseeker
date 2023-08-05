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
                            @include('employer.jobs.partial.hired_header')
                            <div class="panel-body pad-no">
                                <div class="tab-content">
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @forelse($freelancers as $freelancer)
                                                @include('employer.jobs.partial.proposal_freelancer')
                                            @empty
                                                <div class = "clearfix  text-center">
                                                    <img src = "{!! asset('img/background/no-search-result.png') !!}" width = "12%" /> 
                                                    @if($subrequest == "offers")
                                                    <h3 class = "text-dark"> You don't have any offers yet </h3>
                                                    <p class = "pt-15 mar-btm pad-btm"> Interview promising candidates and make them an offer. </p>
                                                    @endif 
                                                    @if($subrequest == "hired")
                                                    <h3 class = "text-dark"> You don't have any hires yet </h3>
                                                    <p class = "pt-15 mar-btm pad-btm"> Interview promising candidates and make them an offer.</p>
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

    @if($subrequest == "offers") 
        @include('employer.jobs.partial.decline_invite')
    @endif 
@endsection
@section('javascript')
<script>
   
</script>
@stop