<div id="miscFormresponce" class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">×</button>	
		<strong></strong>
</div>
<form id="miscForm" method="post" class="form-horizontal text-dark" action="{{{ route('employee.update.misc') }}}">
	<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
	<div class="row">
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label text-left" for="firstname"><star>*</star> First name</label>
			<div class="col-sm-12  @if ($errors->has('firstname')) has-error @endif">
				<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First name" value="{{old('firstname',$user->accounts->firstname)}}" disabled/>
				<em id="firstname-error" class="error help-block hide">
				</em>
			</div>
		</div>
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label text-left" for="lastname"><star>*</star> Last name</label>
			<div class="col-sm-12  @if ($errors->has('lastname')) has-error @endif">
				<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last name" value="{{old('lastname',$user->accounts->lastname)}}" disabled/>
				<em id="lastname-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label text-left" for="email"><star>*</star> Email</label>
			<div class="col-sm-12  @if ($errors->has('email')) has-error @endif">
				<input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{old('email',$user->email)}}" disabled/>
				<em id="email-error" class="error help-block hide">
				</em>
			</div>
		</div>
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label text-left" for="cphonenumber"><star>*</star> Phone Number</label>
			<div class="col-sm-12  @if ($errors->has('cphonenumber')) has-error @endif">
				<input type="text" class="form-control" id="cphonenumber" name="cphonenumber" placeholder="Phone Number" value="{{old('cphonenumber',$user->cphonenumber)}}" disabled/>
				<em id="cphonenumber-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left"  for="headline"><star>*</star> Headline</label>
			<div class="col-sm-12  @if ($errors->has('headline')) has-error @endif">
				<textarea rows="4" id="headline" name="headline" class="form-control" placeholder="Headline">{{old('headline',$user->accounts->headline)}}</textarea>
				<em id="headline-error" class="error help-block">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<label class="col-sm-12 control-label text-left" for="birthdate"><star>*</star> Birth date</label>
			<div class="input-group date col-sm-12" data-provide="datepicker">
				<input type="text" name="birthdate" class="form-control" id="birthdate" value="{{old('birthdate',$user->accounts->birthdate)}}">
				<div class="input-group-addon">
					<span class="glyphicon glyphicon-th"></span>
				</div>
				@if ($errors->has('birthdate'))
					<em id="birthdate-error" class="error help-block">
						{{ $errors->first('birthdate') }}
					</em>
				@endif
			</div>
		</div> 
	</div>
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="socialsecuritynumber"><star>*</star>Social Security Number</label>
			<div class="col-sm-12  @if ($errors->has('socialsecuritynumber')) has-error @endif">
				<input type="text" class="form-control" id="socialsecuritynumber" name="socialsecuritynumber" placeholder="Social Security Number" value="{{old('socialsecuritynumber',$user->accounts->socialsecuritynumber)}}"/>
				<em id="socialsecuritynumber-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class = "row">

	</div>
	<div class="row my-lg-3">
		<div class="form-group col-lg-12">
			<div class="col-sm-12">
				<button type="submit" class="btn btn-primary" name="save" value="Save Changes">Save Changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>	
</form>