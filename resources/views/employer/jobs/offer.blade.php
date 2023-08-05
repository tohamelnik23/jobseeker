@extends('layouts.app')
@if(isset($offer))
    @section('title', "Edit an Offer | Discover Gigs") 
@else
    @section('title', "Make an Offer | Discover Gigs") 
@endif 
@section('css')
@endsection
@section('content')
@php
    if(isset($offer)){
        $job = $offer->getJob();
        $freelancer = $offer->getFreelancer();
    } 
    $proposal               =  $job->getProposal($freelancer->id); 
    if(isset($proposal))
        $profile_freelancer =  $proposal->getProfile(); 
    else
        $profile_freelancer =  $freelancer->getRoles()[0];
    
    $cards                  =  Auth::user()->getCards(); 
    $job_poster_fee         =  App\Model\MasterSetting::getValue('job_poster_fee'); 
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            <h2 class="text-main pad-btm mar-no pad-no  text-semibold text-left text-dark">
                @if(isset($offer)) Edit Contract @else  Hire @endif
            </h2>
        </div> 
        <div class = "col-md-12">
            <div class="card m-md-top">
                <div class="card-body  pad-no">
                    <div class="clearfix pad-all"> 
                        <div class="media">
                            <div class="pull-left mar-rgt">
                                <img src="{!! $freelancer->getImage() !!}" class="img-md img-circle  m-0 fs-exclude" alt="avatar">
                            </div>
                            <div class="media-body">
                                <p class="mb-0 qa-wm-mo-fl-name pt-17">
                                    <a href="{!! route('freelancers.detail', $freelancer->serial) !!}" class  = "text-mint btn-link" target="_blank">
                                        <span class="fs-exclude">{!! $freelancer->accounts->name !!}</span>
                                    </a>
                                </p>  
                                <p class="mar-top text-bold text-dark">
                                    <span> <i class="fa fa-map-marker"></i> {!! Mainhelper::getStateFromValue($freelancer->accounts->state)   !!}</span>
                                </p> 
                            </div>
                        </div> 

                    </div>
                </div>
            </div>
        </div> 
        <div class = "col-md-12">
            <form id = "offerForm"  method="post" enctype="multipart/form-data"  >
                <div class="card m-md-top">
                    <div class="card-body  pad-no">
                        <div class = "col-md-12">
                            <div class = "clearfix pad-all bord-btm">
                                <h3 class = "text-bold text-dark">  Terms  </h3>
                            </div>
                        </div>
                        <div class = "col-md-12"> 
                            @if($job->payment_type == "fixed")
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for=""> Pay a fixed price for your project </label>  
                                    <div class="col-sm-4 input-group"> 
                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                        <input type="text" class="form-control decimal-input edit" id="offer_amount" name="offer_amount"  @if(isset($offer)) value = "{!! $offer->amount  !!}" @elseif(isset($proposal)) value = "{!! $proposal->proposal_amount  !!}"  @endif>
                                    </div>
                                    <div class="col-sm-12">
                                        <span class="help-block">
                                            <strong></strong>
                                        </span>
                                    </div> 
                                </div> 
                                @if(!isset($job))
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for=""> Deposit into Escrow </label>  
                                    <div class="col-sm-12">  
                                        <div class="radio">
                                            <input id="deposit-form-radio" class="magic-radio" type="radio" name="form-radio-button" checked="">
                                            <label for="deposit-form-radio" class = "text-dark"> Deposit $<span class = "despoist_amount"></span> for the whole project </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <span class="help-block">
                                            <strong></strong>
                                        </span>
                                    </div> 
                                </div> 
                                @endif
                            @endif 
                            @if($job->payment_type == "hourly")
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for=""> Pay by the hour </label>  
                                    <div class="col-sm-4 input-group"> 
                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                        <input type="text" class="form-control decimal-input edit" id="offer_amount" name="offer_amount"  @if(isset($offer)) value = "{!! $offer->amount  !!}" @elseif(isset($proposal)) value = "{!! $proposal->proposal_amount  !!}"  @endif>
                                        <span class="input-group-addon text-dark">/hour</span> 
                                    </div>
                                    <div class="col-sm-12 mar-top-5"> <em class = "mar-top"> {!! $freelancer->accounts->name !!}'s profile rate is {!! $profile_freelancer->getHourlyDescription() !!}</em>
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Weekly Limit </label>  
                                    <div class = "col-sm-12">
                                        <p class = ""> Setting a weekly limit is a great way to help ensure you stay on budget. </p>
                                    </div> 
                                    <div class = "col-sm-4 weekly_limit_select_part">
                                        <select name  = "weekly_limit" class = "form-control weekly_limit_select">
                                                <option value = "-1">No Limit</option>
                                            @for($i = 5; $i <= 40; $i += 5)
                                                <option value = "{!! $i !!}" @if(isset($offer))  @if($i == $offer->weekly_limit)  selected @endif @elseif( $i == 40 ) selected @endif > {!! $i !!} hrs/week  </option>
                                            @endfor
                                                <option @if(isset($offer) &&  ($offer->weekly_limit % 5 != 0) ) selected   @endif  value = ""> Other </option>
                                        </select>
                                    </div> 
                                    <div class = "col-sm-3 hidden weekly_limit_input_part">
                                        <input  name = "weekly_limit" class="form-control edit decimal-input weekly_limit_input"  disabled placeholder = "Enter the weekly limit"  @if(isset($offer) &&  ($offer->weekly_limit % 5 != 0)) value = "{!! $offer->weekly_limit !!}"  @endif />
                                    </div> 
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for = "">  Minimum Hours </label>   
                                    <div class = "col-sm-4 ">
                                        <input  name = "minimum_hours" class="form-control edit decimal-input" @if(isset($offer)) value = "{!! $offer->minimum_hours !!}"  @endif />
                                    </div>  
                                </div> 
                            @endif 
                        </div>
                    </div>
                </div> 
                @if( !count($cards) ||  ($job->payment_type == "fixed") )
                <div class="card m-md-top">
                    <div class="card-body  pad-no">
                        <div class = "col-md-12">
                            <div class = "clearfix pad-all bord-btm">
                                <h3 class = "text-bold text-dark">  Billing Method  </h3>
                            </div> 
                        </div>
                        <div class = "col-md-12 pad-ver ">
                            @if(count($cards))
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Card </label>   
                                    <div class="col-sm-6"> 
                                        <select class = "form-control billing-card-selection" name = "card">
                                            @foreach($cards as $card)
                                                <option value = "{!! $card->serial !!}">{!! $card->card_type !!} ending in {!! $card->ext !!}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block text-left"> 
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div> 
                            @else 
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left pt-15  text-dark" for=""> 
                                        Adding a billing method is required to show the freelancer you can pay once work is delivered. There is a {!! $job_poster_fee !!}% processing fee for all payments.  
                                    </label>  
                                </div> 
                                <div class="form-group clearfix">
                                    <div class = "col-md-12">
                                        <button type = "button" class = "btn btn-mint pt-17 add-billing-button">
                                            <i class  = "fa fa-plus mar-rgt"></i> Add Billing Now 
                                        </button>
                                    </div>
                                    <div class = "col-md-12 has-error mar-top">
                                        <span class="help-block text-left"> 
                                            <strong>Please add Billing Method</strong>
                                        </span>
                                    </div>
                                </div> 
                            @endif  
                        </div> 
                    </div>
                </div> 
                @endif 
                <div class="card m-md-top">
                    <div class="card-body  pad-no">
                        <div class = "col-md-12">
                            <div class = "clearfix pad-all bord-btm">
                                <h3 class = "text-bold text-dark">  Work Description  </h3>
                            </div>
                        </div>
                        <div class = "col-md-12">
                            <div class="form-group clearfix">
                                <label class="col-sm-12 control-label text-left h5 text-dark" for=""> Related Gig Listing </label>  
                                <div class = "col-sm-12 mar-top-5">
                                    <a href = "{!! route('jobs_details', $job->serial) !!}" class = "btn-link text-mint text-bold">{!! $job->headline !!}</a>
                                </div>
                            </div> 
                            <div class="form-group clearfix">
                                <label class="col-sm-12 control-label text-left h5 text-dark" for = "contract_title"> Contract Title </label>
                                <div class="col-sm-6">
                                    <input  name = "contract_title" maxlength = 512 class="form-control edit" data-content = "Value is required and can't be empty" @if(isset($offer)) value = "{!! $offer->contract_title !!}"   @else value = "{!! $job->headline !!}" @endif />
                                    <span class="help-block">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label class="col-sm-12 control-label text-left h5 text-dark" for="work_details"> Work Details </label>
                                <div class="col-sm-12  ">
                                    <textarea name = "work_details" maxlength = "5000" class="form-control edit" rows = "8">@if(isset($offer)){!! $offer->work_details !!}@else{!! $job->description !!}@endif</textarea>
                                    <span class = "help-block">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div> 
                        </div> 
                    </div>
                </div> 
                <div class="card mar-ver">
                    <div class="card-body  pad-no"> 
                        <div class = "col-md-12 @if(isset($offer)) hidden @endif">
                            <div class = "clearfix pad-all bord-btm">
                                <div class="checkbox">
                                    <input id="terms-form-checkbox" @if(isset($offer)) checked @endif class="magic-checkbox offer-action" type="checkbox">
                                    <label for="terms-form-checkbox" class = "text-dark"> 
                                        Yes, I understand and agree to the Discover Gigs Terms of Service, including the User Agreement and Privacy Policy. 
                                    </label>
                                </div>  
                            </div> 
                        </div> 
                        <div class = "col-md-12">
                            <div class = "clearfix pad-all ">
                                <a href="{!! route('employer.jobs') !!}" class="btn btn-mint-border text-mint">  Cancel </a> 
                                <button type="button" class="btn btn-mint submit_offer" disabled> 
                                    @if(isset($offer))
                                        Edit Offer
                                    @else
                                        Hire {!! $freelancer->accounts->name !!} 
                                    @endif
                                </button>
                            </div>
                        </div>   
                    </div>
                </div> 
            </form>
        </div>
    </div>
