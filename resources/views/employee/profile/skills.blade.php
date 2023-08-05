<div id="skillsFormresponce" class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">×</button>	
		<strong></strong>
</div>
<form id="skillsForm" method="post" class="form-horizontal" action="{{{ route('employee.update.skills') }}}">
	<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="skills">Skills</label>
			<div class="col-sm-12  @if ($errors->has('skills')) has-error @endif">
				<input name="skills" id="skills" value="" class="typeahead form-control" type="text" autocomplete="new-password">
				<em id="skills-error" class="error help-block hide">
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