@extends('layouts.master')
@section('title','Gig Type Management')
@section('css')
	<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">
	<link rel="stylesheet" href="{{asset('jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}"  type="text/css"/>
@endsection 
@section('content')
    <div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Settings </h1>
		</div>
		<ol class="breadcrumb">
			<li><a href="{{route('master.dashboard')}}"><i class="demo-pli-home"></i></a></li>
		</ol>
	</div> 
    <div id="page-content">
        <div class="col-md-12">
            @include('partial.alert')
        </div>
        <div class = "col-md-10 col-md-offset-1">
            <div class="tab-base">
                <ul class="nav nav-tabs">
                    <li @if(!Session::has('setting_update_type')) class="active" @endif>
                        <a data-toggle="tab" href="#lft-tab-1">General </a>
                    </li>
                    <li @if(Session::has('setting_update_type') && (Session::get('setting_update_type') == 'payment')) class="active" @endif>
                        <a data-toggle="tab" href="#lft-tab-2"> Payment </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="lft-tab-1" class="tab-pane fade @if(!Session::has('setting_update_type'))  active in @endif"> 
                        <div class = "row">
                            <div class="col-md-8 col-md-offset-2">
                                <form class="form-horizontal" method = "post" action = "{!! route('master.settings.update') !!}">
                                    @csrf
                                    <input type="hidden" name="type"  value="global">
                                    <div class="form-group @if ($errors->has('job_poster_fee')) has-error @endif">
                                        <label class="col-md-3 control-label">  Gig Provider Fee (%): <star>*</star> </label> 
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="demo-pli-star"></i></span>
                                                <input placeholder="" maxlength="256"  name = "job_poster_fee" value = "{!! $job_poster_fee !!}" class="form-control decimal-input edit" type="text" required>
                                            </div>
                                            <span class="help-block"> 
                                                <strong>@if ($errors->has('job_poster_fee')){{ $errors->first('job_poster_fee') }}@endif</strong>  
                                            </span>
                                        </div> 
                                    </div> 
                                    <div class="form-group @if ($errors->has('job_taker_fee')) has-error @endif">
                                        <label class="col-md-3 control-label">  Freelancer / Employee Fee (%): <star>*</star> </label> 
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="demo-pli-star"></i></span>
                                                <input placeholder="" maxlength="256"  name = "job_taker_fee" value = "{!! $job_taker_fee !!}" class="form-control decimal-input edit" type="text" required>
                                            </div>
                                            <span class="help-block"> 
                                                <strong>@if ($errors->has('job_taker_fee')){{ $errors->first('job_taker_fee') }}@endif</strong>  
                                            </span>
                                        </div> 
                                    </div> 

                                    <div class="form-group @if ($errors->has('expire_date')) has-error @endif">
                                        <label class="col-md-3 control-label">  Job Expire Date: <star>*</star> </label> 
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="demo-pli-star"></i></span>
                                                <input placeholder="" maxlength="256"  name = "expire_date" value = "{!! $expire_date !!}" class="form-control decimal-input edit" type="text" required>
                                            </div>
                                            <span class="help-block"> 
                                                <strong>@if ($errors->has('expire_date')){{ $errors->first('expire_date') }}@endif</strong>  
                                            </span>
                                        </div> 
                                    </div> 


                                    <div class="form-group @if ($errors->has('expire_warning_date')) has-error @endif">
                                        <label class="col-md-3 control-label">  Job Expire Warning Date ( Before Expire): <star>*</star> </label> 
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="demo-pli-star"></i></span>
                                                <input placeholder="" maxlength="256"  name = "expire_warning_date" value = "{!! $expire_warning_date !!}" class="form-control decimal-input edit" type="text" required>
                                            </div>
                                            <span class="help-block"> 
                                                <strong>@if ($errors->has('expire_warning_date')){{ $errors->first('expire_warning_date') }}@endif</strong>  
                                            </span>
                                        </div> 
                                    </div> 

                                    <div class="form-group @if ($errors->has('expire_offer_date')) has-error @endif">
                                        <label class="col-md-3 control-label">  Offer Expire Date: <star>*</star> </label> 
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="demo-pli-star"></i></span>
                                                <input placeholder="" maxlength="256"  name = "expire_offer_date" value = "{!! $expire_offer_date !!}" class="form-control decimal-input edit" type="text" required>
                                            </div>
                                            <span class="help-block"> 
                                                <strong>@if ($errors->has('expire_offer_date')){{ $errors->first('expire_offer_date') }}@endif</strong>  
                                            </span>
                                        </div> 
                                    </div> 

                                    <div class="form-group @if ($errors->has('expire_interview_date')) has-error @endif">
                                        <label class="col-md-3 control-label">  Interview Expire Date: <star>*</star> </label> 
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="demo-pli-star"></i></span>
                                                <input placeholder="" maxlength="256"  name = "expire_interview_date" value = "{!! $expire_interview_date !!}" class="form-control decimal-input edit" type="text" required>
                                            </div>
                                            <span class="help-block"> 
                                                <strong>@if ($errors->has('expire_interview_date')){{ $errors->first('expire_interview_date') }}@endif</strong>  
                                            </span>
                                        </div> 
                                    </div>


                                    <div class="form-group @if ($errors->has('expire_interview_date')) has-error @endif">
                                        <label class="col-md-3 control-label">  Verify Request Warning Date (Days) : <star>*</star> </label> 
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="demo-pli-star"></i></span>
                                                <input placeholder="" maxlength="256"  name = "verify_request_warning_date" value = "{!! $verify_request_warning_date !!}" class="form-control decimal-input edit" type="text" required>
                                            </div>
                                            <span class="help-block"> 
                                                <strong>@if ($errors->has('expire_interview_date')){{ $errors->first('expire_interview_date') }}@endif</strong>  
                                            </span>
                                        </div> 
                                    </div> 




                                    <div class="form-group">
                                        <div class="col-md-12 text-center"> 
                                            <button type = "submit" class="btn btn-mint submit-button">Save</button> 
                                        </div> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="lft-tab-2" class="tab-pane fade @if(Session::has('setting_update_type') && (Session::get('setting_update_type') == 'payment'))  active in @endif">
                        <div class = "row">
                            <div class="col-md-8 col-md-offset-2">
                                <form class="form-horizontal" method = "post" action = "{!! route('master.settings.update') !!}">
                                    <input type="hidden" name="type"  value="payment">
                                    @csrf
                                    <div class="form-group @if ($errors->has('live_transactionkey')) has-error @endif clearfix">
                                        <label class="col-md-3 control-label">  Security Key: <star>*</star> </label> 
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="demo-pli-star"></i></span>
                                                <input placeholder="" maxlength="256"  name = "live_transactionkey" value ="{{$live_transactionkey}}" class="form-control edit" type="text" required>
                                            </div>
                                            <span class="help-block"> 
                                                <strong>@if ($errors->has('live_transactionkey')){{ $errors->first('live_transactionkey') }}@endif</strong>  
                                            </span>
                                        </div> 
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="col-md-12 text-center"> 
                                            <button type = "submit" class="btn btn-mint submit-button">Save</button> 
                                        </div> 
                                    </div> 
                                    <div class="form-group clearfix mar-top">
                                        <div class="col-md-12 text-center">
                                            <span class="h5"> It is important that you set the AVS settings in <a href = "https://secure.networkmerchants.com"  class="btn-link"> NMI </a> to the following: </span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="col-md-12 text-center">
                                            <img style="width: 400px;" src="{!! asset('img/background/networkmerchants.png') !!}"  >
                                        </div>
                                    </div> 
                                    <div class="form-group clearfix mar-top">
                                        <div class="col-md-12 text-center">
                                            <span class="h5"> Please make sure you adjust the Customize API Response Variables to match this picture: </span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="col-md-12 text-center">
                                            <img style="width: 400px;" src="{!! asset('img/background/networkmerchants1.png') !!}"  >
                                        </div>
                                    </div>
                                </form>
                            </div> 
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
<script>
   


</script>
@endpush