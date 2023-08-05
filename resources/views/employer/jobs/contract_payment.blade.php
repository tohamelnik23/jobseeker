@extends('layouts.app')
@section('title', 'Payment')
@section('css') 
@endsection
@section('content')

@php
    $submit_submittion_request =  $current_milestone->checkSubmittionRequest();
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            @include('partial.alert')
        </div> 
        <div class = "col-md-12 mar-ver"> 
            <div class="card">
                <div class="card-header">
                    Payment 
				</div>
                <div class="card-body">
                    @if(isset($submit_submittion_request))
                        <div class = "form-group clearfix">
                            <div class = "col-xs-6">
                                <h5 class = "text-dark"> Status </h5>
                            </div>
                            <div class = "col-xs-6">
                                <p class = "text-dark"> Payment Requested </p>
                            </div>
                        </div>
                        <div class = "form-group clearfix">
                            <div class = "col-xs-6">
                                <h5 class = "text-dark"> Amount in Escrow </h5>
                            </div>
                            <div class = "col-xs-6">
                                <p class = "text-dark"> ${!! $offer->getTotalEscrow() !!}<!-- paid from Mastercard Ending in 9772 on Nov 27 --> </p>
                            </div>
                        </div>
                        <div class = "form-group clearfix">
                            <div class = "col-xs-6">
                                <h5 class = "text-dark"> Original Amount </h5>
                            </div>
                            <div class = "col-xs-6">
                                <p class = "text-dark"> ${!! $current_milestone->amount !!} </p>
                            </div>
                        </div>
                        <div class = "form-group clearfix">
                            <div class = "col-xs-6">
                                <h5 class = "text-dark"> Amount Requested </h5>
                            </div>
                            <div class = "col-xs-6">
                                <p class = "text-dark"> ${!! $submit_submittion_request->amount !!}   </p>
                            </div>
                        </div>
                        <div class = "form-group clearfix">
                            <div class = "col-xs-12">
                                <button type = "button" class = "btn btn-mint dis-title-button approve_pay_button">
                                    Approve & Pay
                                </button>
                                <button type = "button" class = "mar-lft btn btn-default   dis-title-button">
                                    <span class = "text-mint"> Request Changes</span>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class = "form-group clearfix">
                            <div class = "col-xs-6">
                                <h5 class = "text-dark"> Due Date </h5>
                            </div>
                            <div class = "col-xs-6">
                                <p  class = "text-dark"> {!! date('F d, Y', strtotime($current_milestone->start_date)) !!}</p>
                            </div>
                        </div> 
                        <div class = "form-group clearfix">
                            <div class = "col-xs-6">
                                <h5 class = "text-dark"> Status </h5>
                            </div>
                            <div class = "col-xs-6">
                                <p class = "text-dark"> Active / Funded </p>
                            </div>
                        </div>
                        <div class = "form-group clearfix">
                            <div class = "col-xs-6">
                                <h5 class = "text-dark"> Amount in Escrow </h5>
                            </div>
                            <div class = "col-xs-6">
                                <p class = "text-dark"> ${!! $offer->getTotalEscrow() !!} </p>
                            </div>
                        </div> 
                        <div class = "form-group clearfix">
                            <div class = "col-xs-12">
                                <p>Note: The freelancer has not formally requested payment, but you may release the payment for milestone if it has been completed to your satisfaction.</p>
                            </div>
                        </div> 
                        <div class = "form-group clearfix">
                            <div class = "col-xs-12">
                                <button type = "button" class = "btn btn-mint dis-title-button approve_pay_button">
                                    Pay
                                </button> 
                            </div>
                        </div>
                    @endif  
                </div>
            </div> 

            @php
                $submit_works = $current_milestone->getSubmissions();
            @endphp
            <div class="card m-md-top ">
                <div class="card-header">
                    Work Submission
				</div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-dark">
                        <thead>
                            <tr>
                                <th>Submitted</th>
                                <th>Message</th>
                                <th >Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submit_works as $submit_work)
                            <tr>
                                <td> {!! date('F d, Y', strtotime($submit_work->created_at)) !!} </td>
                                <td>{{ $submit_work->message  }}</td>
                                <td>${!! $submit_work->amount !!}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class = "text-center" colspan = "3">
                                    There are no work submissions.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> 

                </div>
            </div>
            
        </div>
    </div>
</div>
@php
    $escrow_amount =  $offer->getTotalEscrow();
    if(isset($submit_submittion_request)){
        $request_amount =   $submit_submittion_request->amount;
    }
    else{
        $request_amount =   $escrow_amount;
    }
@endphp
 