</div>
@include('employer.jobs.partial.add_payment_modal')
@endsection
@section('javascript')
<script>
    function validateOfferPage(obj){
        var flag  = 1;  
        var validate_string = ""; 
        validate_string 	= ".form-control.edit";
        if($("select[name = 'weekly_limit'] option:selected").val() == ""){
            $(".weekly_limit_select_part").addClass("hidden");
            $(".weekly_limit_input_part").removeClass("hidden");
            
            $(".weekly_limit_select").prop("disabled", true);
            $(".weekly_limit_input").prop("disabled", false);
        }
        else{
            $(".weekly_limit_select_part").removeClass("hidden");
            $(".weekly_limit_input_part").addClass("hidden");

            $(".weekly_limit_select").prop("disabled", false);
            $(".weekly_limit_input").prop("disabled", true);
        }

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
                    addErrorItem($(this), error_string);
                }
                else
                    addErrorItem($(this));
            }
            else
                addErrorItem($(this));            
        });
        
        var offer_amount = $("#offer_amount").val();
        offer_amount     = offer_amount * 1; 
        $(".despoist_amount").html( offer_amount.toFixed(2) );

        if($("#terms-form-checkbox").prop("checked") == false)
            flag  = 0;

        @if(!count($cards))
            flag = 0;
        @endif
        
        if(flag)
            $(".submit_offer").prop("disabled", false);
        else
            $(".submit_offer").prop("disabled", true);
    }
    $(".offer-action").click(function(){
        validateOfferPage($("#offerForm"));
    });
    $(".weekly_limit_select").change(function(){
        validateOfferPage($("#offerForm"));
    }); 
    $(".form-control.edit").keyup(function(){
        validateOfferPage($("#offerForm"));
    });
    validateOfferPage($("#offerForm"));
    $(".submit_offer").click(function(){
        $.ajax({
            @if(isset($offer))
                url:   "{!! route('employer.jobs.editoffer', $offer->serial) !!}",
            @else
                url:   "{!! route('employer.jobs.sendoffer', [$job->serial, $freelancer->serial]) !!}",
            @endif
            type: 'POST',
            data:  $("#offerForm").serialize(),
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
</script>
@stop