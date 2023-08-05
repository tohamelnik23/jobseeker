<form id="editskillForm" method="post" class="form-horizontal" action="{{{ route('master.skills.update') }}}">
	@csrf
	<input type="hidden" name="id" value="{{$skill->id}}">
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left text-bold" for="skill">Skill</label>
			<div class="col-sm-12  @if($errors->has('skill')) has-error @endif">
				<input type="text" class="form-control" id="skill" name="skill" placeholder="Skill" value="{{old('skill', $skill->skill)}}"/>
				<em id="skill-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row my-lg-3">
		<div class="form-group col-lg-12">
			<div class="col-sm-12">
				<button type="button" class="btn btn-primary submitButton" name="save" value="Save Changes">Save Changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>	
</form>