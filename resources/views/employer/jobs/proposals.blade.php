@extends('layouts.app')
@section('title', $job->headline) 
@section('css') 
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('employer.jobs.jobdetail_topbar')
            <div class = "col-md-12 mar-top">
                <div class = "card">
                    <div class = "card-body pad-no">
                        <div class="panel mar-top freelancer_card_panel">
                            @include('employer.jobs.partial.proposal_header')
                            <div class="panel-body pad-no">
                                <div class="tab-content">
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @if($subrequest == 'archived')
                                                @if(count($freelancers_archived))
                                                    <div class="category_stuff clearfix">
                                                        <div class="col-md-12">
                                                            <a href="javascript:void(0)" class="category-anchor">
                                                                <h5 class="text-mint mar-lft pt-17"> 
                                                                    <span class = "pad-lft"> {!! count($freelancers_archived) !!} Archieved </span>
                                                                    <span class="pull-left text-bold text-mint anchor-down" > 
                                                                        <i class="fa fa-chevron-down"></i>
                                                                    </span> 
                                                                    <span class="pull-left text-bold text-mint anchor-up"  > 
                                                                        <i class="fa fa-chevron-up"></i>
                                                                    </span>  
                                                                </h5> 
                                                            </a>
                                                        </div> 
                                                        <div class="col-md-12 category-items">
                                                            @foreach($freelancers_archived as $freelancer)
                                                                @include('employer.jobs.partial.proposal_freelancer')
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(count($freelancers_declined))
                                                    <div class="category_stuff clearfix">
                                                        <div class="col-md-12">
                                                            <a href="javascript:void(0)" class="category-anchor">
                                                                <h5 class="text-mint mar-lft pt-17"> 
                                                                    <span class = "pad-lft"> {!! count($freelancers_declined) !!} Declined </span>
                                                                    <span class="pull-left text-bold text-mint anchor-down" > 
                                                                        <i class="fa fa-chevron-down"></i>
                                                                    </span> 
                                                                    <span class="pull-left text-bold text-mint anchor-up"  > 
                                                                        <i class="fa fa-chevron-up"></i>
                                                                    </span>  
                                                                </h5> 
                                                            </a>
                                                        </div> 
                                                        <div class="col-md-12 category-items">
                                                            @foreach($freelancers_declined as $freelancer)
                                                                @include('employer.jobs.partial.proposal_freelancer')
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif 

                                                @if(!count($freelancers_archived) && !count($freelancers_declined))
                                                    <div class = "clearfix mar-top  text-center">
                                                        <img src = "{!! asset('img/background/no-search-result.png') !!}" width = "12%" /> 
                                                        <h3 class = "text-dark"> You have no archived proposals yet </h3>
                                                        <p class = "pt-15 mar-btm pad-btm"> Please use the archive feature to archive proposals </p> 
                                                    </div>
                                                @endif
                                            @else
                                                @forelse($freelancers as $freelancer)
                                                    @include('employer.jobs.partial.proposal_freelancer')
                                                @empty
                                                    <div class = "clearfix mar-top  text-center">
                                                        <img src = "{!! asset('img/background/no-search-result.png') !!}" width = "12%" />
                                                        @if($subrequest == "applicants")
                                                        <h3 class = "text-dark"> You have no proposals yet </h3>
                                                        <p class = "pt-15 mar-btm pad-btm"> Please wait invite the freelancers matched with your requirements </p>
                                                        @endif 
                                                        @if($subrequest == "shortlisted")
                                                        <h3 class = "text-dark"> You have no shortlisted yet </h3>
                                                        <p class = "pt-15 mar-btm pad-btm"> Use the shortlist feature to add your preferred proposals</p>
                                                        @endif 
                                                        @if($subrequest == "messaged")
                                                        <h3 class = "text-dark"> You have no messated yet </h3>
                                                        <p class = "pt-15 mar-btm pad-btm"> Use the message feature to add your messaged proposals</p>
                                                        @endif 
                                                    </div>
                                                @endforelse
                                            @endif
                                        </div>
                                        @if($subrequest !== 'archived')
                                            @if ($freelancers->hasPages())
                                                <div class = "col-md-12 mar-top text-right pad-hor">
                                                    {!! $freelancers->links() !!}
                                                </div>
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('employer.jobs.partial.employer_action') 
    @include('employer.jobs.partial.decline_invite')

@endsection
@section('javascript')
 
@stop