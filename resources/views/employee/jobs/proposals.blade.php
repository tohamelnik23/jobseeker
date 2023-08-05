@extends('layouts.app')
@section('title', 'Proposal') 
@section('css') 
@endsection
@section('content')
    <div class="container"> 
        <div class="row justify-content-center">
            <div class = "col-md-12">
                @include('partial.alert')
            </div>
            <div class = "col-md-12">
                <h2 class="text-main  pad-btm mar-no  pad-lft text-semibold text-left text-dark">  
                   My Proposals
                </h2>
            </div> 
            <div class = "col-md-12">
                <div class="panel no-bg">  
                    <div class="panel-heading">
                        <div class="panel-control pull-left">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#proposal-tabs-box-1" data-toggle="tab">Active</a></li>
                                <li><a href="#proposal-tabs-box-2" data-toggle="tab">Archived</a></li>
                            </ul>
                        </div>
                    </div> 
                    <div class="panel-body pad-no">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="proposal-tabs-box-1"> 
                                <div class = "card">
                                    <div class = "card-body  pad-no">
                                        <div class = "clearfix pad-all">
                                            <h4 class="m-sm-bottom text-dark m-lg-top"> Offers({!! count($offers) !!})</h4>
                                        </div> 
                                        @if(count($offers))
                                        <div class = "up-proposals-list__block">
                                            @foreach($offers as $offer_index => $offer)
                                                <div class = "row" data-aa = "item{!! $offer_index !!}">
                                                    <div class = "row col-md-9">
                                                        <div class = "col-md-4">
                                                            <div class="pb-5 text-dark"> Received <span class="nowrap">{!! date('M d, Y', strtotime($offer->created_at)) !!}  </span></div>
                                                            <small class="text-muted pt-12">{!! $offer->created_at->diffForhumans()  !!}</small>
                                                        </div>
                                                        <div class = "col-md-8">
                                                            <div class="pb-5">
                                                                <a class = "btn-link text-mint" href="{!! route('jobs_offer_details', $offer->serial) !!}">
                                                                    {!! $offer->contract_title !!}
                                                                </a>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <div class = "row col-md-3"> 
                                                    </div>
                                                </div> 
                                            @endforeach
                                        </div> 
                                        @endif  
                                    </div>
                                </div>  
                                <div class = "card mar-top">
                                    <div class = "card-body  pad-no">
                                        <div class = "clearfix pad-all">
                                            <h4 class="m-sm-bottom text-dark m-lg-top"> Invitation to interview({!! count($invitations) !!})</h4>
                                        </div> 
                                        @if(count($invitations))
                                        <div class = "up-proposals-list__block">
                                            @foreach($invitations as $invitation_index => $invitation)
                                                <div class = "row" data-aa = "item{!! $invitation_index !!}">
                                                    <div class = "row col-md-9">
                                                        <div class = "col-md-4">
                                                            <div class="pb-5 text-dark"> Received <span class="nowrap">{!! date('M d, Y', strtotime($invitation->created_at)) !!}  </span></div>
                                                            <small class="text-muted pt-12">{!! $invitation->created_at->diffForhumans()  !!}</small>
                                                        </div>
                                                        <div class = "col-md-8">
                                                            <div class="pb-5">
                                                                <a class = "btn-link text-mint" href="{!! route('jobs_invites_details', $invitation->serial) !!}">
                                                                    {!! $invitation->headline !!}
                                                                </a>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <div class = "row col-md-3"> 
                                                    </div>
                                                </div> 
                                            @endforeach
                                        </div> 
                                        @endif  
                                    </div>
                                </div> 
                                <div class = "card mar-top">
                                    <div class = "card-body  pad-no">
                                        <div class = "clearfix pad-all bord-btm">
                                            <h4 class="m-sm-bottom text-dark m-lg-top"> Active Proposals({!! count($active_proposals) !!})</h4>  
                                        </div>
                                        @if(count($active_proposals))
                                        <div class = "up-proposals-list__block">
                                            @foreach($active_proposals as $proposal_index => $proposal)
                                                <div class = "row" data-aa = "item{!! $proposal_index !!}">
                                                    <div class = "row col-md-9">
                                                        <div class = "col-md-4">
                                                            <div class="pb-5 text-dark"> Received <span class="nowrap">{!! date('M d, Y', strtotime($proposal->updated_at)) !!}  </span></div>
                                                            <small class="text-muted pt-12">{!! $proposal->updated_at->diffForhumans()  !!}</small>
                                                        </div>
                                                        <div class = "col-md-8">
                                                            <div class="pb-5">
                                                                <a class = "btn-link text-mint" href="{!! route('jobs_proposal_details', $proposal->serial) !!}">
                                                                    {!! $proposal->getJob()->headline !!}
                                                                </a>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <div class = "row col-md-3">
                                                        <div data-qa="user" class="col-md-12 pl-0">
                                                            <small class="text-muted pt-12">
                                                                {!! $proposal->getProfile()->role_title !!}
                                                            </small>
                                                        </div> 
                                                    </div>
                                                </div> 
                                            @endforeach
                                        </div> 
                                        @endif
                                    </div>
                                </div>  
                                <div class = "card mar-top">
                                    <div class = "card-body  pad-no">
                                        <div class = "clearfix pad-all bord-btm">
                                            <h4 class="m-sm-bottom text-dark m-lg-top"> Submitted proposals ({!! count($submitted_proposals) !!})</h4>  
                                        </div> 
                                        @if(count($submitted_proposals))
                                        <div class = "up-proposals-list__block">
                                            @foreach($submitted_proposals as $proposal_index => $proposal)
                                                <div class = "row" data-aa = "item{!! $proposal_index !!}">
                                                    <div class = "row col-md-9">
                                                        <div class = "col-md-4">
                                                            <div class="pb-5 text-dark"> Received <span class="nowrap">{!! date('M d, Y', strtotime($proposal->updated_at)) !!}  </span></div>
                                                            <small class="text-muted pt-12">{!! $proposal->updated_at->diffForhumans()  !!}</small>
                                                        </div>
                                                        <div class = "col-md-8">
                                                            <div class="pb-5">
                                                                <a class = "btn-link text-mint" href="{!! route('jobs_proposal_details', $proposal->serial) !!}">
                                                                    {!! $proposal->getJob()->headline !!}
                                                                </a>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <div class = "row col-md-3">
                                                        <div data-qa="user" class="col-md-12 pl-0">
                                                            <small class="text-muted pt-12">
                                                                {!! $proposal->getProfile()->role_title !!}
                                                            </small>
                                                        </div> 
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div> 
                                        @endif

                                    </div>
                                </div> 
                            </div>
                            <div class="tab-pane fade" id="proposal-tabs-box-2"> 
                                <div class = "card">
                                    <div class = "card-body  pad-no">
                                        <div class = "clearfix pad-all bord-btm">
                                            <h4 class="m-sm-bottom text-dark m-lg-top"> Archived proposals({!! count($declined_proposals) !!})</h4>  
                                        </div> 
                                        <div class = "up-proposals-list__block">
                                            
                                            @foreach($declined_proposals as $declined_proposal_index => $declined_proposal)
                                            <div class = "row" data-aa = "item{!! $declined_proposal_index !!}">
                                                <div class = "row col-md-7">
                                                    <div class = "col-md-4">
                                                        <div class="pb-5 text-dark"> Received <span class="nowrap">{!! date('M d, Y', strtotime($declined_proposal->updated_at)) !!}</span></div>
                                                        <small class="text-muted pt-12">{!! $declined_proposal->updated_at->diffForhumans()  !!}</small>
                                                    </div> 
                                                    @php
                                                        $decline_job = $declined_proposal->getJob();
                                                        $proposal = $declined_proposal->getReference(); 
                                                    @endphp

                                                    <div class = "col-md-8">
                                                        <div class="pb-5">
                                                            <a class = "btn-link text-mint" href="{!! route('jobs_proposal_details', $declined_proposal->decline_reference) !!}">
                                                                {!! $decline_job->headline !!}
                                                            </a>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class = "row col-md-5">
                                                    <div data-qa="user" class="col-md-7 pl-0">
                                                        <small class="pt-13 text-dark">
                                                            {!! $declined_proposal->getDeclineMessage('partial') !!}
                                                        </small>
                                                    </div> 
                                                    <div data-qa="user" class="col-md-5 pl-0">
                                                        <small class="text-muted pt-13">
                                                            {!! $proposal->getProfile()->role_title !!}
                                                        </small>
                                                    </div> 
                                                </div>
                                            </div>  
                                            @endforeach
                                        </div> 
                                    </div>
                                </div> 
                                @if(count($declined_interviews))
                                <div class = "card mar-top">
                                    <div class = "card-body  pad-no">
                                        <div class = "clearfix pad-all bord-btm">
                                            <h4 class="m-sm-bottom text-dark m-lg-top"> Archived interviews({!! count($declined_interviews) !!})</h4>  
                                        </div> 
                                        <div class = "up-proposals-list__block">
                                            @foreach($declined_interviews as $declined_interview_index => $declined_interview)
                                            <div class = "row" data-aa = "item{!! $declined_interview_index !!}">
                                                <div class = "row col-md-7">
                                                    <div class = "col-md-4">
                                                        <div class="pb-5 text-dark"> Received <span class="nowrap">{!! date('M d, Y', strtotime($declined_interview->updated_at)) !!}</span></div>
                                                        <small class="text-muted pt-12">{!! $declined_interview->updated_at->diffForhumans()  !!}</small>
                                                    </div>
                                                    @php
                                                        $decline_job = $declined_interview->getJob();
                                                    @endphp
                                                    <div class = "col-md-8">
                                                        <div class="pb-5">
                                                            <a class = "btn-link text-mint" href="{!! route('jobs_details', $decline_job->serial) !!}">
                                                                {!! $decline_job->headline !!}
                                                            </a>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class = "row col-md-5">
                                                    <div data-qa="user" class="col-md-12 pl-0">
                                                        <small class="pt-13 text-dark">
                                                            @if($declined_interview->decline_user == Auth::user()->id)
                                                                Declined by you
                                                            @else
                                                                Withdrawn by Client
                                                            @endif
                                                        </small>
                                                    </div>  
                                                </div>
                                            </div>  
                                            @endforeach
                                        </div> 
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
            <div class = "col-md-12 mar-top">
                <div class="text-right">
                    <a href="{!! route('search') !!}" class = "btn-link text-mint">  Search for jobs </a> | 
                    <a href="{!! route('employee.profile') !!}"  class = "btn-link text-mint">
                        Manage your profile
                    </a>
                </div>
            </div> 
        </div>
    </div>
@endsection
@section('javascript')
    <script>
       
    </script>
@stop