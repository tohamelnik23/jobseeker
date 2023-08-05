@extends('layouts.app')
@section('title', 'Offer Details') 
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>  
@endsection
@section('content')
@php
    $job_taker_fee  =   App\Model\MasterSetting::getValue('job_taker_fee');
    $benefit_fee    =   100 - $job_taker_fee;
    $job            =   $offer->getJob(); 
    $client         =   $offer->getClient();   
@endphp 
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            @include('partial.alert')
        </div>
        <div class = "col-md-12">
            <h2 class="text-main pad-btm mar-no  pad-lft text-semibold text-left text-dark">  
                View Offer
            </h2>
        </div> 
        <div class = "col-md-9 col-sm-8">
            <div class = "card mar-top mar-btm">
                <div class = "card-body  pad-no"> 
                    <div class = "col-lg-12   pad-all bord-btm">
                        <div class  = "pad-hor">
                            <div class = "form-group mar-top clearfix">
                                <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                    Status
                                </h5>
                                <div class = "col-md-8 col-sm-7 text-dark">
                                    {!! $offer->getStatus() !!}
                                </div>
                            </div> 
                            <div class = "form-group clearfix">
                                <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                    Contract Title
                                </h5>
                                <div class = "col-md-8 col-sm-7 text-dark">
                                   {!! $offer->contract_title !!}
                                </div>
                            </div> 
                            @if(isset($job))
                                <div class = "form-group clearfix">
                                    <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                        Related Gig Posting
                                    </h5>
                                    <div class = "col-md-8 col-sm-7">
                                        <a href = "{!! route('jobs_details', $job->serial) !!}" target = "_blank" class = "btn-link text-mint">{!! $job->headline !!}</a>
                                    </div>
                                </div>  
                                <div class = "form-group clearfix">
                                    <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                        Gig Category
                                    </h5>
                                    <div class = "col-md-8 col-sm-7 text-dark">
                                        {!! $job->getJobCategory()->industry !!}
                                    </div>
                                </div>  
                                <div class = "form-group clearfix">
                                    <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                        Gig Sub Category
                                    </h5>
                                    <div class = "col-md-8 col-sm-7 text-dark">
                                        {!! $job->getJobType()->name !!}
                                    </div>
                                </div>
                            @endif 
                            @if($offer->status == 0)
                            <div class = "form-group clearfix">
                                <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                    Offer Expires
                                </h5>
                                <div class = "col-md-8 col-sm-7 text-dark">
                                   {!! date('F d, Y', strtotime($offer->start_time)) !!}
                                </div>
                            </div>
                            @endif 
                            <div class = "form-group clearfix">
                                <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                    Offer Date
                                </h5>
                                <div class = "col-md-8 col-sm-7 text-dark">
                                    {!! date('F d, Y', strtotime($offer->created_at)) !!}
                                </div>
                            </div> 
                            @if($offer->status !== 3)
                            <div class = "form-group clearfix">
                                <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                    Start Date
                                </h5>
                                <div class = "col-md-8 col-sm-7 text-dark">
                                    {!! date('F d, Y') !!}
                                </div>
                            </div> 
                            @endif 
                            @if($offer->payment_type == "hourly")
                            <div class = "form-group clearfix">
                                <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                    Weekly Limit
                                </h5>
                                <div class = "col-md-8 col-sm-7 text-dark">
                                    {!! $offer->weekly_limit !!} hours per week
                                </div>
                            </div>
                            @endif 
                        </div>
                    </div>
                    <div class = "col-lg-12 pad-all">
                        <div class = "pad-hor">
                            @if($offer->payment_type == "hourly") 
                                <div class="form-group clearfix mar-top">
                                    <div class="col-xs-6">
                                        <p class="pt-14 h4 text-dark mar-no">  Hourly Rate </p>
                                        <p class="text-dark pt-14"> Total amount the client will see </p>
                                    </div>
                                    <div class="col-xs-6 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" disabled class="form-control hgt-35 text-right proposal_amount proposal_budget decimal-input edit" name="proposal_amount" placeholder="" value="{!! $offer->amount !!}"> 
                                                <span class="input-group-addon text-dark">/hr</span> 
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <hr>
                                    </div>
                                </div> 
                                <div class="form-group clearfix mar-top">
                                    <div class="col-xs-6">
                                        <p class="pt-14 h4 text-dark mar-no"> {!! $job_taker_fee !!}% Service Fee </p> 
                                    </div>
                                    <div class="col-xs-6 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" disabled class="form-control hgt-35 text-right proposal_amount proposal_budget decimal-input edit" name="proposal_amount" placeholder="" value="{!! number_format($offer->amount * $job_taker_fee / 100 , 2) !!}"> 
                                                <span class="input-group-addon text-dark">/hr</span> 
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-xs-12">
                                        <hr>
                                    </div>
                                </div> 
                                <div class="form-group clearfix mar-top">
                                    <div class="col-xs-6">
                                        <p class="pt-14 h4 text-dark mar-no">  You will receive </p>
                                        <p class="text-dark pt-14"> The estimated amount you'll receive after service fees </p>
                                    </div>
                                    <div class="col-xs-6 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" disabled class="form-control hgt-35 text-right proposal_amount proposal_budget decimal-input edit" name="proposal_amount" placeholder="" value="{!! number_format($offer->amount * $benefit_fee / 100, 2) !!}"> 
                                                <span class="input-group-addon text-dark">/hr</span> 
                                            </div>    
                                        </div> 
                                    </div>
                                    <div class="col-xs-12">
                                        <hr>
                                    </div>
                                </div>
                            @endif 
                            @if($offer->payment_type == "fixed")
                                <div class="form-group clearfix mar-top">
                                    <div class="col-xs-6">
                                        <p class="pt-14 h4 text-dark mar-no">  Amount </p>
                                        <p class="text-dark pt-14"> Total amount the client will see </p>
                                    </div>
                                    <div class="col-xs-6 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" disabled class="form-control hgt-35 text-right proposal_amount proposal_budget decimal-input edit" name="proposal_amount" placeholder="" value="{!! $offer->amount !!}">  
                                            </div>    
                                        </div> 
                                    </div>
                                    <div class="col-xs-12">
                                        <hr>
                                    </div>
                                </div>  
                                <div class="form-group clearfix mar-top">
                                    <div class="col-xs-6">
                                        <p class="pt-14 h4 text-dark mar-no"> {!! $job_taker_fee !!}% Service Fee </p> 
                                    </div>
                                    <div class="col-xs-6 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" disabled class="form-control hgt-35 text-right proposal_amount proposal_budget decimal-input edit" name="proposal_amount" placeholder="" value="{!! number_format($offer->amount * $job_taker_fee / 100, 2) !!}">  
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-xs-12">
                                        <hr>
                                    </div>
                                </div>
                                <div class="form-group clearfix mar-top">
                                    <div class="col-xs-6">
                                        <p class="pt-14 h4 text-dark mar-no">  You will receive </p>
                                        <p class="text-dark pt-14"> The estimated amount you'll receive after service fees </p>
                                    </div>
                                    <div class="col-xs-6 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" disabled class="form-control hgt-35 text-right proposal_amount proposal_budget decimal-input edit" name="proposal_amount" placeholder="" value="{!! number_format($offer->amount * $benefit_fee / 100, 2) !!}">  
                                            </div>    
                                        </div> 
                                    </div>
                                    <div class="col-xs-12">
                                        <hr>
                                    </div>
                                </div>
                            @endif 
                            <div class = "form-group clearfix">
                                <h5 class = "col-md-4 col-sm-5 text-dark mar-no">
                                    Work Description
                                </h5>
                                <div class = "col-md-8 col-sm-7 text-dark description-detaillist">{!! $offer->work_details !!}</div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>   
        <div class = "col-md-3 col-sm-4">
            <section class = "air-card-divider-sm pad-no profile-detail-group"> 
                <div class = "mar-top"> 
                    <div class = "row">
                        @if($offer->status == 0)
                        <div class = "col-md-12">
                            <button type = "button" class="btn btn-block accept_offer btn-mint">Accept Offer</button>
                        </div>
                        @endif 
                        @php
                            $message_list = $offer->getMessageList();
                        @endphp
                        @if(isset($message_list))
                        <div class = "col-md-12 mar-top">
                            <a href = "{!! route('messages') !!}?room={!! $message_list->serial !!}"  class="btn btn-block btn-default text-mint btn-card-button"> Messages </a>
                        </div>
                        @endif 
                        @if($offer->status == 0)
                            <div class = "col-md-12 mar-top">
                                <button class="btn btn-block btn-default decline_offer text-mint btn-card-button"> Decline Offer </button>
                            </div>  
                        @endif 
                        @if(isset($job))
                            @php
                                $proposal = $job->getProposal($offer->user_id);
                            @endphp
                            @if(isset($proposal))
                            <div class = "col-md-12 mar-ver">
                                <a href = "{!! route('jobs_proposal_details', $proposal->serial) !!}" class="btn btn-block btn-default text-mint btn-card-button"> Original Proposal </a>
                            </div>
                            @endif 
                        @endif
                    </div>
                </div>
            </section> 
            <section class = "air-card-divider-sm pad-no profile-detail-group"> 
                <div class = "mar-top">
                    <h4 class="m-sm-bottom text-dark"> About the client </h4>
                </div>
            </section> 
            <section class = "air-card-divider-sm pad-no profile-detail-group"> 
                <div class = "mar-top"> 
                    @if(isset($client))
						@include('partial.client_history')
					@endif
                </div>
            </section>
        </div>
    </div> 
