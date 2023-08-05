<div id="responce" class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">×</button>	
		<strong></strong>
</div> 
<form id="addressForm" method="post" class="form-horizontal" action="{{{ route('employer.update.address') }}}">
	<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="caddress"><star>*</star>Address</label>
			<div class="col-sm-12  @if ($errors->has('caddress')) has-error @endif">
				<input type="text" class="form-control" id="caddress" name="caddress" placeholder="Address" value="{{old('caddress',$user->accounts->caddress)}}"/>
				<input type="hidden" id="lat" name="lat" value="{{old('lat',$user->accounts->lat)}}"/>
				<input type="hidden" id="lng" name="lng" value="{{old('lng',$user->accounts->lng)}}"/>
				<em id="caddress-error" class="error help-block hide">
				</em> 
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label  text-left" for="oaddress">Address 2</label>
			<div class="col-sm-12  @if ($errors->has('oaddress')) has-error @endif">
				<input type="text" class="form-control" id="oaddress" name="oaddress" placeholder="Address" value="{{old('oaddress',$user->accounts->oaddress)}}"/>
				<em id="oaddress-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div> 
	<div class="row">
		<div class="form-group col-lg-4">
			<label class="col-sm-12 control-label  text-left" for="city"><star>*</star>City</label>
			<div class="col-sm-12  @if ($errors->has('city')) has-error @endif">
				<input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{old('city',$user->accounts->city)}}"/>
				<em id="city-error" class="error help-block hide">
				</em>
			</div>
		</div> 
		@php
			$states = DB::table("states")->get();
		@endphp 
		<div class="form-group col-lg-4">
			<label class="col-sm-12 control-label  text-left" for="state"><star>*</star>State</label>
			<div class="col-sm-12  @if ($errors->has('state')) has-error @endif">
				<select class = "form-control" id="state" name="state">
					@foreach($states as $state)
						<option value = "{{ $state->abbreviation }}"  @if($user->accounts->state ==  $state->abbreviation) selected @endif> {{ $state->state }} </option>
					@endforeach
				</select>
				<em id="state-error" class="error help-block hide">
				</em>
			</div>
		</div>

		<div class="form-group col-lg-4">
			<label class="col-sm-12 control-label  text-left" for="zip"><star>*</star>Zip Code</label>
			<div class="col-sm-12  @if ($errors->has('zip')) has-error @endif">
				<input type="text" class="form-control" id="zip" name="zip" placeholder="Zip"  value="{{old('zip',$user->accounts->zip)}}"/>
				<em id="zip-error" class="error help-block hide">
				</em>
			</div>
		</div>
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