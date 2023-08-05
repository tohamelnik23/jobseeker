<div id="miscFormresponce" class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">×</button>	
		<strong></strong>
</div>
<form id="miscForm" method="post" class="form-horizontal" action="{{{ route('employer.update.misc') }}}">
	<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
	<div class="row">
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label  text-left" for="firstname">*First name</label>
			<div class="col-sm-12  @if ($errors->has('firstname')) has-error @endif">
				<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First name" value="{{old('firstname',$user->accounts->firstname)}}" disabled/>
				<em id="firstname-error" class="error help-block hide">
				</em>
			</div>
		</div>
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label  text-left" for="lastname">*Last name</label>
			<div class="col-sm-12  @if ($errors->has('lastname')) has-error @endif">
				<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last name" value="{{old('lastname',$user->accounts->lastname)}}" disabled/>
				<em id="lastname-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label  text-left" for="email">*Email</label>
			<div class="col-sm-12  @if ($errors->has('email')) has-error @endif">
				<input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{old('email',$user->email)}}" disabled/>
				<em id="email-error" class="error help-block hide">
				</em>
			</div>
		</div>
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label  text-left" for="cphonenumber">*Phone Number</label>
			<div class="col-sm-12  @if ($errors->has('cphonenumber')) has-error @endif">
				<input type="text" class="form-control" id="cphonenumber" name="cphonenumber" placeholder="Phone Number" value="{{old('cphonenumber',$user->cphonenumber)}}" disabled/>
				<em id="cphonenumber-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row my-lg-3">
		<div class="form-group col-lg-12  text-left">
			<div class="col-sm-12">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>	
	</form>	