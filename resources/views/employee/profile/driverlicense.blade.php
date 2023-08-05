<div   class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">×</button>	
	<strong></strong>
</div> 
<form id="driverlicenseForm" method="post" class="form-horizontal" action="{{{ route('employee.update.driverlicense') }}}"> 
    @if( (!isset($driver_license))   ||   ($driver_license->verified == 0) ||  ($driver_license->verified == 3))
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
		<div class="row">
			<div class="form-group col-lg-12">
				<label class="col-sm-12 control-label text-left" for="driver_state"><star>*</star> State</label>
				<div class="col-sm-6  @if ($errors->has('driver_state')) has-error @endif">
                    <select class = "form-control" name = "driver_state">
                        @foreach($states as $state)
                            <option value = "{{ $state->state}}"  @if(isset($driver_license) &&  ( $driver_license->state == $state->state)) selected @endif> 
                                {{ $state->state }} 
                            </option>
                        @endforeach
                    </select> 
					<em id="driver_state-error" class="error help-block hide">
					</em> 
				</div>
			</div>
		</div>
        
        <div class="row">
			<div class="form-group col-lg-12">
				<label class="col-sm-12 control-label text-left" for="plate_number"><star>*</star>Plate Number</label>
				<div class="col-sm-6  @if ($errors->has('plate_number')) has-error @endif">
					<input style="text-transform: uppercase;"    type="text" class="form-control" id="plate_number" name="plate_number" maxlength = '10' placeholder="GGG0563" value="{{old('plate_number',  isset($driver_license)?$driver_license->plate_number:'')}}"/> 
					<em id="plate_number-error" class="error help-block hide">
					</em> 
				</div>
			</div>
		</div>

        <div class="row">
			<div class="form-group col-lg-12">
				<label class="col-sm-12 control-label text-left" for="expiration_year"><star>*</star>Expiration Year</label>
				<div class="col-sm-3  @if ($errors->has('expiration_year')) has-error @endif">
                    <select class = "form-control" name = "expiration_year">
                        @for($i = date('Y') ; $i <= date('Y') + 20; $i++)
                            <option value = "{!! $i !!}"  @if(isset($driver_license) &&  ( $driver_license->expiration_year == $i)) selected @endif  >{!! $i !!}</option>
                        @endfor
                    </select>  
				</div>
			</div>
		</div> 
        <div class="row">
			<div class="form-group col-lg-12">
				<label class="col-sm-12 control-label text-left" for="expiration_month"><star>*</star>Expiration Month</label>
				<div class="col-sm-3  @if ($errors->has('expiration_month')) has-error @endif">
                    <select class = "form-control" name = "expiration_month">
                        @php
                            $months_array = Mainhelper::getMonthArray(); 
                        @endphp 
                        @foreach($months_array as $month_key => $month)
                            <option value = "{!! $month_key + 1 !!}" @if(isset($driver_license) &&  ( $driver_license->expiration_month == ($month_key + 1))) selected @endif  >{!! $month !!}</option>
                        @endforeach
                    </select>  
				</div>
			</div>
		</div> 
	@else
		<div class="card mar-btm">
			<div class="card-body">
				@if($driver_license->plate_number != NULL)<p><b class="col-lg-3">Number:</b>{{$driver_license->plate_number}}</p>@endif
				@if($driver_license->state != NULL)<p><b class="col-lg-3">State:</b>{{$driver_license->state}}</p>@endif 
                <p><b class="col-lg-3">Expiration:</b>{{$driver_license->expiration_year}}/{{$driver_license->expiration_month}}</p>  
			</div>
		</div> 
	@endif 
    @if( (!isset($driver_license))   ||   ($driver_license->verified == 0) ||  ($driver_license->verified == 3)) 
        <p class = "mar-top">
			<strong>Upload an official government ID to verify your address details.</strong><br>
			<small>image/jpeg, image/jpg, image/png,.pdf files are accepted only.</small>
		</p> 
		<div class="needsclick dropzone" id="driverlicense-dropzone">
		</div> 
		<span class="image-upload-error-driverlicense error invalid-feedback"> </span>
		<br>
	@endif  

    <div class="row my-lg-12 text-right">
		<div class="form-group col-lg-12">
			<div class="col-sm-12">
                @if( (!isset($driver_license))   ||   ($driver_license->verified == 0) ||  ($driver_license->verified == 3))
					<button type="submit"  id="driverlicense-saveonly-submit"  class="btn btn-primary" name="save" value="Save Changes">Save Only</button>
					<button type="submit" id="driverlicense-submit" class="btn btn-primary">Save and Request Verify</button>	 
				@endif 
				<button type="button" class="btn btn-secondary btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div> 
</form>