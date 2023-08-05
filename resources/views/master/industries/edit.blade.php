<form id="editindustryForm" method="post" class="form-horizontal" action="{{{ route('master.industries.update') }}}">
	@csrf
	<input type = "hidden" name = "id" value = "{{$industry->serial}}">
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="industry"> Gig Type </label>
			<div class="col-sm-12  @if ($errors->has('industry')) has-error @endif">
				<input type="text" class="form-control" id = "industry" name="industry" placeholder = "" value="{{old('industry',$industry->industry)}}"/>
				<em id="industry-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row my-lg-3">
		<div class="form-group col-lg-12">
			<div class="col-sm-12 text-right">
				<button type="submit" class="btn btn-primary submitButton" name="save" value="Save Changes"> Save</button> 
				<a href="javascript:void(0)" class="btn btn-danger deleteaction"   data-string = "Do you want to remove this item?"   data-url = "{!! route('master.industries.delete', $industry->id) !!}"   data-title="Delete Gig Type">
					Delete
				</a>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">  Close</button>
			</div>
		</div>
	</div>	
</form>