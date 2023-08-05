<div id="educationresponce" class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">×</button>	
		<strong></strong>
</div>
<form id="addeducationForm" method="post" class="form-horizontal" action="{{{ route('employee.add.education') }}}">
	<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="school"><star>*</star> School</label>
			<div class="col-sm-12  @if ($errors->has('school')) has-error @endif">
				<input type="text" class="form-control" id="school" name="school" placeholder="Ex: Boston University" value="{{old('school','')}}"/>
				<em id="school-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="degree">Degree</label>
			<div class="col-sm-12  @if ($errors->has('degree')) has-error @endif">
				<input type="text" class="form-control" id="degree" name="degree" placeholder="Ex: Bachelor’s" value="{{old('degree','')}}"/>
				<em id="degree-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="fieldofstudy">Field of study</label>
			<div class="col-sm-12  @if ($errors->has('fieldofstudy')) has-error @endif">
				<input type="text" class="form-control" id="fieldofstudy" name="fieldofstudy" placeholder="Ex: Business" value="{{old('fieldofstudy','')}}"/>
				<em id="fieldofstudy-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label text-left" for="startyear">Start Year</label>
			<div class="col-sm-12  @if ($errors->has('startyear')) has-error @endif">
				<input type="text" class="form-control" id="startyear" name="startyear" placeholder="Ex: 2007" value="{{old('startyear')}}"/>
				<em id="startyear-error" class="error help-block hide">
				</em>
			</div>
		</div>
		<div class="form-group col-lg-6">
			<label class="col-sm-12 control-label text-left" for="endyear">End Year (or expected)</label>
			<div class="col-sm-12  @if ($errors->has('endyear')) has-error @endif">
				<input type="text" class="form-control" id="endyear" name="endyear" placeholder="Ex: 2011" value="{{old('endyear')}}"/>
				<em id="endyear-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="grade">Grade</label>
			<div class="col-sm-12  @if ($errors->has('grade')) has-error @endif">
				<input type="text" class="form-control" id="grade" name="grade" placeholder="Grade" value="{{old('grade','')}}"/>
				<em id="grade-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left"  for="activities">Activities and societies</label>
			<div class="col-sm-12  @if ($errors->has('activities')) has-error @endif">
				<textarea rows="4" id="activities" name="activities" class="form-control" placeholder="Ex: Alpha Phi Omega, Marching Band, Volleyball"></textarea>
				<em id="activities-error" class="error help-block">
				</em>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left"  for="description">Description</label>
			<div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
				<textarea rows="4" id="description" name="description" class="form-control" placeholder="Description"></textarea>
				<em id="description-error" class="error help-block">
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