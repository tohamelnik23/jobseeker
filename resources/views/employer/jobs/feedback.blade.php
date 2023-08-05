@extends('layouts.app')
@section('title', 'Contract Details')
@section('css') 
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>   
@endsection
@section('content') 
@php
    $freelancer             =   $offer->getFreelancer();  
    $ending_reason_messages =   DB::table('end_reason')->where('type', 'client')->get();
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12 mar-top">
            <h2 class="text-main pad-btm mar-no pad-no  text-semibold text-left text-dark">
                Leave Feedback
            </h2> 
            <p class = 'text-dark pt-15'> Share your experiences! Your honest feedback provides helpful information for James Robot </p>
        </div>
        <div class = "col-md-12">
            <div class="card m-md-top">
                <div class="card-body  pad-no">
                    <div class="clearfix pad-all">  
                        <div class = "row">
                            <div class = "col-md-12"> 
                                <div class="media mar-btm">
                                    <div class="pull-left mar-rgt">
                                        <img src="{!! $freelancer->getImage() !!}" class="img-md img-circle  m-0 fs-exclude" alt="avatar">
                                    </div>
                                    <div class="media-body">
                                        <p class="mb-0 qa-wm-mo-fl-name pt-15">
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
                            <div class = "col-md-12">
                                <h4 class = "mar-top text-dark"> Contract Title</h4>
                                <p class = "text-dark pt-15"> {!! $offer->contract_title !!}</p>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class = "col-md-12">
            <form id = "feedbackForm"  method="post" enctype="multipart/form-data">
                <div class="card m-md-top">
                    <div class="card-body  pad-no">
                        <div class = "col-md-12 bord-btm">
                            <div class = "clearfix pad-all">
                                <h3 class = "text-bold text-dark">  Private Feedback  </h3>
                                <p class="  control-label text-left   text-dark" for=""> 
                                    This feedback will be kept anymous and never shared directly with the employee
                                </p>   
                            </div>
                        </div>
                        <div class = "col-md-12"> 
                            <div class="form-group clearfix">
                                <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Reason for ending contract </label>   
                                <div class="col-sm-6"> 
                                    <select class = "form-control edit" name = "ending_reason">
                                        <option value = "">Select a reason</option>
                                        @foreach($ending_reason_messages as $ending_reason_message)
                                            <option value = "{!! $ending_reason_message->id !!}">{!! $ending_reason_message->content !!}  </option>
                                        @endforeach
                                    </select>
                                    <span class="help-block text-left"> 
                                        <strong></strong>
                                    </span>
                                </div>
                            </div> 
                            <div class="form-group clearfix">
                                <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> How likely are you to recommend this client to a friend or a collegue? </label>   
                                <div class = "col-sm-8 col-md-6 col-xs-12"> 
                                    <div class = "row">
                                        <div class = "col-xs-12">
                                            <div id = "priority-range-def" class="mar-top">
                                                <ul class="likertSelectors clearfix"> 
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <li> 
                                                            <a href = "javascript:void(0)" class="btn btn-default" data-id = "{!! $i !!}">  
                                                                {!! $i !!}   
                                                            </a>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                            <div class="pad-top">
                                                <span class="pull-left text-dark"> Not at all likely </span>
                                                <span class="pull-right text-dark">   Extremely likely </span>
                                            </div>
                                        </div>
                                        <div class = "col-xs-12 has-error hidden recommend_rate_div">
                                            <span class="help-block text-left"> 
                                                <i>Please select this rate to share the information with discovergig</i>
                                            </span>
                                        </div>
                                        <input type = "hidden" name = "recommend_value" value = "" />
                                    </div> 
                                </div>
                            </div> 
                        </div>
                    </div>
                </div> 
                <div class="card m-md-top">
                    <div class="card-body  pad-no">
                        <div class = "col-md-12 bord-btm">
                            <div class = "clearfix pad-all">
                                <h3 class = "text-bold text-dark">  Public Feedback  </h3>
                                <p class="  control-label text-left   text-dark" for=""> 
                                    This feedback will be shared on your employee's profile only after they've left feedback for you.
                                </p>   
                            </div>
                        </div>
                        <div class = "col-md-12"> 
                            <div class="form-group clearfix">
                                <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Feedback to employee </label>    
                            </div> 
                            <div class="form-group clearfix m-btm-30">
                                <div class = "col-xs-9 col-sm-6 col-md-4 star-group">
                                    <span class = "jq-stars" data-id = "rate_skills" ></span>
                                    <input type = "hidden" class = "rate_value"  name = "rate_skills"    id = "rate_skills"  data-rate = "2" value = "0" />
                                </div>
                                <div class = "col-xs-3 col-sm-6 col-md-8"> 
                                    <span class = "text-dark"> Skills </span>
                                </div>
                            </div> 
                            <div class="form-group clearfix m-btm-30">
                                <div class = "col-xs-9 col-sm-6 col-md-4 star-group">
                                    <span class = "jq-stars" data-id = "rate_quality" ></span>
                                    <input type = "hidden" class = "rate_value"  name = "rate_quality"    id = "rate_quality"  data-rate = "2" value = "0" /> 
                                </div>
                                <div class = "col-xs-3 col-sm-6 col-md-8"> 
                                    <span class = "text-dark"> Quality of Work </span>
                                </div>
                            </div> 
                            <div class="form-group clearfix m-btm-30">
                                <div class = "col-xs-9 col-sm-6 col-md-4 star-group"> 
                                    <span class = "jq-stars" data-id = "rate_availability" ></span>
                                    <input type = "hidden" class = "rate_value"  name = "rate_availability"    id = "rate_availability"  data-rate = "1.5" value = "0" />  
                                </div>
                                <div class = "col-xs-3 col-sm-6 col-md-8"> 
                                    <span class = "text-dark"> Availability </span>
                                </div>
                            </div> 
                            <div class="form-group clearfix m-btm-30">
                                <div class = "col-xs-9 col-sm-6 col-md-4 star-group">
                                    <span class = "jq-stars" data-id = "rate_deadlines" ></span>
                                    <input type = "hidden"  class = "rate_value"  name = "rate_deadlines"   id = "rate_deadlines"  data-rate = "1.5" value = "0" />  
                                </div>
                                <div class = "col-xs-3 col-sm-6 col-md-8"> 
                                    <span class = "text-dark"> Adherence to Schedule </span>
                                </div>
                            </div> 
                            <div class="form-group clearfix m-btm-30">
                                <div class = "col-xs-9 col-sm-6 col-md-4 star-group"> 
                                    <span class = "jq-stars" data-id = "rate_communication" ></span>
                                    <input type = "hidden" class = "rate_value"  name = "rate_communication"   id = "rate_communication"  data-rate = "1.5" value = "0" />  
                                </div>
                                <div class = "col-xs-3 col-sm-6 col-md-8"> 
                                    <span class = "text-dark"> Communications </span>
                                </div>
                            </div>
                            <div class="form-group clearfix m-btm-30">
                                <div class = "col-xs-9 col-sm-6 col-md-4 star-group">
                                    <span class = "jq-stars" data-id = "rate_cooperation" ></span>  
                                    <input type = "hidden" class = "rate_value" name = "rate_cooperation"  id = "rate_cooperation"  data-rate = "1.5" value = "0" />
                                </div>
                                <div class = "col-xs-3 col-sm-6 col-md-8"> 
                                    <span class = "text-dark"> Cooperation </span>
                                </div>
                            </div>
                            <div class="form-group clearfix m-btm-30">
                                <p class="col-sm-12 pt-17 text-bold text-left  text-dark" for = ""> Total Score: <span class = "total_score_feedback">0.00</span> </p>    
                            </div>  
                            <div class="form-group clearfix">
                                <div class = "col-md-12">
                                    <h5 class = "text-dark">
                                        Message
                                    </h5> 
                                    <p class = "text-dark"> 
                                        Share your experience with this client to the Discovergig community
                                    </p> 
                                </div>
                                <div class = "col-md-6 col-sm-7 col-xs-12">
                                    <textarea name = "feedback_note" class="form-control" maxlenght = 2000 rows="6"></textarea>
                                    <span class="help-block"> 
                                        <strong></strong>  
                                    </span>
                                </div> 
                            </div> 
                        </div> 
                    </div>
                </div> 
                <div class="card m-md-top mar-btm">
                    <div class="card-body  pad-no">
                        <div class = "col-md-12 bord-btm">
                            <div class = "clearfix pad-all">
                                <button type = "button" class = "btn btn-mint submit-feedback-button"> Submit Feedback </button>
                                <button type = "button" class = "btn btn-default"> <span class = "text-mint">  Cancel </span>  </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('javascript') 
