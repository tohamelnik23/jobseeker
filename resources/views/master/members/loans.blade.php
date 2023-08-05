@extends('layouts.master')
@section('title','Employee Profile | ' . $user->accounts->name)
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/switchery/switchery.min.css') }}">
<style> 
    .switchery{
        width: 50px;
    }
</style>
@endpush
@section('content') 
    <div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Gig  Doers Management </h1>
		</div>
		<ol class="breadcrumb">
			<li><a href="{{route('master.dashboard')}}"><i class="demo-pli-home"></i></a></li>
			<li><a href="{{route('master.members.employees')}}"> Gig  Doers  Management </a></li>  
			<li class="active"> Gig  Doer Loans </li>
		</ol>
	</div>
    <div id="page-content">
        @if(Session::has('success'))
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
				{!! session('success') !!}
			</div>
		@endif
        <div class = "row">
            <div class="col-sm-10 col-sm-offset-1"> 
                <div class="row">
                    <div class = "col-sm-3"> 
						<div class="panel pos-rel">
							<div class="pad-all text-center"> 
								<a href="#">
									<img alt="Profile Picture" class="img-lg img-circle mar-ver" src="{{$user->getImage()}}">
									<p class="text-lg text-semibold mar-no text-main">{{$user->accounts->name}}</p> 
									<p class=" mar-top-5">{{$user->email}}</p> 
									<p class=" mar-top-5">{{ Mainhelper::formatNumber($user->cphonenumber) }}</p> 
									<p class=" mar-top-5">{{$user->accounts->headline}}</p>                 
								</a>  
							</div>
						</div>
					</div> 
                    <div class="col-md-9"> 
                        <div class="panel freelancer_card_panel" style = "background-color: inherit;">  
							<div class="panel-heading bord-btm">
								<div class="panel-control pull-left">
									<ul class="nav nav-tabs">
										<li class = " ">
											<a href = "{!!  route('master.members.show', $user->id) !!}"   class = "text-uppercase"> Verification  </a>
										</li>
										<li class = "active">
											<a  href = "#"  class = "text-uppercase"> Advances </a>
										</li> 
									</ul>
								</div>
							</div> 
							<div class="panel-body pad-no"> 
                                @if(isset($borrowing_change_request))
                                <div class="form-group clearfix mar-top">
                                    <div class = "row"> 
                                        <label class="col-sm-offset-3 col-sm-9 control-label text-danger text-bold">  
                                           <i> {{ $user->accounts->name }} asked the request to ${!! number_format($borrowing_change_request->amount, 2)  !!} </i>
                                        </label>
                                    </div>
                                </div> 
                                @endif
                                <div class="form-group clearfix mar-top">
                                    <div class = "row"> 
                                        <label class="col-sm-3 control-label text-right"> <strong> Advances Enabled:</strong></label>
                                        <div class="col-sm-6">
                                            <input class = "sw-checkstate"   id="sw-checkstate-loan"  type="checkbox" @if($user->accounts->loan_enable) checked @endif>
                                        </div>
                                    </div>
                                </div> 
                                <div class = "loan_setting hidden">
                                    <form id = "loans_form" class="form-horizontal" method = "post" action = ""> 
                                        <div class="form-groupn clearfix mar-top">
                                            <div class = "row">
                                                <label class="col-sm-3 control-label" for="demo-is-inputsmall"> Factor Rate ( <span class = "text-bold" id = "loan_fee_value"></span> )</label>
                                                <div class="col-sm-6">
                                                    <input name = "loan_fee" type = "range" class="form-control-range" id = "loan_fee_range" value="{!! $user->accounts->loan_fee !!}" min="0" max="100" step="1">   
                                                </div>
                                            </div>
                                            <div class = "row"> 
                                                <div class = "col-sm-6 col-sm-offset-3">
                                                    <span class = "pull-left mr-2"  >0</span>
                                                    <span  class ="pull-right">100</span>
                                                </div> 
                                            </div>
                                        </div>  
                                        <div class="form-groupn clearfix mar-top">
                                            <div class = "row">
                                                <label class="col-sm-3 control-label" for="demo-is-inputsmall">Advance Limit ($<span class = "text-bold" id = "loan_amount_value"></span> )</label>
                                                <div class="col-sm-9">
                                                    <input name="loan_amount" type="range" class="form-control-range" id = "loan_amount_range" value="{!! $user->accounts->loan_amount !!}" min="0" max="5000" step="100">   
                                                </div>
                                            </div>
                                            <div class = "row"> 
                                                <div class = "col-sm-9 col-sm-offset-3">
                                                    <span class = "pull-left mr-2"  >0</span>
                                                    <span  class ="pull-right">5000</span>
                                                </div> 
                                            </div>
                                        </div>  
                                        <div class="form-groupn clearfix mar-top">
                                            <label class="col-sm-3 control-label"> Current Advances </label>
                                            <div class="col-sm-3">
                                                <p class = "form-control"> ${!! number_format($user->getLoanValue('total_loans_finished'), 2)  !!} </p>
                                            </div>
                                        </div>
                                    </form> 
                                </div>

                                @if(count($loan_histories))
                                <div class="form-groupn clearfix mar-top"> 
                                    <div class = "col-md-12">
                                        <h4 class = "text-center text-bold"> Advances Requests </h4> 
                                        <div class="table-responsive"> 
                                            <table class="table table-striped table-hover table-vcenter">
                                                <thead>
                                                    <tr class="bg-teal"> 
                                                        <th>Date</th>
                                                        <th>Offer</th> 
                                                        <th>Minimum Budget</th>
                                                        <th>Requested Amount</th>
                                                        <th class = "text-center"> Status </th>
                                                        <th class = "text-center">Action</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($loan_histories as $loan_history)
                                                    <tr>
                                                        <td> {!! date('M d, Y', strtotime($loan_history->updated_at))  !!} </td>
                                                        @php
                                                            $offer = $loan_history->getOffer();
                                                        @endphp
                                                        <td> {!!  $offer->contract_title !!} </td> 
                                                        <td>${!! number_format($offer->EstimatedBudget(), 2)  !!}</td>
                                                        <td class = "text-bold   @if($offer->status == "pending") text-danger  @endif">${!! number_format($loan_history->amount, 2)  !!}</td>
                                                        <td class = "text-center text-capitalize">
                                                            {!! $loan_history->status !!}
                                                        </td>
                                                        <td class = "text-center">   
                                                            @if($offer->status == "pending")
                                                                <a href = "javascript:void(0)" data-serial = "{!! $loan_history->serial !!}" class="btn btn-success margin-5 loan_approve">Approve</a>
                                                                <a href = "javascript:void(0)" data-serial = "{!! $loan_history->serial !!}" class="btn btn-danger margin-5 loan_reject">Reject</a>
                                                            @endif

                                                        </td>
                                                    </tr> 
                                                    @endforeach
                                                </tbody>
                                            </table> 
                                        </div> 
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
@push('javascript')
<script src="{{asset('plugins/switchery/switchery.min.js') }}"     type="text/javascript"></script>
<script>
    function updateLoanFee(){ 
        $.ajax({
            type: "POST",
            url:  "{!! route('master.members.loans', $user->id) !!}",
            data: $("#loans_form").serialize(),
            success: function(json) { 
                if(json.status == 1){
                    
                }
            } 
        }); 
    } 

    function DispalyLoanFee(value){
        value = 1 + value / 100;
        return value.toFixed(2);
    }

    var slider_loanfee = document.getElementById("loan_fee_range");
    var loan_fee_value = document.getElementById("loan_fee_value"); 
    if(slider_loanfee){
        loan_fee_value.innerHTML = DispalyLoanFee(slider_loanfee.value );
        slider_loanfee.oninput = function() {
            loan_fee_value.innerHTML =  DispalyLoanFee (this.value );
            updateLoanFee();
        }
    }  
    var slider_loanamount = document.getElementById("loan_amount_range");
    var loan_amount_value = document.getElementById("loan_amount_value"); 
    if(slider_loanamount){
        loan_amount_value.innerHTML = slider_loanamount.value; 
        slider_loanamount.oninput = function() {
            loan_amount_value.innerHTML = this.value;
            updateLoanFee();
        }
    }
    /*********************************** Verify Loans *************************/
    $(".loan_approve").click(function(){
        var loan_request = $(this).attr('data-serial'); 
        swal({
            title: "Are you sure?",
            text: "You want to approve advance request?", 
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger",
            confirmButtonText: "Yes, approve it!",
            cancelButtonClass: "btn btn-secondary"
        }).then(t=>{
            t.value &&  $.ajax({
                url: "{{route('master.members.loans.action', $user->id)}}",
                type: "post",
                data: {
                    loan_request:  loan_request,
                    request_type: 'approve',
                    "_token"    : "{{ csrf_token() }}",
                },
                success: function () {
                    $(".personal_info_status").html('<span class="badge badge-dot mr-2"><i class="bg-success"></i><span class="status">Approved</span></span><a href="#!" class="table-action table-action-default" data-toggle="tooltip" data-original-title="Congratulations! approved." data-placement="right"><i class="fas fa-info-circle"></i></a>');
                    swal({
                        title: "Approved!", 
                        text: "The advance request is approved successfully.",
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-primary"
                    }).then(function(isConfirm) {
                        if(isConfirm){
                            location.reload();
                        } 
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    swal("Error approving!", "Please try again", "error");
                }
            });
        }); 
    }); 
    $(".loan_reject").click(function(){ 
        var loan_request = $(this).attr('data-serial'); 
        swal({
            title: "Are you sure?",
            text: "You want to reject advance request!", 
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger",
            confirmButtonText: "Yes, reject it!",
            cancelButtonClass: "btn btn-secondary"
        }).then(t=>{
            t.value &&  $.ajax({
                url: "{{route('master.members.loans.action', $user->id)}}",
                type: "post",
                data: {
                    loan_request:  loan_request,
                    request_type:  'reject',
                },
                success: function () {
                    $(".personal_info_status").html('<span class="badge badge-dot mr-2"><i class="bg-success"></i><span class="status">Approved</span></span><a href="#!" class="table-action table-action-default" data-toggle="tooltip" data-original-title="Congratulations! approved." data-placement="right"><i class="fas fa-info-circle"></i></a>');
                    swal({
                        title: "Rejected!", 
                        text: "The advance request is rejected successfully.",
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-primary"
                    }).then(function(isConfirm) {
                        if(isConfirm){
                            location.reload();
                        }
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    swal("Error rejecting!", "Please try again", "error");
                }
            });
        });  
    });
    /*************************************************************************/ 
    function switchery_show(){
        var visible = 0;
        if($("#sw-checkstate-loan").prop('checked')){
            visible = 1;
            $(".loan_setting").removeClass("hidden");
        }
        else{
            visible = 0;
            $(".loan_setting").addClass("hidden");
        }
        $.ajax({
            type: "POST",
            url:  "{!! route('master.members.loans_setting', $user->id) !!}",
            data:  { loan_visible : visible},
            success: function(json) {
                if(json.status == 1){
                    
                } 
            } 
        });
    }

    var changeCheckbox_loan =   document.getElementById('sw-checkstate-loan');
    new Switchery(changeCheckbox_loan);
    changeCheckbox_loan.onchange = function() {
        switchery_show();
    }; 
    switchery_show();
</script>
@endpush