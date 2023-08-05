<div id="responce" class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">×</button>	
		<strong></strong>
</div>
<form id="addressForm" method="post" class="form-horizontal" action="{{{ route('employee.update.address') }}}">
	@if(($user->address_verified_status == 0) ||  ($user->address_verified_status == 3))
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
		<div class="row">
			<div class="form-group col-lg-12">
				<label class="col-sm-12 control-label text-left" for="caddress"><star>*</star> Address</label>
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
				<label class="col-sm-12 control-label text-left" for="oaddress">Address 2</label>
				<div class="col-sm-12  @if ($errors->has('oaddress')) has-error @endif">
					<input type="text" class="form-control" id="oaddress" name="oaddress" placeholder="Address" value="{{old('oaddress',$user->accounts->oaddress)}}"/>
					<em id="oaddress-error" class="error help-block hide">
					</em>
				</div>
			</div>
		</div> 
		<div class="row">
			<div class="form-group col-lg-4">
				<label class="col-sm-12 control-label text-left" for="city"><star>*</star> City</label>
				<div class="col-sm-12  @if ($errors->has('city')) has-error @endif">
					<input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{old('city',$user->accounts->city)}}"/>
					<em id="city-error" class="error help-block hide">
					</em>
				</div>
			</div>
			<div class="form-group col-lg-4">
				<label class="col-sm-12 control-label text-left" for="state"><star>*</star> State</label>
				<div class="col-sm-12  @if ($errors->has('state')) has-error @endif">
					@php
						$states = DB::table("states")->get();
					@endphp
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
				<label class="col-sm-12 control-label text-left" for="zip"> <star>*</star> Zip Code</label>
				<div class="col-sm-12  @if ($errors->has('zip')) has-error @endif">
					<input type="text" class="form-control" id="zip" name="zip" placeholder="Zip"  value="{{old('zip',$user->accounts->zip)}}"/>
					<em id="zip-error" class="error help-block hide">
					</em>
				</div>
			</div>
		</div>
	@else 
		<div class="card mar-btm">
			<div class="card-body">
				@if($user->accounts->caddress != NULL)<p><b class="col-lg-3">Address:</b>{{$user->accounts->caddress}}</p>@endif
				@if($user->accounts->city != NULL)<p><b class="col-lg-3">City:</b>{{$user->accounts->city}}</p>@endif
				@if($user->accounts->state != NULL)<p><b class="col-lg-3">State:</b>{{$user->accounts->state}}</p>@endif
				@if($user->accounts->zip != NULL)<p><b class="col-lg-3">Zip:</b>{{$user->accounts->zip}}</p>@endif
				@if($user->accounts->oaddress != NULL)<p><b class="col-lg-3">Address 2:</b>{{$user->accounts->oaddress}}</p>@endif
			</div>
		</div> 
	@endif

	@if(($user->address_verified_status == 0) ||  ($user->address_verified_status == 3))	
		<p class = "mar-top">
			<strong>Upload an official government ID to verify your address details.</strong><br>
			<small>image/jpeg, image/jpg, image/png,.pdf files are accepted only.</small>
		</p> 
		<div class="needsclick dropzone" id="address-dropzone">
		</div> 
		<span class="image-upload-error-address error invalid-feedback"> </span>
		<br>
	@endif 

	<div class="row my-lg-3">
		<div class="form-group col-lg-12">
			<div class="col-sm-12">
				@if(($user->address_verified_status == 0) ||  ($user->address_verified_status == 3))
					<button type="submit"  id="address-saveonly-submit"  class="btn btn-primary" name="save" value="Save Changes">Save Only</button>
					<button type="submit" id="address-submit" class="btn btn-primary">Save and Request Verify</button>	 
				@endif 
				<button type="button" class="btn btn-secondary btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div> 
</form>	