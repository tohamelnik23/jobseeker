<div class="modal fade" id = "addpayment_modal" tabindex="-1" role="dialog" aria-labelledby="DeclineActionModal" aria-hidden="true">
  	<div class="modal-dialog modal-md">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addpayment_modal"> Add a billing method </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">  
                <div class = "pad-all">  
                    <form id = "addpaymentForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class = "row">
                            <div class = "col-sm-12">
                                <p class = "text-dark pt-14">
                                    Discover Gig Payment Protection: only pay for work you authorize
                                </p>
                            </div> 
                        </div>  
                        <div class="form-group clearfix">
                            <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Card Number </label>   
                            <div class="col-sm-12"> 
                                <input type = "text" name = "card_number" class = "form-control bordered edit integer-input" maxlength = "16">
                                <span class="help-block text-left"> 
                                    <strong></strong>
                                </span>
                            </div>
                        </div> 
                        <div class = "row">
                            <div class = "col-sm-6"> 
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> First Name </label>   
                                    <div class="col-sm-12"> 
                                        <input type = "text" name = "first_name" class = "form-control bordered edit" maxlength = "256">
                                        <span class="help-block text-left"> 
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div> 
                            </div>
                            <div class = "col-sm-6">
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Last Name </label>   
                                    <div class="col-sm-12"> 
                                        <input type = "text" name = "last_name" class = "form-control bordered edit"  maxlength = "256">
                                        <span class="help-block text-left"> 
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-sm-6"> 
                                <div class="form-group clearfix"> 
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Expires on</label>   
                                    <div class = "col-sm-12">
                                        <div class = "row"> 
                                            <div class = "col-sm-6">
                                                <input type = "text" name = "mm" maxlength = "2" class = "form-control bordered edit" placeholder = "MM">
                                                <span class="help-block text-left"> 
                                                    <strong></strong>
                                                </span>
                                            </div>
                                            <div class = "col-sm-6">
                                                <input type = "text" name = "yy" maxlength = "2" class = "form-control bordered edit" placeholder = "YY">
                                                <span class="help-block text-left"> 
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-sm-6">
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Security Code </label>   
                                    <div class="col-sm-12"> 
                                        <input type = "text" name = "security_code" class = "form-control edit bordered" maxlength = "4">
                                        <span class="help-block text-left"> 
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $user = Auth::user();
                        @endphp

                        @if($user->accounts->caddress == NULL && $user->accounts->city == NULL && $user->accounts->state == NULL && $user->accounts->zip == NULL && $user->accounts->oaddress == NULL)
                            
                        @else
                            <div class = "row">
                                <div class="col-sm-12">  
                                    <div class="checkbox">
                                        <input id   =   "payment_modal-checkbox" name = "use_another_billing_address" class = "magic-checkbox" type="checkbox" >
                                        <label for  =   "payment_modal-checkbox" class = "text-dark"> Use another Billing Address </label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class = "payment_billing_address   @if($user->accounts->caddress == NULL && $user->accounts->city == NULL && $user->accounts->state == NULL && $user->accounts->zip == NULL && $user->accounts->oaddress == NULL)  @else hidden  @endif">
                            <div class = "row">
                                <div class="col-sm-12">
                                    <div class="form-group clearfix">
                                        <label class="col-lg-12 control-label  text-dark text-left"> <star>*</star> Address1</label>
                                        <div class="col-lg-12">
                                            <input type="text" maxlength = 512 minlength = 5 data-content = "The length of address should be at least 5 characters"   placeholder="123 Moorhead Manor" name="address1"  class="form-control form-control-edit edit"  value = ""> 
                                            <span class="help-block">             
                                                <strong></strong>  
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-lg-12 control-label  text-dark  text-left">  Address2 </label>
                                        <div class="col-lg-12">
                                            <input type = "text" maxlength = 512 name = "address2" class = "form-control"   value = "">
                                            <span class = "help-block">
                                                <strong></strong>  
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-lg-12 control-label text-dark  text-left"> <star>*</star>City</label>
                                        <div class="col-lg-12">
                                            <input type="text" maxlength = 512 minlength = 2 data-content = "The length of city should be at least 2 characters"  placeholder = "Naples" name = "city" class="form-control edit form-control-edit"  value = "">
                                            <span class="help-block">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-sm-12 control-label text-dark  text-left" for="state"><star>*</star> State</label>
                                        <div class="col-sm-12">
                                            @php
                                                $states = DB::table("states")->get();
                                            @endphp
                                            <select class = "form-control edit" name="state">
                                                @foreach($states as $state)
                                                    <option value = "{{ $state->abbreviation }}"> {{ $state->state }} </option>
                                                @endforeach
                                            </select>
                                            <span class="help-block">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-lg-12 control-label text-dark   text-left"> <star>*</star> Zip</label>
                                        <div class="col-lg-6">
                                            <input type="text" maxlength = 20 data-content = "Please input the zip code"  placeholder = "34212" name = "zip" class = "form-control edit form-control-edit"  value = "">
                                            <span class="help-block">
                                                <strong></strong>
                                            </span> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="col-sm-12 mar-top action_btn_group text-right"> 
                                <button type="button"  class="btn btn-mint dis-title-button verify-payment-modal"> Verify </button> 
                            </div>
                        </div> 
                    </form>
                </div>
			</div>
		</div>
  	</div>
</div>
@push('partialscripts')
<script> 
    function validate_card_form(){
        var flag = 1; 
        $("#addpaymentForm").find(".form-control.edit").each(function(){
            if($.trim($(this).val()) ===  ""){
                flag = 0;  
                addErrorItem($(this), "This field is required."); 
            }
            else{
                addErrorForm( $(this) );
            }
        });  
        return flag;
    }
    $(".add-billing-button").click(function(){
        $('#addpaymentForm')[0].reset(); 
        $("#addpaymentForm").find(".form-control.edit").each(function(){
            addErrorForm( $(this) );
        });
        // default first name and last name
        $("#addpaymentForm input[name = 'first_name']").val("{!! Auth::user()->accounts->firstname !!}");
        $("#addpaymentForm input[name = 'last_name']").val("{!!  Auth::user()->accounts->lastname  !!}");

        show_payment_billing_address();
        $("#addpayment_modal").modal("show");
    });  
    $(".verify-payment-modal").click(function(){
        var flag    =   validate_card_form();
        if(flag){
            $.ajax({
                url:   "{!! route('employer.profile.settings.addcard') !!}", 
                type: 'POST',
                data:  $("#addpaymentForm").serialize(),
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
        }
    });

    function show_payment_billing_address(){ 
        if($("#payment_modal-checkbox").length){
            if($("#payment_modal-checkbox").prop('checked')){
                $(".payment_billing_address").removeClass("hidden");
                $(".form-control-edit").addClass("edit");
            }
            else{
                $(".payment_billing_address").addClass("hidden");
                $(".form-control-edit").removeClass("edit");
            }    
        }
    }

    $("#payment_modal-checkbox").click(function(){
        show_payment_billing_address();
    });
</script>
@endpush