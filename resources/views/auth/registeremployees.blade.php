@extends('layouts.app')
@section('title', 'Register | Gig Doer')
@section('css')
<style>
#skills{
	display:block !important;
	visibility: hidden;
	height: 0px !important;
    padding: 0px !important;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
					<h4 class = "text-dark"> Gig  Doer  </h4>
				</div> 
                <div class="card-body">
                    <form id="signupForm" method="post" class="form-horizontal" action="{{{ route('employees.create') }}}">
						@csrf 
						<div class="row">
							<div class="form-group col-lg-12">
								<p class="col-sm-12 text-bold text-dark  text-left"> 
									Register Below to Search our Extensive Database of Workers:
								</p>
							</div>

							<div class="form-group col-lg-6 @if ($errors->has('firstname'))   has-error @endif">
								<label class="col-sm-12 control-label text-left" for="firstname"><star>*</star> First name</label>
								<div class="col-sm-12  @if ($errors->has('firstname')) has-error @endif">
									<input type="text" class="form-control" id="firstname" name="firstname" placeholder = "" value = "{!! old('firstname') !!}"  />
									@if ($errors->has('firstname'))
										<em id="firstname-error" class="error help-block">
											{{ $errors->first('firstname') }}
										</em>
									@endif
								</div>
							</div>
							<div class="form-group col-lg-6 @if ($errors->has('lastname'))   has-error @endif">
								<label class="col-sm-12 control-label text-left" for="lastname"><star>*</star>Last name</label>
								<div class="col-sm-12  @if ($errors->has('lastname')) has-error @endif">
									<input type="text" class="form-control" id="lastname" name="lastname" placeholder = ""  value = "{!! old('lastname') !!}"  />
									@if ($errors->has('lastname'))
										<em id="lastname-error" class="error help-block">
											{{ $errors->first('lastname') }}
										</em>
									@endif
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-lg-12 @if ($errors->has('email'))   has-error @endif">
								<label class="col-sm-12 control-label text-left" for="email"><star>*</star>Email</label>
								<div class="col-sm-12 @if($errors->has('email')) has-error @endif">
									<input type="text" class="form-control" id="email" name="email" placeholder = ""  value = "{!! old('email') !!}" />
									@if ($errors->has('email'))
										<em id="email-error" class="error help-block">
											{{ $errors->first('email') }}
										</em>
									@endif
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-lg-12 @if ($errors->has('cphonenumber'))   has-error @endif">
								<label class="col-sm-12 control-label text-left" for="cphonenumber"><star>*</star>Mobile Number</label>
								<div class="col-sm-12  @if ($errors->has('cphonenumber')) has-error @endif">
									<input type="text" class="form-control" id="cphonenumber" name="cphonenumber" placeholder = ""  value = "{!! old('cphonenumber') !!}" />
									@if ($errors->has('cphonenumber'))
										<em id="cphonenumber-error" class="error help-block">
											{{ $errors->first('cphonenumber') }}
										</em>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-12">
								<div class="col-sm-12"> 
									<div class="checkbox">
										<input id="agree-checkbox" class="magic-checkbox" name = "agree" value = "agree" type="checkbox">
										<label for="agree-checkbox"> Agree to our Terms of Service </label> 
									</div> 
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-12">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-primary col-lg-12" name="signup" value="Sign up">Sign up</button>
								</div>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/typeahead.bundle.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.js?'.time())}}"></script>
<script>
$( document ).ready( function () { 
	$("#cphonenumber").mask('(999) 999-9999'); 
	$( "#signupForm" ).validate({
		rules: {
			firstname: "required",
			lastname: "required",
			cphonenumber: "required",
			email: {
				required: true,
				email: true
			},
			desired_rate: {
				number: true,
				required: true
			}, 
			agree: "required"
		},
		messages:{
			firstname: "Please enter your First name",
			lastname: "Please enter your Last name",
			cphonenumber: "Please enter your Phone number",
			desired_rate: "Please enter your desired rate",
			email: "Please enter a valid email address",
			agree: "Please accept our policy"
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else {
				error.insertAfter( element );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
		},submitHandler: function(form) {
			form.submit();
		}
	});
});
</script>
@stop