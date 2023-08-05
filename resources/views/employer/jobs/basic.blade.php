@php $i = 1; @endphp
<div class="text-center q_box @if($i == 1) show @else hide @endif" id="q_box{{$i}}">
	<h5>What is the location where gig will be done?</h5>
	<form id="job_location_form" method="post" class="form-horizontal" action="{{{ route('employer.job.addlocation') }}}">
		@csrf
		<div class="row">
			<div class="form-group col-lg-12"> 
				<input type="hidden" name="skill_id" value="{{$skill_id}}">
				<input type="hidden" name="jobid" value="{{$jobid}}">
				<input name="lat" type="hidden" value="">
				<input name="lng" type="hidden" value="">
				<div class="col-sm-12 eb  @if ($errors->has('job_location')) has-error @endif">
					<input type="text" class="form-control" id="job_location" name="job_location" placeholder="Location for the gig..." value="{{$job->job_location}}"/>
					<em id="job_location-error" class="error help-block hide">
					</em>
				</div>
			</div>
		</div>
		<div class="row my-lg-3">
			<div class="form-group col-lg-12">
				<div class="col-sm-12">
					@if($i > 1) 
						<button type="button" class="btn btn-primary back" name="back" value="Back" data-id="{{$i}}">Back</button>
					@endif
					<button type="submit" class="btn btn-primary location_submit" name="submit" value="Submit" data-id="{{$i}}">Submit</button>
				</div>
			</div>
		</div>	
	</form>
