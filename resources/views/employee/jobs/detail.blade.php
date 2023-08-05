@extends('layouts.app')
@section('title', 'Proposal Details') 
@section('css') 
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>  
@endsection
@section('content')

@php
    $job_taker_fee = App\Model\MasterSetting::getValue('job_taker_fee');
    $benefit_fee   =  100 - $job_taker_fee;
@endphp 
    <div class="container">
        <div class="row justify-content-center">
            <div class = "col-md-12">
                @include('partial.alert')
            </div>
            <div class = "col-md-12">
                <h2 class="text-main pad-btm mar-no  pad-lft text-semibold text-left text-dark">  
                   @if(isset($proposal)) Proposal Details  @endif
                   @if(isset($invite)) Invitation to Interview  @endif
                </h2>
            </div>  
            <div class = "col-md-9 col-sm-8">
                @php
                    if(isset($proposal))
                        $job =  $proposal->getJob();
                    if(isset($invite))
                        $job =  $invite->getJob();
                @endphp 
                <div class = "card mar-top">
                    <div class = "card-body  pad-no">
                        <div class = "col-lg-12   pad-all bord-btm">
                            <div class = "clearfix pad-hor">  
                                <h3 class="m-sm-bottom text-dark m-lg-top text-capitalize">{{ $job->type }} details</h3> 
                            </div>
                        </div>
                        <div class = "col-lg-12   pad-hor bord-btm">
                            <div class = "row">
                                <div class = "col-lg-9   pad-all bord-rgt">
                                    <div class = "clearfix pad-hor freelanceritem_card">   
                                        <section class = "air-card-divider-sm pad-no profile-detail-group">
                                            <h4 class="m-sm-bottom text-dark m-lg-top pad-btm">{!! $job->headline !!}</h4> 
                                            <p >  Posted   {!! date('F d, Y', strtotime($job->updated_at)) !!}  </p>   
                                            <div class = "up-line-clamp-v2 profile-detail mar-top" style = "-webkit-line-clamp: 7;">
                                                <span class  = "text-pre-line text-dark pt-14 ">{!!  $job->description !!}</span>
                                            </div> 
                                            <a href = "javascript:void(0)" data-expanded="false" class="pad-top  text-mint btn-link text-bold updownmore_buttton hidden"> 
                                                more
                                            </a>  
                                            <div class = "clearfix mar-top">
                                                <a class="btn-link text-mint text-bold mar-top mo" href="{!! route('jobs_details', $job->serial) !!}"> View Gig Posting </a> 
                                            </div>
                                        </section>
                                    </div>
                                </div>  
                                <div class = "col-lg-3">   
                                    <div class = "pad-ver">
                                        <p class = "text-dark text-bold  text-capitalize"  >
                                            @if($job->payment_type == "hourly")
                                                <i class = "fa fa-clock-o pt-15 pad-rgt"></i> 
                                            @else
                                                <i class="fa  fa-tag pt-15 pad-rgt"></i>
                                            @endif 
                                            {!! $job->payment_type !!} Price
                                        </p>
                                        <p class = "text-dark text-bold" >
                                            <i class = "fa fa-calendar-check-o pt-15 pad-rgt"></i>
                                            {!! $job->getDisplayDate() !!}   {!! $job->getJobTime() !!}
                                            @if($job->shift_end_date_time)
                                                ~ {!! date('H:i A' , strtotime($job->shift_end_date_time))  !!}
                                            @endif
                                        </p>
                                    </div> 
                                </div> 
                            </div>
                        </div>   
                        <div class = "col-lg-12   pad-hor bord-btm">  
                            @php
                                $skills = $job->getSkills();
                            @endphp
                            <section class = "mar-top pad-hor clearfix"> 
                                <h4 class="m-sm-bottom text-dark  ">Skills and expertise </h4>
                                <p>
                                    @foreach($skills as $skill)
                                        <span class="badge pad-rgt mar-top-5">{!! $skill->skill !!}</span> 
                                    @endforeach
                                </p> 
                            </section> 
                        </div>
                        @if(isset($proposal))
                            <div class = "col-lg-12   pad-hor bord-btm">   
                                <section class = "mar-top pad-hor clearfix">  
                                    <div class = "row ">
                                        <div class = "col-md-4 ">
                                            <h4 class = "text-dark mar-btm"> Your Proposed terms  </h4>
                                        </div> 
                                        <div class = "col-md-8">
                                            <h4 class = "text-right text-thin"> @if($job->payment_type == 'fixed') Client's budget:  {!! $job->getBudget() !!}  @endif </h4>
                                        </div>
                                    </div>
                                        @php
                                            $role = $proposal->getProfile(); 
                                        @endphp
                                    <div class = "proposed_terms_display">
                                        <h5 class = "text-dark text-bold mar-top clearfix"> Profile </h5>
                                        <p class = "text-mint text-bold"> {!! $role->role_title !!} </p> 
                                        @if($job->payment_type == "hourly")
                                            <h5 class = "text-dark text-bold mar-top clearfix"> Hourly Rate </h5>
                                            <p class = "text-dark text-bold"> Total amount the client will see on your proposal </p> 
                                            <p class = "text-dark text-bold">  ${!! $proposal->proposal_amount !!} /hr </p> 
                                            <hr class = "mar-no" />
                                            <h5 class = "text-dark text-bold mar-top clearfix"> You will receive </h5>
                                            <p class = "text-dark text-bold"> The estimated amount you will receive after service fees </p> 
                                            <p class = "text-dark text-bold">  ${!! number_format($proposal->proposal_amount * $benefit_fee / 100, 2) !!} /hr </p>   
                                        @endif
                                        @if($job->payment_type == "fixed")
                                            <h5 class = "text-dark text-bold mar-top clearfix"> Bid/Budget </h5>
                                            <p class = "text-dark text-bold"> Total amount the client will see on your proposal </p> 
                                            <p class = "text-dark text-bold">  ${!! $proposal->proposal_amount !!}</p> 
                                            <hr class = "mar-no" />
                                            <h5 class = "text-dark text-bold mar-top clearfix"> You will receive </h5>
                                            <p class = "text-dark text-bold"> The estimated amount you will receive after service fees </p> 
                                            <p class = "text-dark text-bold">  ${!! number_format($proposal->proposal_amount * $benefit_fee / 100, 2) !!} </p>   
                                        @endif 
                                    </div>
                                    @if($proposal->status <= 2)
                                        <div class = "proposed_terms_edit hidden">
                                            <form id = "changeterms_form"  method="post" enctype="multipart/form-data" class="form-horizontal">      
                                                <div class = "row">
                                                    <div class = "col-md-12">
                                                        <h5 class = "text-dark">
                                                            Profile 
                                                        </h5>
                                                    </div>
                                                    <div class = "col-md-6"> 
                                                        @php
                                                            $user_roles = Auth::user()->getRoles();
                                                        @endphp
                                                        <select class = "form-control" name="specialized_role" >
                                                            @foreach($user_roles as $user_role)
                                                                <option value = "{!! $user_role->serial !!}" data-description = "{!! $user_role->getHourlyDescription()  !!}" @if($job->payment_type == 'hourly') data-suggestvalue = "{!! $user_role->getSuggestBudget() !!}" @else data-suggestvalue = "{!! $job->getSuggestBudget() !!}" @endif> {!! $user_role->role_title !!} </option>
                                                            @endforeach
                                                        </select> 
                                                    </div> 
                                                </div>
                                                <div class = "row"> 
                                                    <div class = "col-md-12"> 
                                                        <form id = "changeTermsForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                                                            <div class="panel"> 
                                                                <div class="panel-body pad-no mar-top"> 
                                                                    @if($job->payment_type == 'fixed')
                                                                        <div class = "row">
                                                                            <div class = "col-xs-12">
                                                                                <p class = "text-dark  h4 pt-14"> What is the full amount you'd like to bid for this gig? </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class = "row">
                                                                            <div class = "col-xs-6">
                                                                                <p class = "pt-14 h4 text-dark mar-no">  Bid </p>
                                                                                <p class = "text-dark pt-14"> Total amount the client will see on your proposal </p>
                                                                            </div>
                                                                            <div class = "col-xs-5 mar-no ">
                                                                                <div class="form-group clearfix"> 
                                                                                    <div class="col-sm-8 input-group"> 
                                                                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                                                        <input type="text" class="form-control hgt-35 text-right proposal_budget decimal-input edit proposal_amount" name="proposal_amount" placeholder="" value = "{!! $proposal->proposal_amount !!}" data-orgvalue = "{!! $proposal->proposal_amount !!}">  
                                                                                    </div>    
                                                                                </div> 
                                                                            </div>
                                                                        </div>
                                                                        <div class = "row">
                                                                            <div class = "col-xs-11">
                                                                                <hr />
                                                                            </div>
                                                                        </div>
                                                                        <div class = "row mar-top">
                                                                            <div class = "col-xs-6">
                                                                                <p class = "pt-14 h4 text-dark mar-top">Service Fee </p> 
                                                                            </div>
                                                                            <div class = "col-xs-5 mar-no ">
                                                                                <div class="form-group clearfix"> 
                                                                                    <div class="col-sm-8 input-group "> 
                                                                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                                                        <input type="text" class="form-control hgt-35 text-right proposal_budget proposal_fee decimal-input edit" disabled name="proposal_fee" placeholder="" value = "2.00">  
                                                                                    </div>
                                                                                </div> 
                                                                            </div>
                                                                        </div>
                                                                        <div class = "row">
                                                                            <div class = "col-xs-12">
                                                                                <hr />
                                                                            </div>
                                                                        </div>
                                                                        <div class = "row mar-top">
                                                                            <div class = "col-xs-6">
                                                                                <p class = "pt-14 h4 text-dark mar-no">  You'll Receive </p>
                                                                                <p class = "text-dark pt-14"> The estimated amount you'll receive after service fees </p>
                                                                            </div>
                                                                            <div class = "col-xs-5 mar-no ">
                                                                                <div class="form-group clearfix"> 
                                                                                    <div class="col-sm-8 input-group "> 
                                                                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                                                        <input type="text" class="form-control hgt-35 text-right proposal_budget proposal_income decimal-input edit" name="proposal_income" placeholder="" value = "12.00">  
                                                                                    </div>  
                                                                                </div> 
                                                                            </div>
                                                                        </div>
                                                                        <!--div class = "row mar-top"> 
                                                                            <label class="col-sm-12 control-label text-left text-dark" for="role_description">
                                                                                <strong>  Message   </strong>
                                                                            </label>
                                                                            <div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
                                                                                <textarea name = "message_client" class = "form-control edit"  rows = "10"></textarea>
                                                                                <span class="help-block"> 
                                                                                    <strong></strong>  
                                                                                </span>
                                                                            </div> 
                                                                        </div -->
                                                                    @endif 
                                                                    @if($job->payment_type == 'hourly') 
                                                                        <div class = "row">  
                                                                            <div class = "col-xs-6">
                                                                                <p class = "pt-14 h4"> Your profile rate: <strong class = "my_profile_rate"> $22.00/hr </strong> </p>
                                                                            </div>
                                                                            <div class = "col-xs-6">
                                                                                <p class = "pt-14 h4"> Client's budget: {!! $job->getBudget() !!} </p>
                                                                            </div>  
                                                                        </div>
                                                                        <div class = "row mar-top">
                                                                            <div class = "col-xs-6">
                                                                                <p class = "pt-14 h4 text-dark mar-no">  Hourly Rate </p>
                                                                                <p class = "text-dark pt-14"> Total amount the client will see on your proposal </p>
                                                                            </div>
                                                                            <div class = "col-xs-6 mar-no ">
                                                                                <div class="form-group clearfix"> 
                                                                                    <div class="col-sm-8 input-group "> 
                                                                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                                                        <input type="text" class="form-control hgt-35 text-right proposal_amount proposal_budget decimal-input edit" name="proposal_amount" placeholder="" value = "{!! $proposal->proposal_amount !!}" data-orgvalue = "{!! $proposal->proposal_amount !!}"> 
                                                                                        <span class="input-group-addon text-dark">/hr</span>
                                                                                    </div>    
                                                                                </div> 
                                                                            </div>
                                                                        </div>
                                                                        <div class = "row">
                                                                            <div class = "col-xs-12">
                                                                                <hr />
                                                                            </div>
                                                                        </div>
                                                                        <div class = "row mar-top">
                                                                            <div class = "col-xs-6">
                                                                                <p class = "pt-14 h4 text-dark mar-top">  {!! $job_taker_fee !!}% Service Fee </p> 
                                                                            </div>
                                                                            <div class = "col-xs-6 mar-no ">
                                                                                <div class="form-group clearfix"> 
                                                                                    <div class="col-sm-8 input-group "> 
                                                                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                                                        <input type="text" class="form-control hgt-35 text-right proposal_fee proposal_budget decimal-input edit" disabled name="proposal_fee" placeholder="" value = "2.00"> 
                                                                                        <span class="input-group-addon text-dark">/hr</span> 
                                                                                    </div>    
                                                                                </div> 
                                                                            </div>  
                                                                        </div>
                                                                        <div class = "row">
                                                                            <div class = "col-xs-12">
                                                                                <hr />
                                                                            </div>
                                                                        </div>
                                                                        <div class = "row mar-top">
                                                                            <div class = "col-xs-6">
                                                                                <p class = "pt-14 h4 text-dark mar-no">  You'll Receive </p>
                                                                                <p class = "text-dark pt-14"> The estimated amount you'll receive after service fees </p>
                                                                            </div>
                                                                            <div class = "col-xs-6 mar-no ">
                                                                                <div class="form-group clearfix"> 
                                                                                    <div class="col-sm-8 input-group "> 
                                                                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                                                        <input type="text" class="form-control hgt-35 text-right proposal_income proposal_budget decimal-input edit" name="proposal_income" placeholder="" value = "12.00"> 
                                                                                        <span class="input-group-addon text-dark">/hr</span> 
                                                                                    </div>    
                                                                                </div> 
                                                                            </div>
                                                                        </div>  
                                                                    @endif

                                                                    <div class = "row mar-top"> 
                                                                        <div class = "col-xs-12">
                                                                            <button type = "button" class = "btn btn-mint submit_proposal"> Submit </button>
                                                                            <button type = "button" class = "btn cancel_submit_proposal btn-mint-border text-mint">  Cancel </button>
                                                                        </div> 
                                                                    </div>

                                                                </div> 
                                                            </div> 
                                                        </form>
                                                    </div>
                                                </div>
                                            </form>
                                        </div> 
                                    @endif
                                </section>
                            </div>

                            @if($proposal->status <= 2)
                            <div class = "col-lg-12   pad-all proposed_terms_display">   
                                <section class = "mar-top pad-hor clearfix"> 
                                    <button  type = "button" class = "btn btn-mint change_terms"> Change Terms </button>
                                    <button  type = "button" class = "mar-lft btn btn-mint-border text-mint withdraw_proposal"> Withdraw Proposal  </a>
                                </section>
                            </div>
                            @endif

                        @endif  
                    </div>
                </div> 
                @if(isset($invite))
                    <div class = "card mar-top">
                        <div class = "card-body  pad-no">
                            <div class = "col-lg-12   pad-all bord-btm">
                                <div class = "clearfix pad-hor">  
                                    <h4 class="m-sm-bottom text-dark m-lg-top">Original Message</h4> 
                                </div>
                            </div>  
                            <div class = "col-lg-12   pad-hor pad-btm bord-btm"> 
                                <div class = "clearfix pad-hor">   
                                    <section class = "air-card-divider-sm pad-no"> 
                                        <div class = "up-line-clamp-v2 profile-detail mar-top">
                                            <span class  = "text-dark pt-14 ">{!! nl2br($invite->notes)!!}</span>
                                        </div>  
                                    </section>
                                </div> 
                            </div>   
                        </div>
                    </div>
                @endif 
                @if(isset($proposal))
                    <div class = "card mar-ver">
                        <div class = "card-body  pad-no">
                            <div class = "col-lg-12   pad-all bord-btm">
                                <div class = "clearfix pad-hor">  
                                    <h3 class="m-sm-bottom text-dark m-lg-top">Cover Letter</h3> 
                                </div>
                            </div>  
                            <div class = "col-lg-12   pad-hor pad-btm bord-btm"> 
                                <div class = "clearfix pad-hor freelanceritem_card">   
                                    <section class = "air-card-divider-sm pad-no profile-detail-group"> 
                                        <div class = "up-line-clamp-v2 profile-detail mar-top" style = "-webkit-line-clamp: 7;">
                                            <span class  = "text-pre-line text-dark pt-14 ">{!! $proposal->coverletter !!}</span>
                                        </div> 
                                        <a href = "javascript:void(0)" data-expanded="false" class="pad-top  text-mint btn-link text-bold updownmore_buttton hidden"> 
                                            more
                                        </a> 
                                    </section>  
                                    @php
                                        $answers = $proposal->getAnswers();  
                                    @endphp 
                                    @foreach($answers as $answer)
                                        <section class = "air-card-divider-sm pad-no  profile-detail-group">
                                            <h5 class = "text-dark text-bold mar-top clearfix"> {!! $answer->question !!} </h5> 
                                            <div class = "up-line-clamp-v2 profile-detail" style = "-webkit-line-clamp: 4;">
                                                <span class  = "text-pre-line text-dark pt-14 ">{!! $answer->answer !!}</span>
                                            </div> 
                                            <a href = "javascript:void(0)" data-expanded="false" class="pad-top  text-mint btn-link text-bold updownmore_buttton hidden"> 
                                                more
                                            </a>  
                                        </section>
                                    @endforeach
                                </div> 
                            </div>  

                        </div>
                    </div> 
                @endif
            </div>  
            <div class = "col-md-3 col-sm-4"> 
                @if(isset($invite))
                    <section class = "air-card-divider-sm pad-no profile-detail-group"> 
                        <div class = "mar-top">
                            <p class="m-sm-bottom text-dark"> Interesting in discuss on this gig? </p>
                            <div class = "row">
                                <div class = "col-md-12">
                                    <button type = "button" class="btn btn-block accept_interview btn-mint">Accept Interview</button>
                                </div>
                                <div class = "col-md-12 mar-ver">
                                    <button class="btn btn-block btn-default decline_interview text-mint btn-card-button"> Decline </button>
                                </div>
                            </div>
                        </div>
                    </section> 
                @endif
                
                @if(isset($proposal))
                    @php
                        $decline = $job->getDecline(Auth::user()->id);
                    @endphp
                    @if(isset($decline))
                    <section class = "air-card-divider-sm pad-no profile-detail-group"> 
                        <div class = "mar-top">
                            <p class="m-sm-bottom text-danger"> {!!  $decline->getDeclineMessage()  !!} </p>
                        </div>
                    </section> 
                    @endif
                @endif
                
				@php
					$client =  $job->getClient();
				@endphp
				@if(isset($client))
                <section class = "air-card-divider-sm pad-no profile-detail-group"> 
                    <div class = "mar-top">
                        <h4 class="m-sm-bottom text-dark"> About the client </h4>  
                        @if(isset($client))
                            @include('partial.client_history')
                        @endif
                    </div> 
                </section> 
				@endif
				
            </div>  
        </div>
    </div> 
    @if(isset($proposal))
        <div class="modal fade" id="decline_interview_modal" tabindex="-1" role="dialog" aria-labelledby="decline_interview_modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyprofilepicModalLabel">Withdraw Proposal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class = "pad-all">  
                            <form id = "declineForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <p class = "text-dark"> We  will politely notify the client that you are not interested. The client wil be able to view the reason you've withdrawn your proposal. </p>
                                    </div> 
                                    <div class = "col-md-12">
                                        <h5 class = "text-dark">
                                            Reason
                                        </h5>
                                    </div>
                                    <div class="col-md-12"> 
                                        @foreach($decline_messages as $decline_message)
                                        <div class="radio">
                                            <input id="decline-form-radio-{!! $decline_message->id  !!}" data-type = "{!! $decline_message->more_info   !!}" class="magic-radio decline_form_radio" type="radio" name="reason" value = "{!! $decline_message->id !!}">
                                            <label for="decline-form-radio-{!! $decline_message->id  !!}">{!! $decline_message->content !!}</label>
                                        </div>
                                        @endforeach
                                    </div> 
    
                                    <div class = "col-md-12 other_reason_div hidden mar-btm">
                                        <input name = "other_reason" class="form-control" placeholder = "Enter a reason" maxlenght = 512 >
                                    </div> 
                                    <div class = "col-md-12">
                                        <h5 class = "text-dark">
                                            Message
                                        </h5>
                                        <p class = "text-dark"> 
                                            Add an optional message to share with the client when we notify them that this invitation has been withdrawn.
                                        </p>
                                    </div>
                                    <div class="col-sm-12">
                                        <textarea name = "decline_notes" class="form-control" maxlenght = 5000 rows="6"></textarea>
                                        <span class="help-block"> 
                                            <strong></strong>  
                                        </span>
                                    </div> 
                                    <div class="form-group clearfix">
                                        <div class="col-sm-12 mar-top action_btn_group"> 
                                            <button type="button" disabled class="btn btn-mint decline_invite_form"> Withdraw Proposal </button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                                        </div>
                                    </div> 
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif 
    @if(isset($invite))
        <!-- Profile Item Stuff --> 
        <div class="modal fade" id="accept_interview_modal" tabindex="-1" role="dialog" aria-labelledby="accept_interview_modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyprofilepicModalLabel">Accept invitation to interview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class = "pad-all"> 
                            <form id = "proposalForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <h5 class = "text-dark">
                                            Message To Client
                                        </h5>
                                    </div>
                                    <div class="col-sm-12">
                                        <textarea name = "coverletter" class="form-control edit" rows="10"></textarea>
                                        <span class="help-block"> 
                                            <strong></strong>  
                                        </span>
                                    </div>
                                </div> 
                                @php
                                    $questions = $job->getQuestions();
                                @endphp 
                                @foreach($questions as $question)
                                    <div class = "row">
                                        <label class="col-sm-12 control-label text-left h5 text-dark" for="role_description"> {!! $question->question !!} </label>
                                        <div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
                                            <textarea name = "answer[{!! $question->serial !!}]" class = "form-control edit"  rows = "2"></textarea>
                                            <span class="help-block">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                @endforeach 
                                <div class = "row mar-top">
                                    <div class = "col-md-12">
                                        <h5 class = "text-dark">
                                            Propose with a Specialized profile
                                        </h5>
                                    </div>
                                    <div class = "col-md-12">
                                        <div class = "form-group clearfix"> 
                                            <div class="col-sm-6">
                                                @php
                                                    $user_roles = $user->getRoles();
                                                @endphp
                                                <select class = "form-control" name="specialized_role" >
                                                    @foreach($user_roles as $user_role)
                                                        <option value = "{!! $user_role->serial !!}" data-description = "{!! $user_role->getHourlyDescription()  !!}"  @if($job->payment_type == 'hourly') data-suggestvalue = "{!! $user_role->getSuggestBudget() !!}" @else data-suggestvalue = "{!! $job->getSuggestBudget() !!}" @endif> {!! $user_role->role_title !!} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                    </div> 
                                </div> 
                                @if($job->payment_type == 'hourly')
                                    <div class = "row">  
                                        <div class = "col-xs-12">  
                                            <h5 class = "text-dark"> Propose Terms</h5> 
                                        </div> 
                                    </div>
                                    <div class = "row">  
                                        <div class = "col-xs-6">
                                            <p class = "pt-14 h4"> Your profile rate: <strong class = "my_profile_rate"> $22.00/hr </strong> </p>
                                        </div>
                                        <div class = "col-xs-6">
                                            <p class = "pt-14 h4"> Client's budget: {!! $job->getBudget() !!} </p>
                                        </div>  
                                    </div>
                                    <div class = "row mar-top">
                                        <div class = "col-xs-6">
                                            <p class = "pt-14 h4 text-dark mar-no">  Hourly Rate </p>
                                            <p class = "text-dark pt-14"> Total amount the client will see on your proposal </p>
                                        </div>
                                        <div class = "col-xs-6 mar-no ">
                                            <div class="form-group clearfix"> 
                                                <div class="col-sm-8 input-group "> 
                                                    <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                    <input type="text" class="form-control hgt-35 text-right proposal_amount proposal_budget decimal-input edit" name="proposal_amount" placeholder="" value = "12.00"> 
                                                    <span class="input-group-addon text-dark">/hr</span> 
                                                </div>    
                                            </div> 
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-xs-12">
                                            <hr />
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-xs-6">
                                            <p class = "pt-14 h4 text-dark">  {!! $job_taker_fee !!}% Service Fee </p> 
                                        </div>
                                        <div class = "col-xs-6 mar-no ">
                                            <div class="form-group clearfix"> 
                                                <div class="col-sm-8 input-group "> 
                                                    <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                    <input type="text" class="form-control hgt-35 text-right proposal_fee proposal_budget decimal-input edit" disabled name="proposal_fee" placeholder="" value = "2.00"> 
                                                    <span class="input-group-addon text-dark">/hr</span> 
                                                </div>    
                                            </div> 
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-xs-12">
                                            <hr />
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-xs-6">
                                            <p class = "pt-14 h4 text-dark mar-no">  You'll Receive </p>
                                            <p class = "text-dark pt-14"> The estimated amount you'll receive after service fees </p>
                                        </div>
                                        <div class = "col-xs-6 mar-no ">
                                            <div class="form-group clearfix"> 
                                                <div class="col-sm-8 input-group "> 
                                                    <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                    <input type="text" class="form-control hgt-35 text-right proposal_income proposal_budget decimal-input edit" name="proposal_income" placeholder="" value = "12.00"> 
                                                    <span class="input-group-addon text-dark">/hr</span> 
                                                </div>    
                                            </div> 
                                        </div>
                                    </div>  
                                @endif

                                @if($job->payment_type == 'fixed')
                                    <div class = "row">  
                                        <div class = "col-xs-12">  
                                            <h4 class = "text-dark"> What is the full amount you'd like to bid for this gig? </h4> 
                                        </div> 
                                    </div> 
                                    <div class = "row mar-top">
                                        <div class = "col-xs-6">
                                            <p class = "pt-14 h4 text-dark mar-no">  Bid </p>
                                            <p class = "text-dark pt-14"> Total amount the client will see on your proposal </p>
                                        </div>
                                        <div class = "col-xs-5 mar-no ">
                                            <div class="form-group clearfix"> 
                                                <div class="col-sm-8 input-group "> 
                                                    <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                    <input type="text" class="form-control hgt-35 text-right proposal_budget decimal-input edit proposal_amount" name="proposal_amount" placeholder="" value = "12.00">  
                                                </div>    
                                            </div> 
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-xs-11">
                                            <hr />
                                        </div>
                                    </div>
                                    <div class = "row mar-top">
                                        <div class = "col-xs-6">
                                            <p class = "pt-14 h4 text-dark mar-top">{!! $job_taker_fee !!}% Service Fee </p> 
                                        </div>
                                        <div class = "col-xs-5 mar-no ">
                                            <div class="form-group clearfix"> 
                                                <div class="col-sm-8 input-group "> 
                                                    <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                    <input type="text" class="form-control hgt-35 text-right proposal_budget proposal_fee decimal-input edit" disabled name="proposal_fee" placeholder="" value = "2.00">  
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-xs-12">
                                            <hr />
                                        </div>
                                    </div>
                                    <div class = "row mar-top">
                                        <div class = "col-xs-6">
                                            <p class = "pt-14 h4 text-dark mar-no">  You'll Receive </p>
                                            <p class = "text-dark pt-14"> The estimated amount you'll receive after service fees </p>
                                        </div>
                                        <div class = "col-xs-5 mar-no ">
                                            <div class="form-group clearfix"> 
                                                <div class="col-sm-8 input-group "> 
                                                    <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                    <input type="text" class="form-control hgt-35 text-right proposal_budget proposal_income decimal-input edit" name="proposal_income" placeholder="" value = "12.00">  
                                                </div>  
                                            </div> 
                                        </div>
                                    </div> 
                                @endif 
                                <div class="form-group clearfix">
                                    <div class="col-sm-12 mar-top action_btn_group"> 
                                        <button type="button" class="btn btn-mint accept_invite_form"> Accept </button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="modal fade" id="decline_interview_modal" tabindex="-1" role="dialog" aria-labelledby="decline_interview_modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyprofilepicModalLabel">Decline to interview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class = "pad-all">  
                            <form id = "declineForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <p class = "text-dark"> We will politely notify the client you are not interested. </p>
                                    </div> 
                                    <div class = "col-md-12">
                                        <h5 class = "text-dark">
                                            Reason
                                        </h5>
                                    </div>
                                    <div class="col-md-12"> 
                                        @foreach($decline_messages as $decline_message)
                                        <div class="radio">
                                            <input id="decline-form-radio-{!! $decline_message->id  !!}" data-type = "{!! $decline_message->more_info   !!}" class="magic-radio decline_form_radio" type="radio" name="reason" value = "{!! $decline_message->id !!}">
                                            <label for="decline-form-radio-{!! $decline_message->id  !!}">{!! $decline_message->content !!}</label>
                                        </div>
                                        @endforeach
                                    </div> 
    
                                    <div class = "col-md-12 other_reason_div hidden mar-btm">
                                        <input name = "other_reason" class="form-control" placeholder = "Enter a reason" maxlenght = 512 >
                                    </div> 
                                    <div class = "col-md-12">
                                        <h5 class = "text-dark">
                                            Message
                                        </h5>
                                        <p class = "text-dark"> 
                                            Add an optional message to share with the client when we notify them that this invitation has been declined.
                                        </p>
                                    </div>
                                    <div class="col-sm-12">
                                        <textarea name = "decline_notes" class="form-control" maxlenght = 5000 rows="6"></textarea>
                                        <span class="help-block"> 
                                            <strong></strong>  
                                        </span>
                                    </div> 
                                    <div class="form-group clearfix">
                                        <div class="col-sm-12 mar-top action_btn_group"> 
                                            <button type="button" disabled class="btn btn-mint decline_invite_form"> Decline </button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                                        </div>
                                    </div> 
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif 
@endsection
@section('javascript')
<script src="{{asset('plugins/simplerating/jquery.star-rating-svg.js')}}"></script>
<script>
    $(document).on("click", ".btn-description-action" , function(){
        var obj = $(this).closest(".freelanceritem_card");
        obj.find(".description-part").addClass("hidden"); 
        if($(this).hasClass("more")){
            obj.find(".description-detaillist").removeClass("hidden");
        }
        if($(this).hasClass("less")){
            obj.find(".description-shortlist").removeClass("hidden");
        } 
    }); 
    function initPage(){
        $(".profile-detail-group").each(function(){ 
            if($(this).find(".text-pre-line").length){
                var str = $(this).find(".text-pre-line").html();   
                if(str.split(/\r\n|\r|\n/).length <= 7)
                    $(this).find(".updownmore_buttton").addClass("hidden");
                else
                    $(this).find(".updownmore_buttton").removeClass("hidden");     
            }
        }); 
    }
    initPage(); 
    $(document).on("click", ".updownmore_buttton", function(){
        var obj = $(this).closest(".profile-detail-group"); 
        if($(this).attr("data-expanded") == 'false'){
            obj.find(".profile-detail").css("-webkit-line-clamp", "initial");
            $(this).attr("data-expanded", true);
            $(this).text("less");
        }
        else{
            obj.find(".profile-detail").css("-webkit-line-clamp", "7");
            $(this).attr("data-expanded", false);
            $(this).text("more");
        }
    }); 
    @if(isset($invite) || isset($proposal))
        var service_fee = <?= $job_taker_fee ?>;
        var benefit_fee =  100 - service_fee;

        function proposal_budget(obj = ""){
            if(obj == ""){
                var proposal_amount = $(".proposal_amount").val(); 
                var proposal_fee    = proposal_amount * service_fee / 100;
                var proposal_income = proposal_amount - proposal_fee; 
                $(".proposal_fee").val(proposal_fee.toFixed(2));
                $(".proposal_income").val(proposal_income.toFixed(2));
            }
            else{
                if(obj.hasClass('proposal_amount')){
                    var proposal_amount = $(".proposal_amount").val(); 
                    var proposal_fee    = proposal_amount * service_fee / 100;
                    var proposal_income = proposal_amount - proposal_fee; 
                    $(".proposal_fee").val(proposal_fee.toFixed(2));
                    $(".proposal_income").val(proposal_income.toFixed(2));
                } 
                if(obj.hasClass('proposal_income')){
                    var proposal_income =  $(".proposal_income").val(); 

                    var proposal_amount =  proposal_income / benefit_fee * 100; 
                    var proposal_fee    =  proposal_amount * service_fee / 100; 

                    $(".proposal_fee").val(proposal_fee.toFixed(2));
                    $(".proposal_amount").val(proposal_amount.toFixed(2));
                }
            }
        }
        
        $(document).on("keyup", ".proposal_budget", function(){
            proposal_budget($(this));
            @if(isset($proposal))
                checkProposalForm();
            @endif
        });
        $("select[name = 'specialized_role']").change(function(){
            var active_option       = $(this).find("option:selected").val();
            var data_description    = $(this).find("option:selected").attr("data-description");
            var data_suggestvalue   = $(this).find("option:selected").attr("data-suggestvalue"); 
            $(".proposal_amount").val(data_suggestvalue);
            $(".my_profile_rate").html(data_description);
            proposal_budget(); 
        }); 
        function CheckProposalForm(obj, validdate_scope = "all"){
            var flag = 1; 
            var validate_string = ""; 
            validate_string 	= ".form-control.edit";  
            obj.find(  validate_string  ).each(function(){
                if($(this).prop('disabled') == false){
                    var attr_name   = $(this).attr('name');
                    var str_content = $.trim($(this).val()); 
                    var data_error_srting = "";
                    var minlength = $(this).attr('minlength'); 
                    if (typeof minlength !== typeof undefined && minlength !== false) { 
                    }
                    else{
                        minlength = 0;
                    }
                    var error_string = $(this).attr('data-content');
                    if (typeof error_string !== typeof undefined && error_string !== false) { 
                    }
                    else{
                        error_string =  "This field is required";
                    }
                    if( (str_content == "") || ( str_content.length < minlength)){                 
                        flag = 0;
                        addErrorItem($(this), error_string);
                    }
                    else
                        addErrorItem($(this));
                }
                else
                    addErrorItem($(this));            
            });
            return flag;
        }   
        /****************************** Decline Interview Action  *************************************/
        function declineformAction(){
            var flag = 0;
            $(".other_reason_div").addClass("hidden");
            $("input[name = 'other_reason']").removeClass("edit");
            $(".decline_form_radio").each(function(){
                if($(this).prop("checked")){
                    flag = 1; 
                    if($(this).attr("data-type") == '1'){
                        $(".other_reason_div").removeClass("hidden");
                        $("input[name = 'other_reason']").addClass("edit"); 
                        if($.trim($("input[name = 'other_reason']").val()) == "")
                            flag = 0; 
                    }
                    else{
                        $(".other_reason_div").val("");
                    }
                }
            }); 
            if(flag)
                $(".decline_invite_form").prop('disabled', false);
            else
                $(".decline_invite_form").prop('disabled', true);
        }
        $("input[name = 'other_reason']").keyup(function(){
            declineformAction();
        }); 
        $(document).on("click", ".decline_form_radio", function(){
            declineformAction();
        });
    @endif
    
    @if(isset($proposal))
        $(".change_terms").click(function(){
            //changeterms_form
            $(".proposed_terms_display").addClass("hidden");
            $(".proposed_terms_edit").removeClass("hidden"); 
            proposal_budget($("#changeterms_form .proposal_amount"));
            checkProposalForm();
        });  
        $(".cancel_submit_proposal").click(function(){
            //changeterms_form
            $(".proposed_terms_display").removeClass("hidden");
            $(".proposed_terms_edit").addClass("hidden");
        });  
        function checkProposalForm(){
            var flag = 0;
            var org_value  =  $("#changeterms_form .proposal_amount").attr('data-orgvalue');
            var curr_value =  $("#changeterms_form .proposal_amount").val(); 
            if( parseFloat(org_value) ==  parseFloat(curr_value)){
                flag = 0;
            }
            else{
                flag = 1;
            } 
            if(flag)
                $(".submit_proposal").prop('disabled', false);
            else
                $(".submit_proposal").prop('disabled', true); 
        } 
        $(".submit_proposal").click(function(){
            $.ajax({ 
                url:   "{!! route('employee.changeterms' , $proposal->serial) !!}",
                type: 'POST',
                data:  $("#changeterms_form").serialize(),
                dataType: 'json',
                beforeSend: function (){
                    $("#cover-spin").show();
                },
                success: function(json){
                    if(json.status){
                        location.reload();
                    }
                    else{
                        swal({
                            title: "Error Occured",   
                            text:  json.msg,
                            type: "error",   
                            confirmButtonText: "Close" 
                            }).then(function(isConfirm) {
                            if(isConfirm){ 
                            }
                        });
                    }
                },
                complete: function () {
                    $("#cover-spin").hide();
                },
                error: function() {
                    $("#cover-spin").hide();
                }
            });
        }); 
        /****************************** Withdraw the proposal  *************************************/ 
        $(".withdraw_proposal").click(function(){
            $('#declineForm')[0].reset();
            declineformAction(); 
            $("#decline_interview_modal").modal("show"); 
        }); 
        $(".decline_invite_form").click(function(){
            //var flag        =  CheckProposalForm($("#declineForm"));    
           // if(flag){
                $.ajax({ 
                    url:   "{!! route('employee.jobs.declineaction', ['proposal', $proposal->serial]) !!}",
                    type: 'POST',
                    data:  $("#declineForm").serialize(),
                    dataType: 'json',
                    beforeSend: function (){
                        $("#cover-spin").show();
                    },
                    success: function(json){
                        if(json.status){
                           location.reload();
                        }
                        else{
                            swal({
								title: "Error Occured",   
								text:  json.msg,
								type: "error",   
								confirmButtonText: "Close" 
								}).then(function(isConfirm) {
								if(isConfirm){ 
								}
							});
                        }
                    },
                    complete: function () {
                        $("#cover-spin").hide();
                    },
                    error: function() {
                        $("#cover-spin").hide();
                    }
                });  
            //}
        }); 
    @endif
    
    @if(isset($invite))
        /****************************** Accept Interview Action  *************************************/
        $(".accept_interview").click(function(){
            $("select[name = 'specialized_role']").trigger("change"); 
            $("#accept_interview_modal").modal("show");
        }); 
        $(".accept_invite_form").click(function(){
            var flag        =  CheckProposalForm($("#proposalForm"));   
            if(flag){
                $.ajax({ 
                    url:   "{!! route('jobs_proposals', $job->serial) !!}",
                    type: 'POST',
                    data:  $("#proposalForm").serialize(),
                    dataType: 'json',
                    beforeSend: function (){
                        $("#cover-spin").show();
                    },
                    success: function(json){
                        if(json.status){
                           location.href = json.url;
                        }
                        else{
                            swal({
								title: "Error Occured",   
								text:  json.msg,
								type: "error",   
								confirmButtonText: "Close" 
								}).then(function(isConfirm) {
								if(isConfirm){ 
								}
							});
                        }
                    },
                    complete: function () {
                        $("#cover-spin").hide();
                    },
                    error: function() {
                        $("#cover-spin").hide();
                    }
                });  
            }
        });  
        /****************************** Decline Interview Action  *************************************/ 
        $(".decline_interview").click(function(){
            $('#declineForm')[0].reset();
            declineformAction(); 
            $("#decline_interview_modal").modal("show"); 
        }); 
        $(".decline_invite_form").click(function(){ 
            $.ajax({ 
                url:   "{!! route('employee.jobs.declineaction', ['invite', $invite->serial]) !!}",
                type: 'POST',
                data:  $("#declineForm").serialize(),
                dataType: 'json',
                beforeSend: function (){
                    $("#cover-spin").show();
                },
                success: function(json){
                    if(json.status){
                        location.reload();
                    }
                    else{
                        swal({
                            title: "Error Occured",   
                            text:  json.msg,
                            type: "error",   
                            confirmButtonText: "Close" 
                            }).then(function(isConfirm) {
                            if(isConfirm){ 
                            }
                        });
                    }
                },
                complete: function () {
                    $("#cover-spin").hide();
                },
                error: function() {
                    $("#cover-spin").hide();
                }
            });   
        });  
        var hash_url = window.location.hash;
        if(hash_url == "#acceptInterview"){
            $(".accept_interview").trigger("click");
        }
        if(hash_url == "#declineInterview"){
            $(".decline_interview").trigger("click");
        }
    @endif  

    $(".total-rating").each(function(){ 
        $(this).starRating({
            initialRating: $(this).attr('data-star'),
            starSize:   $(this).attr('data-size'),
            totalStars: 5,  
            disableAfterRate: false,
            readOnly: true
        });
    }); 
</script>
@stop