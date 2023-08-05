@extends('layouts.app')
@section('title', 'Phone Verification')
@section('css')
<style>
    #wrapper_verification {
        font-family: Lato;
        font-size: 1.5rem;
        text-align: center;
        box-sizing: border-box;
        color: #333;
    }  
    #wrapper_verification span {
        font-size: 90%;
    } 
    #wrapper_verification input {
        margin: 0 5px;
        text-align: center;
        line-height: 60px;
        font-size: 40px;
        border: solid 1px #ccc;
        box-shadow: 0 0 5px #ccc inset;
        outline: none;
        width: 50px;
        transition: all .2s ease-in-out;
        border-radius: 3px;
    }
    #wrapper_verification input:focus {
        border-color: purple;
        box-shadow: 0 0 5px purple inset;
    }
    #wrapper_verification input::selection {
        background: transparent;
    } 
    #wrapper_verification div {
        position: relative;
        z-index: 1;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-offse-2 col-md-8">
            <div class="card mar-btm">
                <div class="card-header bg-trans"> 
                    <h4 class = "text-dark"> Phone Verification </h4>
                </div>
                <div class="card-body">
                    <div class = "pad-all">
                        <div class = "first_verification ">
                            <div class="form-group clearfix"> 
                                <p class = "pt-14 col-sm-12 clearfix  text-dark"> 
                                    In order to protect the security of your account, please verify your mobile phone. A text message is being sent with a verification code to the phone number ending with ****{!!  substr( Auth::user()->cphonenumber , -4); !!}.
                                </p>
                            </div>
                            <div class="form-group clearfix mar-top"> 
                                <div class="col-sm-offset-2 col-sm-8">
                                    <button type = "button" class="btn btn-block btn-mint send_sms_code">Send SMS Code</button>
                                </div>
                            </div>
                        </div>
                        <div class = "second_verification hidden">
                            <div class="form-group clearfix"> 
                                <p class = "pt-14 col-sm-12 clearfix text-dark">
                                    We have sent the verification code to your phone number ending with ***{!!  substr(Auth::user()->cphonenumber , -4) !!}. If you did not receive the message, plese click here to <a href = "#" class = "text-primary btn-link text-bold send_sms_code"> resend </a>.
                                </p>
                            </div>
                            <div class="form-group clearfix mar-top">
                                <div class="col-sm-12"> 
                                    <div id="wrapper_verification">
                                        <input class = " verification_code" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                                        <input class = " verification_code" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                                        <input class = " verification_code" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                                        <input class = " verification_code" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" /> 
                                    </div> 
                                </div>
                                <div class="col-sm-12 text-center phone_error_message hidden has-error"> 
                                    <span class="help-block"> 
                                        <strong>This code is invalid</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group clearfix mar-top"> 
                                <div class="col-sm-offset-2 col-sm-8">
                                    <button type = "button" class="btn btn-block btn-mint verification_button" disabled>Verify</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
    function EnableVerifyButton(){
        var flag = 0;
        $(".verification_code").each(function(){
            if( $.trim($(this).val()) == ""){
                flag = 1;
            }
        });
        if(flag){
            $(".verification_button").prop("disabled", true);
        }
        else{
            $(".verification_button").prop("disabled", false);
        }
    } 
    $(".send_sms_code").click(function(){
        $.ajax({
            url:   "{!! route('request_phone_verification') !!}",
            type: 'POST', 
            dataType: 'json',
            beforeSend: function (){
            },
            success: function(json){
                if(json.status){
                    $(".first_verification").addClass("hidden");
                    $(".second_verification").removeClass("hidden"); 
                }
                else{
                    if(json.redirect){
                        location.reload();
                    }
                    else{
                        swal({
                            title: "Error Occured",   
                            text:   json.msg, 
                            type: "error",   
                            confirmButtonText: "Close" 
                            }).then(function(isConfirm) {
                            if(isConfirm){ 
                            }
                        });
                    } 
                }
            },
            complete: function () {
            },
            error: function() {
            }
        });
    });

    $(".verification_button").click(function(){
        var code = "";
        $(".verification_code").each(function(){
            code += $(this).val();
        });
        $.ajax({
            url:   "{!! route('verify_phone_verification') !!}",
            type: 'POST', 
            dataType: 'json',
            data: { code: code },
            beforeSend: function (){
            },
            success: function(json){
                if(json.status){
                    location.reload();
                }
                else{
                    if(json.redirect){
                        location.reload();
                    }
                    else{
                        $(".phone_error_message").removeClass("hidden");
                    } 
                }
            },
            complete: function () {
            },
            error: function() {
            }
        });

    });
    
    $(function() {
        'use strict';
        var body = $('body'); 
        function goToNextInput(e) {
            var key = e.which,
            t = $(e.target),
            sib = t.next('input');
            if (key != 9 && (key < 48 || key > 57)) { 
                e.preventDefault();
                return false;
            } 
            if (key === 9) {
                return true;
            }
            if (!sib || !sib.length) {
            sib = body.find('input').eq(0);
            }
            sib.select().focus(); 
            EnableVerifyButton();
        } 
        function onKeyDown(e) {
            var key = e.which; 
            if (key === 9 || (key >= 48 && key <= 57)) { 
                return true;
            } 
            e.preventDefault();
            return false;
        }
        function onFocus(e) {
            $(e.target).select();
        }
        body.on('keyup', 'input', goToNextInput);
        body.on('keydown', 'input', onKeyDown);
        body.on('click', 'input', onFocus);
    });
</script>
@stop