</div>	
@php $i = 2; @endphp
	<div class="text-center q_box @if($i == 1) show @else hide @endif" id="q_box{{$i}}">
		<h4>What is the date of gig?</h4>
		<form id="job_date_form" method="post" class="form-horizontal" action="{{{ route('employer.job.adddate') }}}">
			@csrf
			<div class="row">
				<div class="form-group col-lg-6"> 
					<input type="hidden" name="skill_id" value="{{$skill_id}}">
					<input type="hidden" name="jobid" 	 value="{{$jobid}}">
					<div class="col-sm-12 eb  @if ($errors->has('job_date')) has-error @endif">
						<input type="text" class="form-control" id="job_date" name="job_date" placeholder="Date..." value="{{$job->job_date}}"/>
						<em id="job_date-error" class="error help-block hide">
						</em>
					</div>
				</div>
				<div class="form-group col-lg-6">
					<div class="col-sm-12 input-group bootstrap-timepicker timepicker eb  @if ($errors->has('job_time')) has-error @endif">
						<input type="text" class="form-control" id="job_time" name="job_time" placeholder="Time..." value="{{$job->job_time}}"/>
						<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
						<em id="job_time-error" class="error help-block hide">
						</em>
					</div>
				</div>
			</div>
			<div class="row my-lg-3">
				<div class="form-group col-lg-12">
					<div class="col-sm-12">
						@if($i > 1) 
							<button type="button" class="btn btn-primary back" name="back" value="Back" data-id="{{$i}}">Back</button>
						@endif
						<button type="submit" class="btn btn-primary date_submit" name="submit" value="Submit" data-id="{{$i}}">Submit</button>
					</div>
				</div>
			</div>	
		</form>
	</div>

	@foreach($questions_by_employee as $question)
		<div class="text-center q_box @if($i == 1) show @else hide @endif" id="q_box{{$i}}">
			<h4>We would like to know</h4><h5>Below answer from you about your gig.</h5>
			<form id="answer_questions_form_{{$i}}" method="post" class="form-horizontal" action="{{{ route('employer.job.addanswer') }}}">
				@csrf
				<div class="row">
					<div class="form-group col-lg-12">
						<input type="hidden" name="question_id" value="{{$question->id}}">
						<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
						<input type="hidden" name="finalval" value="@if(count($questions_by_employee) == $i) finalval @endif">
						<input type="hidden" name="skill_id" value="{{$skill_id}}">
						<input type="hidden" name="jobid" value="{{$jobid}}">
						<label class="col-sm-12 control-label" for="question">{{$question->question}}</label>
						@if($question->question_type == 'normal')
							<div class="col-sm-12 eb  @if ($errors->has('answer')) has-error @endif">
								<input type="text" class="form-control" id="answer" name="answer" placeholder="{{$question->placeholder_question}}" value="{{$question->answer}}"/>
								<em id="answer-error" class="error help-block hide">
								</em>
							</div>
						@endif
						@if($question->question_type == 'single_option')
							@php $k = 1; @endphp
							@foreach(  App\Model\Skillquestionoption::where('skill_question_id',$question->id)->get() as $options )
								<div class="form-check form-check-inline eb">
								<input class="form-check-input" type="radio" name="answer" value="{{$options->option_name}}" @if($k == 1 || $question->answer == $options->option_name) checked @endif>
								<label class="form-check-label">{{$options->option_name}}</label>
								</div>
								@php $k++; @endphp
							@endforeach
							<em id="answer-error" class="error help-block hide">
								</em>
						@endif
					</div>
				</div>
				<div class="row my-lg-3">
					<div class="form-group col-lg-12">
						<div class="col-sm-12">
							@if($i > 1) 
								<button type="button" class="btn btn-primary back" name="back" value="Back" data-id="{{$i}}">Back</button>
							@endif
							<button type="submit" class="btn btn-primary ans_submit" data-skillid="{{$skill_id}}" data-jobid="{{$jobid}}" name="next" value="Next" data-id="{{$i}}">Next</button>
						</div>
					</div>
				</div>	
			</form>
		</div>	
		@php $i++; @endphp
	@endforeach
 
	@foreach($questions_by_employer as $question)
		<div class="text-center q_box @if($i == 1) show @else hide @endif" id="q_box{{$i}}">
			<h5>Do you agree</h2> <h3>this question should be asked from employees?</h5>
			<form id="employee_questions_form_{{$i}}" method="post" class="form-horizontal" action="{{{ route('employer.job.agreequestions') }}}">
				@csrf
				<div class="row">
					<div class="form-group col-lg-12">
						<input type="hidden" name="question_id" value="{{$question->id}}">
						<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
						<input type="hidden" name="finalval" value="@if(count($questions_by_employer) == $i) finalval @endif">
						<input type="hidden" name="skill_id" value="{{$skill_id}}">
						<input type="hidden" name="jobid" value="{{$jobid}}">
						<label class="col-sm-12 control-label" for="question">{{$question->question}}</label>
						@if($question->question_type == 'normal')
							
						@endif
						@if($question->question_type == 'single_option')
							@php $k = 1; @endphp
							@foreach(  App\Model\Skillquestionoption::where('skill_question_id',$question->id)->get() as $options )
								<div class="form-check form-check-inline eb">
								<input class="form-check-input" type="radio" name="answer" value="{{$options->option_name}}" @if($k == 1) checked @endif disabled>
								<label class="form-check-label">{{$options->option_name}}</label>
								</div>
								@php $k++; @endphp
							@endforeach
							<em id="answer-error" class="error help-block hide">
								</em>
						@endif
					</div>
				</div>
				<div class="row my-lg-3">
					<div class="form-group col-lg-12">
						<div class="col-sm-12">
							@if($i > 1) 
								<button type="button" class="btn btn-primary back" name="back" value="Back" data-id="{{$i}}">Back</button>
							@endif
							<button type="submit" class="btn btn-primary agree_questions_yes" data-skillid="{{$skill_id}}"  data-jobid="{{$jobid}}"  name="yes" value="Yes" data-id="{{$i}}">Yes</button>
							<button type="submit" class="btn btn-secondary agree_questions_no" data-skillid="{{$skill_id}}"  data-jobid="{{$jobid}}"  name="no" value="No" data-id="{{$i}}">No</button>
						</div>
					</div>
				</div>	
			</form>
		</div>	
		@php $i++; @endphp
	@endforeach
	
	<?php for ($x = 0; $x <= 10; $x++){ ?>
		<div class="q_box @if($i == 1) show @else hide @endif" id="q_box{{$i}}">
			<div class="row ">
				<div class="col-lg-12 text-center">
					<h4>Add Questions</h4>
					<h5>what do you want to know from employees about gig.</h5>
				</div>
			</div>
			<form id="add_new_question_form" method="post" class="form-horizontal" action="{{{ route('employer.job.addquestion') }}}">
				@csrf
				<input type="hidden" name="skill_id" value="{{$skill_id}}">
				<input type="hidden" name="jobid" value="{{$jobid}}">
				<input type="hidden" name="type_added_by" value="2">
				<input type="hidden" name="type_added_for" value="1">
				 
				<div class="form-group ">
					<label class="col-sm-12 control-label text-left" for="question">
						<strong>
							<star>*</star> Question
						</strong>
					</label>
					<div class="col-sm-12  @if ($errors->has('question')) has-error @endif">
						<input type="text" class="form-control" id="question" name="question" placeholder="What do you want to know from employers..." value=""/>
						<em id="question-error" class="error help-block hide">
						</em>
					</div>
				</div>
				
				<div class="form-group ">
					<label class="col-sm-12 control-label text-left" for="question">
						<strong>
							<star>*</star> Question type
						</strong>
					</label>
					<div class = "col-sm-12 "> 
						<div class="radio">  
							<input id="form-check-radio-{!! $x !!}" class="magic-radio"   type="radio"   name="question_type"    value = "normal" checked >
							<label for="form-check-radio-{!! $x !!}">Text</label> 
							<input id="form-check-radio-2-{!! $x !!}" class="magic-radio" type="radio"   name="question_type"  value ="single_option">
							<label for="form-check-radio-2-{!! $x !!}">Options (Single Choice)</label> 
						</div>  
					</div>
				</div>

				
				<div class="row" id="placeholder_box">
					<div class="form-group col-lg-12">
						<label class="col-sm-12 control-label text-left" for="placeholder_question">
							<strong>
								Placeholder (to explain the employer what is this question for)
							</strong>
						</label>
						<div class="col-sm-12  @if ($errors->has('placeholder_question')) has-error @endif">
							<input type="text" class="form-control" id="placeholder_question" name="placeholder_question" placeholder="What is the above question for..." value=""/>
							<em id="placeholder_question-error" class="error help-block hide"></em>
						</div>
					</div>
				</div>
				<div class="row" id="options_box">
					<div class="form-group col-lg-12">
						<label class="col-sm-12 control-label text-left" for="option1">Option Label</label>
						<div class="col-sm-12  @if ($errors->has('option1')) has-error @endif">
							<input type="text" class="form-control" id="option1" name="option1" placeholder="Option Label" value=""/>
							<em id="option1-error" class="error help-block hide"></em>
						</div>
					</div>
					<div class="form-group col-lg-12">
						<label class="col-sm-12 control-label text-left" for="option2">Option Label</label>
						<div class="col-sm-12  @if ($errors->has('option2')) has-error @endif">
							<input type="text" class="form-control" id="option2" name="option2" placeholder="Option Label" value=""/>
							<em id="option2-error" class="error help-block hide"></em>
						</div>
					</div>
					<div class="form-group col-lg-12">
						<label class="col-sm-12 control-label text-left" for="option3">Option Label</label>
						<div class="col-sm-12  @if ($errors->has('option3')) has-error @endif">
							<input type="text" class="form-control" id="option3" name="option3" placeholder="Option Label" value=""/>
							<em id="option3-error" class="error help-block hide"></em>
						</div>
					</div>
					<div class="form-group col-lg-12">
						<label class="col-sm-12 control-label text-left" for="option4">Option Label</label>
						<div class="col-sm-12  @if ($errors->has('option4')) has-error @endif">
							<input type="text" class="form-control" id="option4" name="option4" placeholder="Option Label" value=""/>
							<em id="option4-error" class="error help-block hide">
							</em>
						</div>
					</div>
				</div>
				<div class="row my-lg-3">
					<div class="form-group col-lg-12">
						<div class="col-sm-12">
							@if($i > 1) 
								<button type="button" class="btn btn-primary back" name="back" value="Back" data-id="{{$i}}">Back</button>
							@endif
							<input id="add_new_question_form_sub" type="submit" class="btn btn-primary" name="save" value="Save & add more" data-name="save" data-id="{{$i}}">
							<input id="add_new_question_form_sub" type="submit" class="btn btn-primary" name="savesubmit" value="Save & submit" data-name="savesubmit" data-id="{{$i}}"> 
						</div>
					</div>
				</div>	
			</form>	
		</div>	
	<?php 
		$i++;
	}
?>