</div> 
@if($offer->status == 0)
    @php
        $decline_messages = DB::table('decline_reasons')->where('type', 'invite_freelancer')->get();
    @endphp
    <div class="modal fade" id="decline_interview_modal" tabindex="-1" role="dialog" aria-labelledby="decline_interview_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyprofilepicModalLabel">Decline Offer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class = "pad-all">  
                        <form id = "declineForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class = "row">
                                <div class = "col-md-12">
                                    <p class = "text-dark"> We  will politely notify the client that you are not interested. The client wil be able to view the reason you've declined the offer. </p>
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
                                        <button type="button" disabled class="btn btn-mint decline_invite_form"> Decline Offer </button>
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
    <div class="modal fade" id="accept_offer_modal" tabindex="-1" role="dialog" aria-labelledby="accept_offer_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyprofilepicModalLabel">Accept Offer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class = "pad-all">
                        <form id = "acceptForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class = "row">
                                <div class = "col-md-12">
                                    <h5 class = "text-dark">
                                        Message to Client
                                    </h5> 
                                </div>
                                <div class="col-sm-12">
                                    <textarea name = "message_client" class="form-control edit" maxlenght = 5000 rows="6"></textarea>
                                    <span class="help-block"> 
                                        <strong></strong>
                                    </span>
                                </div> 
                                @if(Auth::user()->accounts->loan_enable)  
                                    <div class="col-xs-12">
                                        <hr>
                                    </div>
                                    <div class="col-xs-12">
                                        <p class = "pt-14  h4 text-dark"> Get Paid In Advance? <yes> <no> </p>
                                    </div> 
                                    <div class="col-xs-12">
                                        <div class="radio"> 
                                            <input id="getpaid-form-radio" class="magic-radio" type="radio" name = "getpaid_inadvance" value = "yes">
                                            <label for="getpaid-form-radio">Yes</label> 
                                            <input id="getpaid-form-radio-2" class="magic-radio" type="radio" name = "getpaid_inadvance" checked="" value = "no">
                                            <label for="getpaid-form-radio-2">No</label> 
                                        </div>
                                    </div> 
                                    <div class = "get_paid_advance hidden">
                                        @php
                                            $bankinformation = Auth::user()->getBankInformation();
                                        @endphp
                                        @if(isset($bankinformation)) 
                                            @php
                                                $lending_power          =   Auth::user()->accounts->loan_amount - Auth::user()->getLoanValue('total_loans_pending'); 
                                                $loan_fee 	            =	100 - Auth::user()->accounts->loan_fee; 
                                                $offer_loan_amount      =   $offer->EstimatedBudget() *  $loan_fee / 100; 
                                                $loan_amount            =   $offer_loan_amount;
                                                if( $offer_loan_amount >   $lending_power )
                                                    $loan_amount = $lending_power;
                                                if($loan_amount < 0)
                                                    $loan_amount = 0;
                                            @endphp 
                                            @if($loan_amount > 0)
                                                <div class = "col-md-12">
                                                    <h5 class = "text-dark">
                                                        Advance Amount 
                                                    </h5>
                                                    <p class="text-dark pt-14"> Total amount should be less than ${!! number_format($loan_amount, 2) !!} </p>
                                                </div> 
                                                <div class="col-xs-12 mar-no ">
                                                    <div class="form-group clearfix"> 
                                                        <div class="col-sm-6 input-group "> 
                                                            <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                            <input type="text" class="form-control decimal-input" data-max = "{!! $loan_amount !!}" name="borrowing_amount" placeholder="" value="{!! round($loan_amount, 2) !!}">  
                                                        </div>    
                                                    </div> 
                                                </div> 
                                            @else 
                                                <div class="col-xs-12 mar-ver">
                                                    <span class = "text-dark text-bold"> Your Borrowing Power is at its limit.  Request an increase </span> <a href = "{!! route('loans.request') !!}" class = "btn-link">HERE</a>
                                                </div> 
                                            @endif
                                        @else
                                            <div class = "form-group clearfix">
                                                <div class="col-xs-12">
                                                    <span class = "text-dark text-bold">Get Approval to Borrow </span> <a href = "{!! route('settings.bankcards') !!}" class = "btn-link">HERE</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div> 
                                @endif 
                                <div class = "col-md-12 mar-top"> 
                                    <div class="checkbox">
                                        <input id="terms-form-checkbox"  class="magic-checkbox offer-action" type="checkbox">
                                        <label for="terms-form-checkbox" class = "text-dark"> 
                                            Yes, I understand and agree to the Discover Gig Terms of Service.
                                        </label>
                                    </div>   
                                </div>  
                                <div class="col-sm-12 mar-top action_btn_group"> 
                                    <button type="button" disabled class="btn btn-mint accept_form"> Accept Offer </button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
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
    @if($offer->status == 0)
        /* Decline Action */
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
        $(".decline_invite_form").click(function(){
            //var flag        =  CheckProposalForm($("#declineForm"));    
           // if(flag){
                $.ajax({ 
                    url:   "{!! route('employee.jobs.declineaction', ['offer', $offer->serial]) !!}",
                    type: 'POST',
                    data:  $("#declineForm").serialize(),
                    dataType: 'json',
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
            //}
        }); 
        $(".decline_offer").click(function(){
            $('#declineForm')[0].reset();
            declineformAction(); 
            $("#decline_interview_modal").modal("show"); 
        });
        /*************************** accept offer *********************************/
        function validateOfferPage(obj, empty_val = ""){
            var flag            =  1;  
            var validate_string =  ""; 
            validate_string 	=  ".form-control.edit";
            obj.find(validate_string).each(function(){
                if($(this).prop('disabled') == false){
                    var attr_name           =   $(this).attr('name');
                    var str_content         =   $.trim($(this).val()); 
                    var data_error_srting   =   "";
                    var minlength           =   $(this).attr('minlength'); 
                    if (typeof minlength !== typeof undefined && minlength !== false) { 
                    }
                    else{
                        minlength = 0;
                    }
                    var error_string        =   $(this).attr('data-content');
                    if (typeof error_string !== typeof undefined && error_string !== false) {
                    }
                    else{
                        error_string =  "This field is required";
                    }
                    if( (str_content == "") || ( str_content.length < minlength)){
                        flag = 0;
                        if(empty_val == "clear")
                            addErrorItem($(this));
                        else
                            addErrorItem($(this), error_string);
                    }
                    else
                        addErrorItem($(this));
                }
                else
                    addErrorItem($(this));
            }); 
            if($(".get_paid_advance").length ){
                if($("input[name = 'borrowing_amount']").hasClass('edit') ){ 
                    var current_value   = parseFloat($("input[name = 'borrowing_amount']").val());
                    var max_value       = parseFloat($("input[name = 'borrowing_amount']").attr('data-max')); 
                    if(current_value > max_value){ 
                        flag = 0;
                        addErrorItem($("input[name = 'borrowing_amount']"), "The amount should be less than $" + max_value.toFixed(2));
                    }
                    else{
                        addErrorItem($("input[name = 'borrowing_amount']"));
                    }
                }
            }

            if($("#terms-form-checkbox").prop("checked") == false)
                flag  = 0;


            if(flag)
                $(".accept_form").prop("disabled", false);
            else
                $(".accept_form").prop("disabled", true);
        } 
        $(".offer-action").click(function(){ 
            validateOfferPage($("#acceptForm"));
        });
        $(document).on("keyup", ".form-control.edit" ,function(){
            validateOfferPage($("#acceptForm"));
        }); 
        $(".accept_offer").click(function(){
            $('#acceptForm')[0].reset();
            GetPaidAdvance();
            validateOfferPage($("#acceptForm"), "clear");
            $("#accept_offer_modal").modal("show"); 
        }); 
        $(".accept_form").click(function(){
            $.ajax({
                url:   "{!! route('jobs_offer_accept', $offer->serial) !!}", 
                type: 'POST',
                data:  $("#acceptForm").serialize(),
                dataType: 'json',
                beforeSend: function (){
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
                },
                error: function() {
                }
            });
        });
        function GetPaidAdvance(){
            if($('input[name="getpaid_inadvance"]:checked').val() == "yes"){
                $(".get_paid_advance").removeClass("hidden");
                $("input[name = 'borrowing_amount']").addClass('edit');
            }
            else{
                $(".get_paid_advance").addClass("hidden");
                $("input[name = 'borrowing_amount']").removeClass('edit');
            }
        }
        $("input[name = 'getpaid_inadvance']").click(function(){
            GetPaidAdvance();
        }); 
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