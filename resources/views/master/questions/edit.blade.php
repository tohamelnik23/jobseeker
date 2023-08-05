<form id="editQuestionForm" method="post" class="form-horizontal" action="{{{ route('master.questions.update') }}}">
	@csrf
	<input type="hidden" name="id" value="{{$question->serial}}">
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left text-bold" for="edit_question">Question</label>
			<div class="col-sm-12  @if($errors->has('skill')) has-error @endif"> 
                <textarea class="form-control" id="edit_question" name="question" placeholder="Question">{{old('question', $question->question)}}</textarea> 
				<em id="edit_question-error" class="error help-block hide">
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