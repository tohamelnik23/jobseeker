<div id="socialsecuritynumberFormresponce" class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">×</button>	
		<strong></strong>
</div>
<form id="socialsecuritynumberForm" method="post" class="form-horizontal" action="{{{ route('employee.update.socialsecuritynumber') }}}">
	<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="socialsecuritynumber"><star>*</star> Social Security Number</label>
			<div class="col-sm-12  @if ($errors->has('socialsecuritynumber')) has-error @endif">
				<input type="text" class="form-control"   name="socialsecuritynumber" placeholder="Social Security Number" value="{{old('socialsecuritynumber',$user->accounts->socialsecuritynumber)}}"/>
				<em  class="error help-block hide">
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