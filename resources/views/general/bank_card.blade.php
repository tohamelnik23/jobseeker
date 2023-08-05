@extends('layouts.app')
@section('title', 'Bank Information')
@section('css')
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class = "col-md-3">
                @include('general.partial.settings_leftsidebar') 
            </div>
            <div class="col-md-9">
                <form id = "bank_form" class="panel-body form-horizontal form-padding"  method="post" action="{{{ route('settings.bankcards') }}}">
                    @csrf
                    <div class="card mar-btm">
                        <div class="card-header"> 
                            Bank Information 
                            @if(isset($bank_information) && ($bank_information->verification_status == 3))
                                <small>  
                                    <img class = "checkbox-img" src = "{!! asset('img/checkbox.png') !!}" />
                                </small> 
                            @elseif(isset($bank_information) && ($bank_information->verification_status == 1))
                                (Confirming)
                            @endif
                            
                            @if(isset($bank_information) && ($bank_information->verification_status == 0))
                                <a href="javascript:void(0)" title="Edit Bank Information" type="button" class="close pull-right edit_bank_information">
                                    <i class="fa fa-pencil pt-17"></i>						 
                                </a>
                            @endif

                        </div>
                        <div class="card-body">
                            @if(isset($bank_information))
                                <div class = "bank_display_stuff "> 
                                    <div class="form-group clearfix">
                                        <label class="col-md-12 control-label text-dark text-left"> <strong> Bank Name </strong> </label>
                                        <div class="col-md-6"> 
                                            {!! $bank_information->bank_name !!}                                            
                                        </div>
                                    </div>  
                                    <div class="form-group clearfix">
                                        <label class="col-md-12 control-label text-dark text-left"> <strong> Routing Number </strong> </label>
                                        <div class="col-md-6"> 
                                            {!! $bank_information->routing_number !!}
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-md-12 control-label text-dark text-left"> <strong> Account Number </strong> </label>
                                        <div class="col-md-6"> 
                                             {!! $bank_information->account_number !!}
                                        </div>
                                    </div> 
                                </div>
                            @else
                                <div class = "bank_display_stuff ">
                                    <p class = "text-dark pt-15  text-center"> <em> You have no bank information yet. Please add it. </em> </p>
                                    <div class = "form-group mar-top clearfix">
                                        <div class="col-md-12 text-center">
                                            <button class = "btn btn-mint edit_bank_information"  type = "button"> Add Bank Information </button>
                                        </div>
                                    </div>
                                </div>
                            @endif 

                            @if(!isset($bank_information) || ($bank_information->verification_status == 0))
                            <div class = "bank_edit_stuff hidden">
                                <div class="form-group clearfix">
                                    <label class="col-md-12 control-label text-left"> <strong> Bank Name </strong> </label>
                                    <div class="col-md-6"> 
                                        <input  name = "bank_name" maxlength = 512 class="form-control edit"  @if(isset($bank_information)) value = "{!! $bank_information->bank_name !!}" @endif />
                                        <span class="help-block">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <div class = "col-md-12 text-center">
                                        <img src = "{!! asset('img/card/account_card.gif') !!}" style = "max-width: 100%;" />
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="col-md-12 control-label text-left"> <strong> Routing Number </strong> </label>
                                    <div class="col-md-6"> 
                                        <input  name = "routing_number" maxlength = 20 class="form-control edit integer-input"  @if(isset($bank_information)) value = "{!! $bank_information->routing_number !!}" @endif />
                                        <span class="help-block">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>  
                                <div class="form-group clearfix">
                                    <label class="col-md-12 control-label text-left"> <strong> Account Number </strong> </label>
                                    <div class="col-md-6"> 
                                        <input  name = "account_number" maxlength = 20 class="form-control edit integer-input"  @if(isset($bank_information)) value = "{!! $bank_information->account_number !!}" @endif />
                                        <span class="help-block">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>  
                                <input type = "hidden" name = "request_type" value = "" />
                                <div class = "form-group mar-top clearfix">
                                    <div class="col-md-12 text-center">
                                            <button class = "btn btn-mint send_update_button"  data-type = "edit"    type = "button">  @if(isset($bank_information)) Update @else Add @endif Bank </button>
                                        @if(isset($bank_information))
                                            <button class = "btn btn-mint send_update_button"  data-type = "verify"  type = "button"> Update & Verify </button>
                                        @else
                                            <button class = "btn btn-mint send_update_button"  data-type = "verify"  type = "button"> Add & Verify </button>
                                        @endif
                                    </div>
                                </div>
                            </div> 
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
<script>
    @if(!isset($bank_information) || ($bank_information->verification_status == 0))
        $(".edit_bank_information").click(function(){
            $(".bank_display_stuff").addClass("hidden");
            $(".bank_edit_stuff").removeClass("hidden");
        });
        function validateBankFormPage(obj){
            var flag  = 1;  
            var validate_string =  ".form-control.edit";        
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
            return flag;
        }
        $(".send_update_button").click(function(){
            $("input[ name = 'request_type']").val( $(this).attr('data-type') );
            var flag =  validateBankFormPage($("#bank_form"));
            if(flag){
                $("#bank_form").submit();
            }
        });
    @endif
</script>
@stop