<script src="{{asset('plugins/simplerating/jquery.star-rating-svg.js')}}"></script>
<script> 
    $(".likertSelectors .btn").click(function(){
        $(".likertSelectors .btn").removeClass("btn-mint").addClass("btn-default");
        $(this).addClass("btn-mint").removeClass("btn-default");
        $("input[name = 'recommend_value']").val( $(this).attr('data-id')); 
    }); 
    // 1 2 2.75 3.5 4.25
    function calculateTotal(){
        var total_value = 0;
        $(".rate_value").each(function(){
                total_value += parseInt($(this).val()) * parseFloat($(this).attr('data-rate'));
        });
        total_value = total_value / 10;
        $(".total_score_feedback").html(total_value.toFixed(2));
    } 
    $(".jq-stars").each(function(){
        $(this).starRating({                        
            initialRating: 0,
            starSize: 19,   
            totalStars: 5,  
            disableAfterRate: false,
            callback: function(currentRating, $el){
                $("#" + $el.closest(".star-group").find(".jq-stars").attr('data-id')).val(currentRating);
                calculateTotal(); 
            },       
            useFullStars: true
        });                                                      
    });

    function validateFeedbackForm(obj){
        var flag  = 1;  
        var validate_string = ".form-control.edit"; 
        if($("input[name = 'recommend_value']").val() == ""){
            $(".recommend_rate_div").removeClass("hidden");
            flag = 0;
        }            
        else
            $(".recommend_rate_div").addClass("hidden"); 
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
        return flag;  
    }

    $(".submit-feedback-button").click(function(){
        var flag = validateFeedbackForm($("#feedbackForm"));

        if(flag){
            $.ajax({
                url:  "{{route('employer.contract.leavefeedback', $offer->serial)}}",
                type: 'POST',
                dataType: 'json',
                data:  $("#feedbackForm").serialize(),
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
        }
    });
</script>
@stop