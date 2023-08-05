@extends('layouts.app')
@section('title', 'Contract Details')
@section('css') 
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>  
@endsection
@section('content') 
@php
    $job            =   $offer->getJob();
    $freelancer     =   $offer->getFreelancer();   
    $message_list   =   $offer->getMessageList( );   
    //get current milestone 
    if($offer->status == 1)
        $current_milestone = $offer->getCurrentMilestone(); 

    if($offer->status == 10){
        $client_feedback        =  $offer->getFeedback( Auth::user()->id );
        $freelancer_feedback    =  $offer->getFeedback( $offer->user_id );
    }
@endphp 
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            @include('partial.alert')
        </div>
        <div class = "col-md-12 mar-top">
            <div class = "card freelancer_card">
                <div class = "card-body pad-no">
                    <div class = "row">
                        <div class = "col-md-12">
                            <div class = "clearfix pad-all">
                                <div class = "row pad-hor">
                                    <div class = "col-md-7 col-sm-6 d-flex">
                                        <div class = "pad-top">
                                            <img class="img-circle img-md" src="{!! $freelancer->getImage() !!}"> 
                                        </div>
                                        <div class = "pad-top">
                                            <h4 class = "text-dark one-line-limit-noblock">{!! $job->headline !!}</h4> 
                                            <p class = "pt-15">{!! $freelancer->accounts->name !!} </p> 
                                        </div> 
                                    </div>
                                    <div class = "col-md-5 col-sm-6 "> 
                                        <div class = "pad-top">
                                            @if($offer->status < 10)
                                                @if($offer->status == 1)
                                                    @if(isset($current_milestone))
                                                        @php
                                                           $submit_submittion_request =  $current_milestone->checkSubmittionRequest();
                                                        @endphp
                                                        @if($current_milestone->deposit_status == 1)
                                                        <a href = "{!! route('employer.contract_payment', $offer->serial) !!}" class = "btn btn-mint">
                                                            @if(isset($submit_submittion_request))
                                                                Review & Pay
                                                            @else
                                                                Pay Now
                                                            @endif
                                                        </a>
                                                        @else
                                                            <a href = "{!! route('employer.employer.activate_milestone', $offer->serial) !!}" class = "btn btn-mint">
                                                                Activate Milestone
                                                            </a>
                                                        @endif 
                                                    @endif  
                                                    <button type = "button" class = "btn btn-default btn-card-button dis-title-button give-bonus-button"> 
                                                        <span class="text-mint"> Give Bonus </span>
                                                    </button>
                                                    <button type = "button" class = "btn btn-default btn-card-button dis-title-button end-contract-button"> 
                                                        <span class="text-mint"> End Contract </span>
                                                    </button>
                                                @endif
                                                
                                            @endif
                                        </div>
                                    </div> 
                                    @if($offer->status == 10 )
                                    <div class = "col-md-12">
                                       <p class = "text-dark mar-no"> 
                                           <img src = "{!! asset('img/checkbox.png') !!}" height = 15 /> Completed  {!! date('M d',  strtotime($offer->end_time)  ) !!} 
                                            @if(!isset($freelancer_feedback))
                                                <a  href = "{!! route('employer.contract.leavefeedback', $offer->serial)  !!}">   Give Feedback </a>
                                                <span class = "mar-lft total-rating" data-star = '0'></span>
                                            @else
                                                <span class = "mar-lft total-rating" data-star = '{!! $freelancer_feedback->rate_total !!}'></span>
                                            @endif
                                       </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class = "col-md-12">
                            <div class="panel freelancer_card_panel">
                                <div class="panel-heading bord-btm">
                                    <div class="panel-control pull-left">
                                        <ul class="nav nav-tabs">
                                            <li class = "active "><a href = "#contract-details-milestone" data-toggle="tab" class = "text-uppercase"> Milestones & PAYMENTS </a></li>  
                                            <li class = ""><a  href = "{!! route('messages') !!}?room={!! $message_list->serial !!}"    class = "text-uppercase"> Messages </a></li>
                                            <li class = ""><a href = "#contract-details-terms_settings" data-toggle="tab" class = "text-uppercase"> Terms & Settings </a></li>
                                            <li class = "">
                                                <a href = "#contract-details-feedback" data-toggle="tab" class = "text-uppercase"> 
                                                    Feedback   @if(($offer->status == 10) && !isset($freelancer_feedback))  <img height = 12 src = "{!! asset('img/background/dot.jpg') !!}" /> @endif
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body pad-no">
                                    <div class="tab-content"> 
                                        <div class="tab-pane fade   active in " id="contract-details-milestone">
                                            <div class = "pad-all clearfix bord-btm">
                                                <div class = "d-flex pad-hor">
                                                    <div class = "flex-1 ">
                                                        <p class = "pt-15 text-bold text-dark">Budget</p>
                                                        <p class = "pt-20 text-dark">${!! number_format($offer->amount, 2) !!}</p>
                                                    </div>
                                                    <div class = "flex-1 ">
                                                        <p class = "pt-15 text-bold text-dark">In Escrow </p>
                                                        <p class = "pt-20 text-dark">${!! number_format($offer->getTotalEscrow(), 2) !!}</p>
                                                     </div>
                                                    <div class = "flex-1 ">
                                                        <p class = "pt-15 text-bold text-dark">Milestones Paid</p>
                                                        <p class = "pt-20 text-dark">${!! number_format($offer->getMilestonePaid(), 2) !!}</p>
                                                    </div>
                                                    <div class = "flex-1 bord-rgt">
                                                        <p class = "pt-15 text-bold text-dark">Remaining</p>
                                                        <p class = "pt-20 text-dark">${!! number_format( $offer->getTotalRemaing(), 2) !!}</p>
                                                    </div>            
                                                    <div class = "flex-1 pad-hor">
                                                        <p class = "pt-15 text-bold text-dark">Total Payments</p>
                                                        <p class = "pt-20 text-dark">${!! number_format( $offer->getTotalPaid(), 2) !!}</p>
                                                    </div>
                                                </div> 
                                            </div>

                                            @if( ($offer->status == 1) && isset($current_milestone))
                                                <div class = "pad-all clearfix bord-btm">
                                                    <div class = " pad-hor">
                                                        <div class = "row">
                                                            <div class = "col-xs-6">
                                                                <h4 class = "text-dark">
                                                                    Current Milestone @if($current_milestone->status == 'active') - Active @endif
                                                                </h4>
                                                            </div>
                                                        </div>
                                                        <div class = "row">
                                                            <div class = "col-xs-6">
                                                                <h5 class = "text-dark">
                                                                    {!! $current_milestone->milestone_sort + 1 !!}. {!! $current_milestone->headline  !!} 
                                                                </h5>
                                                                <p class = "text-dark mar-lft "> ${!! $current_milestone->amount !!} @if($current_milestone->deposit_status == 1) (Funded) @endif </p>
                                                            </div>
                                                            <div class = "col-xs-6 text-right"> 
                                                                @if($current_milestone->deposit_status == 1)
                                                                    <a href = "{!! route('employer.contract_payment', $offer->serial) !!}" class = "btn btn-mint">
                                                                        @if(isset($submit_submittion_request))
                                                                            Review & Pay
                                                                        @else
                                                                            Pay Now
                                                                        @endif
                                                                    </a>
                                                                @else
                                                                    <a href = "{!! route('employer.employer.activate_milestone', $offer->serial) !!}" class = "btn btn-mint">
                                                                        Activate Milestone
                                                                    </a>
                                                                @endif
                                                            </div>  
                                                        </div>
                                                    </div> 
                                                </div>
                                            @endif
 
                                            <div class = "pad-all clearfix bord-btm">
                                                <div class = " pad-hor">
                                                    @php
                                                        $upcoming_milestones = $offer->getUpcomiingMilestones(); 
                                                    @endphp  
                                                    <div class="category_stuff clearfix">
                                                        <div class="col-md-12">
                                                            <a href="javascript:void(0)" class="category-anchor">
                                                                <h4 class="text-dark mar-ver"> 
                                                                    Upcoming milestones ({!! count($upcoming_milestones) !!})
                                                                    <span class="pull-right text-bold text-dark anchor-down">  
                                                                        <i class="fa fa-chevron-down"></i>
                                                                    </span> 
                                                                    <span class="pull-right text-bold text-dark anchor-up"> 
                                                                        <i class="fa fa-chevron-up"></i>
                                                                    </span>  
                                                                </h4> 
                                                            </a> 				
                                                        </div> 
                                                        <div class="col-md-12 category-items">
                                                            @forelse($upcoming_milestones as $upcoming_milestone_index => $upcoming_milestone)
                                                            <div class="row  @if($upcoming_milestone_index) bord-top @endif">
                                                                <div class="col-xs-10">
                                                                    <h5 class = "text-dark"> 
                                                                        {!! $upcoming_milestone->milestone_sort + 1 !!} - {!! $upcoming_milestone->headline  !!}
                                                                    </h5> 
                                                                </div> 
                                                                <div class = "col-xs-2">
                                                                    <p class = "text-dark text-right mar-lft"> ${!! $upcoming_milestone->amount !!} </p> 
                                                                </div>
                                                            </div> 
                                                            @empty
                                                                <div class = "row"> 
                                                                    <div class = "col-xs-12">
                                                                        <p class = "text-dark"> There are no upcoming milestones. </p>
                                                                    </div>
                                                                </div>
                                                            @endforelse

                                                            @if($offer->status == 1)
                                                            <div class = "row"> 
                                                                <div class = "col-xs-12">
                                                                    <button type="button" class="btn btn-default btn-card-button dis-title-button"> 
                                                                        <span class="text-mint"> <i class = "fa fa-plus"></i>  New Milestone </span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            @endif 
                                                        </div>
                                                    </div> 
                                                </div> 
                                            </div> 

                                            <div class = "pad-all clearfix bord-btm">
                                                <div class = " pad-hor">
                                                    @php
                                                        $completed_milestones = $offer->getCompletedMilestones(); 
                                                    @endphp  
                                                    <div class="category_stuff clearfix">
                                                        <div class="col-md-12">
                                                            <a href="javascript:void(0)" class="category-anchor">
                                                                <h4 class="text-dark mar-ver"> 
                                                                        Completed Milestones({!! count($completed_milestones) !!})
                                                                    <span class="pull-right text-bold text-dark anchor-down">  
                                                                        <i class="fa fa-chevron-down"></i>
                                                                    </span> 
                                                                    <span class="pull-right text-bold text-dark anchor-up"> 
                                                                        <i class="fa fa-chevron-up"></i>
                                                                    </span>  
                                                                </h4> 
                                                            </a> 				
                                                        </div> 
                                                        <div class="col-md-12 category-items">
                                                            @forelse($completed_milestones as $remaining_milestone)
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h5 class = "text-dark"> 
                                                                        {!! $remaining_milestone->milestone_sort + 1 !!}. {!! $remaining_milestone->headline  !!}
                                                                    </h5>
                                                                    <p class = "text-dark mar-lft"> ${!! $remaining_milestone->amount !!} (Paid) </p> 
                                                                </div> 
                                                            </div>
                                                            @empty
                                                                <div class = "row"> 
                                                                    <div class = "col-xs-12">
                                                                        <p class = "text-dark"> There are no completed milestones. </p>
                                                                    </div>
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div> 
                                                </div> 
                                            </div>
                                            @php
                                                $getAdditionalPayments  = $offer->getAdditionalPayments();
                                            @endphp
                                            <div class = "pad-all clearfix mar-btm">   
                                                <div class = " pad-hor"> 
                                                    <h4 class = "text-dark"> Additional payments and credits </h4>  
                                                    <div class="table-responsive">
                                                        <table class="table ">
                                                            <thead>
                                                                <tr>
                                                                    <th class = "text-dark text-bold">Date</th>
                                                                    <th class = "text-dark text-bold">Description</th>
                                                                    <th class = "text-dark text-bold">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>  
                                                                @forelse($getAdditionalPayments as $getAdditionalPayment)
                                                                <tr>
                                                                    <td class = "text-dark">{!! date('M d, Y', strtotime($getAdditionalPayment->created_at)) !!}  </td>
                                                                    <td  class = "text-dark">{!! $getAdditionalPayment->type !!}</td>
                                                                    <td class = "text-dark">${!! $getAdditionalPayment->amount  !!}</td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td class = "text-center" colspan = "3">
                                                                        There are no additional earnings.
                                                                    </td>
                                                                </tr>
                                                                @endforelse 
                                                            </tbody>
                                                        </table>
                                                    </div> 

                                                </div>
                                            </div>
                                        </div> 
                                        <div class="tab-pane fade" id="contract-details-terms_settings">
                                            <div class = "row">
                                                <div class = "col-md-6 col-sm-6 bord-rgt">
                                                    <div class = "pad-all clearfix bord-btm">
                                                        <div class = "pad-hor">
                                                            <h4 class = "text-dark"> Contract Info </h4>
                                                        </div>
                                                        <div class = "pad-all"> 
                                                            <div class = "col-xs-6">
                                                                <h5 class = "text-dark"> Start Date </h5>
                                                            </div>
                                                            <div class = "col-xs-6">
                                                                <p class = "text-dark">  {!!  date('F d, Y', strtotime($offer->start_time))   !!}  </p>
                                                            </div> 
                                                        </div>
                                                    </div> 
                                                    <div class = "pad-all clearfix"> 
                                                        <div class = "pad-all">
                                                            <div class = "col-xs-12">
                                                                <div class = "col-xs-6">
                                                                    <h5 class = "text-dark">  Contract ID </h5>
                                                                </div>
                                                                <div class = "col-xs-6">
                                                                    <p class = "text-dark mar-top-5"> {!! $offer->serial !!} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class = "col-md-6 col-sm-6">
                                                    <div class = "pad-all clearfix bord-btm">
                                                        <div class = "pad-hor">
                                                            <h4 class = "text-dark"> Description of work </h4>
                                                        </div>
                                                        <div class = "pad-hor description_item_card">   
                                                            @if(strlen($offer->work_details) > 170 )
                                                            <div class = "description-shortlist description-part"> 
                                                                <span class="pt-14 text-dark ">
                                                                    {!! substr($offer->work_details, 0, 170) !!} ...
                                                                </span>
                                                                <a class="btn-link text-mint btn-description-action more" href="javascript:void(0)"> more </a> 
                                                            </div> 
                                                            <div class = "description-detaillist hidden description-part"><span class="pt-14 text-dark">{{ $offer->work_details }}</span><a class="btn-link btn-description-action text-mint less" href="javascript:void(0)"> less </a> 
                                                            </div>
                                                            @else
                                                            <div class = "description-shortlist description-part"> 
                                                                <span class="pt-14 text-dark ">
                                                                    {{ $offer->work_details }}
                                                                </span>
                                                            </div>
                                                            @endif  
                                                        </div>
                                                    </div> 
                                                    <div class = "pad-all clearfix bord-btm">
                                                        <ul class="ats-actions list-unstyled pad-hor">
                                                            <li class="m-sm-bottom">
                                                                <a  target = "_blank" href = "{!! route('employer.jobs.viewoffer', $offer->serial) !!}" class="btn-link text-mint">
                                                                    <i class="fa fa-share-square-o vertical-align-middle m-0-left  mar-rgt pt-15"></i>View Offer
                                                                </a>
                                                            </li>  
                                                            @php
                                                                $proposal = $offer->getProposal();
                                                            @endphp 
                                                            @if(isset($proposal))
                                                            <li class="m-sm-bottom">
                                                                <a target = "_blank" href = "{!! route('employer.jobs.mainaction.user', [$job->serial, $freelancer->serial ]) !!}" class="btn-link text-mint text-capitalize">
                                                                    <i class="fa fa-share-square-o vertical-align-middle m-0-left  mar-rgt pt-15"></i>View original proposal
                                                                </a>
                                                            </li>
                                                            @endif 

                                                            @if(isset($job))
                                                            <li class="m-sm-bottom">
                                                                <a target = "_blank" href = "{!! route('employer.jobs.mainaction', [$job->serial, 'job-details']) !!}" class="btn-link text-mint text-capitalize">
                                                                    <i class="fa fa-share-square-o vertical-align-middle m-0-left  mar-rgt pt-15"></i>View original  {{ $job->type }} posting
                                                                </a> 
                                                            </li>
                                                            @endif 

                                                        </ul>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="contract-details-feedback">
                                            @if($offer->status == 10)
                                                <div class = "row mar-all pad-all">
                                                    <div class = "col-md-6 col-sm-6"> 
                                                        <h4 class = "text-dark">Employee Feedback to You</h4>
                                                        @if(isset($client_feedback))
                                                            @if(isset($freelancer_feedback))
                                                                <p  class = "total-rating" data-star = '{!! $client_feedback->rate_total !!}'></p>
                                                                <p  class = "text-dark">
                                                                    {{ $client_feedback->message  }}
                                                                </p> 
                                                            @else
                                                                <p class = "text-dark">
                                                                    Employee's feedback is hidden until you provide feedback.
                                                                </p>
                                                            @endif
                                                        @else
                                                            <p class = "text-dark">
                                                                No Feedback Given
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class = "col-md-6 col-sm-6"> 
                                                        <h4 class = "text-dark">Your Feedback to Employee</h4>
                                                        @if(isset($freelancer_feedback))
                                                            <p  class = "total-rating" data-star = '{!! $freelancer_feedback->rate_total !!}'></p>
                                                            <p class = "text-dark">
                                                                {{ $freelancer_feedback->message  }}
                                                            </p>
                                                        @else
                                                            <p class = "text-dark">
                                                                No Feedback Given
                                                            </p>
                                                            <a  href = "{!! route('employer.contract.leavefeedback', $offer->serial)  !!}" class="btn  btn-card-button  btn-mint">
                                                                <span class="text-bold   pt-15"> Give Feedback </span>
                                                            </a>
                                                        @endif 
                                                    </div> 
                                                </div>
                                            @else
                                                <div class = "row mar-all pad-all">
                                                    <div class="col-md-12 text-center pt-70">
                                                        <i class = "fa fa-file pad-rgt"> </i> 
                                                    </div> 
                                                    <div class="col-md-12 text-center">
                                                        <h4 class = "text-dark"> This contract is not yet eligible for feedback.</h4> 
                                                    </div> 
                                                </div>
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
    </div>
