@extends('layouts.app')
@section('title', 'Borrowing Power')
@section('css')
@endsection
@section('content')
@php
    $bankinformation = Auth::user()->getBankInformation();
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-offse-2 col-md-8"> 
            <div class="card mar-btm">
                <div class="card-header bg-trans"> 
                    <h4 class = "text-dark"> Advance Power Request </h4>
                </div>
                <div class="card-body">
                    <div class="bank_display_stuff "> 
                        <div class = "pad-all">
                            @if(isset($bankinformation))
                                <div class="form-group row clearfix mar-btm">
                                    <label class="col-md-12 h5 control-label text-dark text-left"> <strong> Factor Rate  </strong> </label>
                                    <div class="col-md-6 text-dark pt-14"> 
                                        {!! Auth::user()->accounts->loan_fee  !!}% 
                                    </div>
                                </div>  
                                <div class="form-group row clearfix mar-btm">
                                    <label class="col-md-12 h5  control-label text-dark text-left"> <strong> Advance Limit  </strong> </label>
                                    <div class="col-md-6 text-dark pt-14"> 
                                        ${!! number_format(Auth::user()->accounts->loan_amount, 2)  !!}
                                    </div>
                                </div> 
                                <div class="form-group row clearfix mar-btm">
                                    <label class="col-md-12 h5  control-label text-dark text-left"> <strong> Current Advances (Include pending) </strong> </label>
                                    <div class="col-md-6 text-dark "> 
                                        ${!! number_format(Auth::user()->getLoanValue('total_loans_pending'), 2)  !!}
                                    </div>
                                </div>  
                                @php
                                    $lending_power = Auth::user()->accounts->loan_amount; 
                                @endphp  
                                <div class="form-group row clearfix mar-btm">
                                    <label class="col-md-12  h5 control-label text-dark text-left"> <strong> Balance </strong> </label>
                                    <div class="col-md-6 text-dark pt-14"> 
                                        ${!! number_format($lending_power - Auth::user()->getLoanValue('total_owed_pending'), 2)  !!}
                                    </div>
                                </div>

                                @if(isset($borrowing_change_request))
                                <div class="form-group row clearfix mar-btm">
                                    <label class="col-md-12 mar-no  h5 control-label text-dark text-left"> <strong> Past Change Request </strong> </label>
                                    <p class = "col-md-12 text-dark"><em>You have sent the request {!! $borrowing_change_request->created_at->diffForHumans() !!}</em></p>
                                    <div class="col-md-6 text-dark pt-14"> 
                                        ${!! number_format(  $borrowing_change_request->amount, 2)  !!}
                                    </div>
                                </div>
                                @endif
                                 
                                <div class="form-groupn clearfix mar-top">
                                    <div class = "row">
                                        <label class="col-sm-12  h5  text-dark control-label" for="demo-is-inputsmall">
                                            <strong> 
                                            Advance Limit  ($<span class = "text-bold" id = "loan_amount_value"></span>)
                                            </strong> 
                                        </label>
                                        <div class="col-sm-9">
                                            <input name="loan_amount" type="range" class="form-control-range" id = "loan_amount_range" value = "{!! $lending_power !!}" min = "0" max = "5000" step="100">   
                                        </div>
                                    </div>
                                    <div class = "row"> 
                                        <div class = "col-sm-9">
                                            <span class = "pull-left mr-2 text-dark">0</span>
                                            <span  class ="pull-right text-dark">{!! number_format(5000 , 2)  !!}</span>
                                        </div> 
                                    </div>
                                </div>
                                <div class = "form-group mar-top clearfix">
                                    <div class="col-md-12 text-center">
                                        <button class = "btn btn-mint send_request" data-past_value = "{!!  Auth::user()->accounts->loan_amount !!}"  data-type = "edit"    type = "button" disabled>  Send Request </button> 
                                    </div>
                                </div> 
                            @else
                                <div class = "form-group clearfix">
                                    <div class = "col-xs-12">
                                        You have to verify your bank information to DiscoverGig first
                                    </div>
                                    <div class = "col-xs-12 mar-top text-center">
                                        <a href = "{!! route('settings.bankcards') !!}" class = "btn btn-mint">Verify Bank Information</a>
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
@endsection
@section('javascript')
    @if(isset($bankinformation) &&  $lending_power)
        <script>
            function updateLoanFee(value){
               var past_value       = parseInt($(".send_request").attr('data-past_value'));
               var current_value    = parseInt(value); 
               if(past_value < current_value){
                    $(".send_request").prop('disabled', false);
               }
               else{
                    $(".send_request").prop('disabled', true);
               } 
            }
            var slider_loanamount = document.getElementById("loan_amount_range");
            var loan_amount_value = document.getElementById("loan_amount_value"); 
            if(slider_loanamount){
                loan_amount_value.innerHTML = slider_loanamount.value; 
                slider_loanamount.oninput = function() {
                    loan_amount_value.innerHTML = this.value;
                    updateLoanFee(this.value);
                }
            }
            
            $(".send_request").click(function(){ 
                $.ajax({
                    url:   "{!! route('loans.request') !!}", 
                    type: 'POST',
                    data:  { 'request_amount' : $("#loan_amount_range").val() } ,
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
    @endif
@stop