<div class="modal fade" id = "approve_pay_modal" tabindex="-1" role="dialog" aria-labelledby="approve_pay_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyprofilepicModalLabel">Approve and Pay</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id = "send_payment_form"  method="post" enctype="multipart/form-data"  >
                    <div class = "pad-all approve_pay_modal_body">
                        <div class="form-group clearfix mar-no">
                            <label class="col-sm-12 mar-btm control-label text-left text-bold text-dark">Release from escrow </label> 
                            <div class="col-sm-12 release-div-disaplay ">
                                <p class = "text-dark"> ${!! $request_amount !!}  
                                    <a href = "javascript:void(0)"> <i class = "fa fa-pencil mar-lft icon-2x approve_pay_action release_div_action"></i> </a>
                                </p>
                            </div> 
                            <div class="col-sm-4 input-group  release-div hidden"> 
                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                <input type="text" class="form-control hgt-35 text-right decimal-input edit" name = "release_amount" data-max = "{!! $escrow_amount   !!}" placeholder = "" value = "{!! $request_amount  !!}">
                            </div>
                            <div class="col-sm-12">
                                <span class="help-block">
                                    <strong></strong>
                                </span>
                            </div>
                        </div> 
                        @php
                            $cards = Auth::user()->getCards();
                        @endphp 
                        <div class="form-group clearfix">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <input id = "bonus-form-checkbox" class="magic-checkbox approve_pay_action" type="checkbox">
                                    <label for = "bonus-form-checkbox" class = "text-dark"> Add bonus (optional)</label>
                                </div> 
                            </div>
                            <div class="col-sm-4 input-group hidden   bonus-div hidden"> 
                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                <input type="text" class="form-control hgt-35 text-right decimal-input" name = "bonus_amount" placeholder = "" value = "">  
                            </div> 
                            <div class="col-sm-12  bonus-div hidden">
                                <span class="help-block">
                                    <strong></strong>
                                </span>
                            </div>
                        </div> 

                        <div class="form-group  bonus-div hidden clearfix">
                            <label class="col-sm-12 mar-btm control-label text-left text-bold text-dark"> Card for bonus </label>
                            <div class="col-sm-6 col-xs-12">
                                <select class = "form-control billing-card-selection" name = "card">
                                    @foreach($cards as $card)
                                        <option value = "{!! $card->serial !!}">  {!! $card->card_type !!} ending in {!! $card->ext !!} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  
                        <div class="form-group clearfix mar-top">
                            <label class="col-sm-12 control-label text-left text-bold text-dark"> Contract Status </label> 
                            <div class="col-sm-12">
                                <div class="radio">
                                    <input id="contract-form-radio" class="magic-radio" type="radio" name = "contract_status"  value = "end">
                                    <label for="contract-form-radio" class = "text-dark">End the contract - Work is complete</label>
                                </div> 
                                <div class="radio">
                                    <input id="contract-form-radio-2" class="magic-radio" type="radio" name = "contract_status" value = "continue" checked="" >
                                    <label for="contract-form-radio-2" class = "text-dark">Keep the contract open - I've got more for Albert</label>
                                </div>
                            </div>
                        </div> 
                    </div>
                </form> 
            </div>
            <div class = "modal-footer">
                <div class = "row">
                    <div class = "col-xs-12">
                        <button type = "button" class = "btn btn-mint dis-title-button send_payment_button"> Send Payment </button>
                        <button type = "button"  data-dismiss="modal" class = "btn btn-default dis-title-button"> cancel </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('javascript') 
<script>
    function display_approve_modal(){
        if($("#bonus-form-checkbox").prop("checked")){
            $(".bonus-div").removeClass("hidden"); 
            $("#approve_pay_modal input[name = 'bonus_amount']").prop("disabled", false);
            $("#approve_pay_modal input[name = 'bonus_amount']").addClass("edit");
            $("#approve_pay_modal input[name = 'bonus_amount']").val(0);
        }
        else{
            $(".bonus-div").addClass("hidden");
            $("#approve_pay_modal input[name = 'bonus_amount']").prop("disabled", true);
            $("#approve_pay_modal input[name = 'bonus_amount']").removeClass("edit");
        } 

        if($(".release_div_action").hasClass("checked")){ 
            $(".release-div").removeClass("hidden");  
            $(".release-div-disaplay").addClass("hidden"); 
        }
        else{ 
            $(".release-div").addClass("hidden");
            $(".release-div-disaplay").removeClass("hidden");
        } 
    } 
    $(".release_div_action").click(function(){
        if($(".release_div_action").hasClass("checked")){
            $(".release_div_action").removeClass("checked");
            $("#approve_pay_modal input[name = 'release_amount']").val($("#approve_pay_modal input[name = 'release_amount']").attr('data-max'));
        }
        else{
            $(".release_div_action").addClass("checked");
            $("#approve_pay_modal input[name = 'release_amount']").val($("#approve_pay_modal input[name = 'release_amount']").attr('data-max'));
        }
        display_approve_modal();
    }); 
    $(".approve_pay_action").click(function(){
        display_approve_modal();
    }); 
    $(".approve_pay_button").click(function(){
        $("#approve_pay_modal").modal("show");
    });

    $('.send_payment_button').click(function(){ 
        var flag  = 1;  
        var validate_string = ""; 
        validate_string 	= ".form-control.edit";
        var obj             = $("#send_payment_form"); 
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
        //Check Max amount
        if(flag){
           if( parseFloat($("#approve_pay_modal input[name = 'release_amount']").attr('data-max')) <  parseFloat($("#approve_pay_modal input[name = 'release_amount']").val())){
                addErrorForm( $("#approve_pay_modal input[name = 'release_amount']") , "The amount cannot be grater than escrow amount");
                flag = 0;
            }
            else{
                addErrorForm($("#approve_pay_modal input[name = 'release_amount']"));
            }
        } 
        if(flag){ 
            $.ajax({
                url:  "{{route('employer.contract_payment', $offer->serial)}}",
                type: 'POST',
                dataType: 'json',
                data:  $("#send_payment_form").serialize(),
                beforeSend: function (){
                },
                success: function(json){
                    if(json.status){
                        location.href = "{!! route('employer.contract_details', $offer->serial) !!}";
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