</div>

    @if($offer->status == 1) 
        <div class="modal fade" id = "give_bonus_modal" tabindex="-1" role="dialog" aria-labelledby="give_bonus_modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="give_bonus_modalLabel">Give bonus or expense reimbursement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id = "give_bonus_form"  method="post" enctype="multipart/form-data">
                            <div class = "row">
                                <div class = "col-sm-8 bord-rgt"> 
                                    <div class = "pad-all approve_pay_modal_body">
                                        <div class="form-group clearfix mar-no">
                                            <label class="col-sm-12 mar-btm control-label text-left text-bold text-dark"> Amount </label>  
                                            <div class="col-sm-6 input-group"> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" class="form-control hgt-35 text-right decimal-input edit" name = "bonus_amount" placeholder = "" value = "">
                                            </div>
                                            <div class="col-sm-12">
                                                <span class="help-block">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <label class="col-sm-12 mar-btm control-label text-left text-bold text-dark"> Reason </label>
                                            <div class="col-sm-8 col-xs-12">
                                                <select class = "form-control" name = "bonus_reason"> 
                                                    <option value = "Bonus">         Bonus                </option>
                                                    <option value = "Expense reimbursement"> Expense reimbursement</option>
                                                </select>
                                            </div>
                                        </div>
                                        @php
                                            $cards = Auth::user()->getCards();
                                        @endphp  
                                        <div class="form-group clearfix">
                                            <label class="col-sm-12 mar-btm control-label text-left text-bold text-dark"> Card </label>
                                            <div class="col-sm-8 col-xs-12">
                                                <select class = "form-control" name = "card">
                                                    @foreach($cards as $card)
                                                        <option value = "{!! $card->serial !!}">  {!! $card->card_type !!} ending in {!! $card->ext !!} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                    </div>  
                                </div>
                                <div class = "col-sm-4">
                                    <p class = "text-dark mar-btm pt-15">
                                        When you send a bonus or expense reimbursement your account will be charged.
                                    </p>
                                    <p class = "text-dark pt-15">
                                        The freelancer will receive funds.
                                    </p>
                                </div>
                            </div> 
                        </form> 
                    </div>
                    <div class = "modal-footer">
                        <div class = "row">
                            <div class = "col-xs-12">
                                <button type = "button" class = "btn btn-mint dis-title-button make_payment_button"> Make Payment </button>
                                <button type = "button"  data-dismiss="modal" class = "btn btn-default dis-title-button"> cancel </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class = "modal fade" id = "end_contract_modal" tabindex="-1" role="dialog" aria-labelledby="give_bonus_modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="give_bonus_modalLabel">End Contract</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id = "end_contract_form"  method="post" enctype="multipart/form-data">
                            <div class = "form-group clearfix">
                                <div class = "col-xs-12 text-center text-mint pt-70">
                                    <i class = "fa fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="form-group clearfix"> 
                                <div class="col-xs-12">
                                    <h4 class = "text-dark text-center">Are you sure you want to end this contract?</h4>
                                </div>
                            </div>
                            <div class="form-group clearfix"> 
                                <div class="col-xs-12">
                                    <p class = "text-dark text-center pt-15">You will be prompted to provide feedback and make any final payments in the following steps.</p>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class = "modal-footer">
                        <div class = "row">
                            <div class = "col-xs-12"> 
                                <button type = "button"  data-dismiss="modal" class = "btn btn-default dis-title-button"> Cancel </button>
                                <button type = "button" class = "btn btn-mint dis-title-button end_contract_modal_button"> End Contract </button>
                            </div>
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
    @if($offer->status == 1)
        // give bonus stuff
        $(".give-bonus-button").click(function(){ 
            $('#give_bonus_form')[0].reset(); 
            $("#give_bonus_modal").modal("show");
        }); 
        $(".make_payment_button").click(function(){
            var flag            = 1;
            var validate_string = ".form-control.edit";
            var obj             = $("#give_bonus_form"); 
            obj.find(validate_string).each(function(){
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
                        addErrorForm($(this), error_string);
                    }
                    else
                        addErrorForm($(this));
                }
                else
                    addErrorForm($(this));
            }); 
            if(flag){ 
                $.ajax({
                    url:  "{{route('employer.give_bonus', $offer->serial)}}",
                    type: 'POST',
                    dataType: 'json',
                    data:  $("#give_bonus_form").serialize(),
                    beforeSend: function (){
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
                    },
                    error: function() {
                    }
                }); 
            } 
        }); 
        // end contract stuff
        $(".end-contract-button").click(function(){
            $("#end_contract_modal").modal("show");
        });
        $(".end_contract_modal_button").click(function(){
            $.ajax({
                url:  "{{route('employer.contract.end_contract', $offer->serial)}}",
                type: 'POST',
                dataType: 'json',
                //data:  $("#give_bonus_form").serialize(),
                beforeSend: function (){
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
                },
                error: function() {
                }
            }); 
        });
    @endif

    @if($offer->status == 10)
        $(".total-rating").each(function(){
            $(this).starRating({
                initialRating: $(this).attr('data-star'),
                starSize:   14,
                totalStars: 5,  
                disableAfterRate: false,
                readOnly: true
            });
        }); 
    @endif
</script>
@stop