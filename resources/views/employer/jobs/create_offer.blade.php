@extends('layouts.app')
@section('title', "Make an Offer | Discover Gigs")
@section('css')
<style>
    /******************************************************************************/
    #drop-zone, .drop-zone{
        width: 100%;
        min-height: 150px;
        border: 3px dashed rgba(0, 0, 0, .3);
        border-radius: 5px;
        font-family: Arial;
        text-align: center;
        position: relative;
        font-size: 15px;
        color: #222;
    }  
    #drop-zone input, .drop-zone input {
        position: absolute;
        cursor: pointer;
        left: 0px;
        top: 0px;         
        opacity: 0;
    } 
    #drop-zone1 input{
        left: 74px;
        top: 46px;
    } 
    #drop-zone.mouse-over, .drop-zone.mouse-over  {
        border: 3px dashed rgba(0, 0, 0, .3);
        color: #7E7E7E;
    }
    #clickHere, .clickHere{
        display: inline-block;
        cursor: pointer;
        color: white;
        font-size: 17px;
        width: 150px;
        border-radius: 4px;
        background-color: #4679BD;
        padding: 10px;
    } 
    #clickHere:hover, .clickHere2:hover {
        background-color: #376199;    
    }
    #filename,  .filename {
        margin-top: 10px;
        margin-bottom: 10px;
        font-size: 14px;
        line-height: 1.5em;
    }                             
    .file-preview {
        background: #ccc;
        border: 5px solid #fff;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        font-size: 14px;
        margin-top: 5px;
    } 
    .closeBtn:hover { 
        color: red;
        display:inline-block;
    }
</style>
@endsection
@section('content')
@php 
    $job_poster_fee         =  App\Model\MasterSetting::getValue('job_poster_fee');
    $cards                  =  Auth::user()->getCards(); 
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            <h2 class="text-main pad-btm mar-no pad-no  text-semibold text-left text-dark">
                Hire 
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
        <form id = "offerForm"  method="post" enctype="multipart/form-data"  >
            <div class = "col-md-12">
                <div class="card m-md-top">
                    <div class = "col-md-12">
                        <div class = "clearfix pad-all bord-btm">
                            <h3 class = "text-bold text-dark"> Job Details  </h3>
                        </div> 
                    </div>
                    <div class = "col-md-12 pad-ver "> 
                        <div class="form-group clearfix">
                            <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Related Job Posting (Optional)  </label>   
                            <div class="col-sm-6"> 
                                <select class = "form-control select-jobposting-selection" name = "related_job_posting">
                                    <option value = "">Select an open job post</option>
                                    @foreach($jobs as $job)
                                        <option value = "{!! $job->serial !!}" @if(isset($pref_job) && ($pref_job->serial == $job->serial)) selected  @endif>{{ $job->headline }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block text-left"> 
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-sm-12 control-label text-left h5 text-dark" for="contract_title"> Contract Title </label>
                            <div class="col-sm-6">
                                <input name="contract_title" maxlength="512" class="form-control edit" data-content="Value is required and can't be empty" value="">
                                <span class="help-block">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class = "col-md-12 "> 
                <div class = "terms_html">
                </div>
                <div class="card mar-ver">
                    <div class="card-body  pad-no"> 
                        <div class = "col-md-12 @if(isset($offer)) hidden @endif">
                            <div class = "clearfix pad-all bord-btm">
                                <div class="checkbox">
                                    <input id="terms-form-checkbox"  class="magic-checkbox offer-action" type="checkbox">
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
                                        Hire {!! $freelancer->accounts->name !!}  
                                </button>
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@include('employer.jobs.partial.add_payment_modal')
@endsection
@section('javascript')
<script> 
    function switchJobs(){
        var current_job = $(".select-jobposting-selection").find("option:selected").val(); 
        $.ajax({
            url:   "{!! route('employer.jobs.getofferdetails', $freelancer->serial) !!}", 
            type: 'POST',
            data:  {job_id : current_job},
            dataType: 'json',
            beforeSend: function (){
            },
            success: function(json){
                if(json.status){
                    $("input[ name = 'contract_title']").val(json.contract_title);
                    $(".terms_html").html(json.html); 
                    validateOfferPage($("#offerForm"));
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
    $(".select-jobposting-selection").change(function(){
        switchJobs();
    });

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
    $(document).on("click", ".offer-action", function(){
        validateOfferPage($("#offerForm"));
    });
    $(document).on("change", ".weekly_limit_select", function(){
        validateOfferPage($("#offerForm"));
    });
    $(document).on("keyup", ".form-control.edit", function(){
        validateOfferPage($("#offerForm"));
    });
    
    $(".submit_offer").click(function(){
        $.ajax({
            url:   "{!! route('employer.jobs.createoffer', $freelancer->serial) !!}",
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


    switchJobs();
</script>
@stop