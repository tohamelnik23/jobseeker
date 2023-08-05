@extends('layouts.app')
@section('title', 'Add Gig')
@section('css')
<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">
@endsection

@section('content')                          			                      
	<div class="container">
		<div class="row justify-content-center text-center">
			<div class="col-md-12">
				<h4>What do you want to get done?</h4>
			</div>
			<form id="job_post" method="post" class="form-horizontal col-lg-6" action="{{{ route('employer.job.post') }}}">
				@csrf
				<div class="row">
					<div class="form-group col-lg-12">
						<div class="col-sm-12  @if ($errors->has('skills')) has-error @endif">
							<input type="text" class="typeahead form-control" id="skills" name="skills" placeholder="What do you want to get done?" value="{{old('skills','')}}"/>
							<em id="skills-error" class="error help-block hide"></em>
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
		</div>
	</div> 

	<div style="overflow-x: hidden; overflow-y: auto;" class="modal fade" id="jobModal" tabindex="-1" role="dialog" aria-labelledby="jobModalLabel" aria-hidden="true" >
		<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="jobModalLabel">Gig</h5>
				</div>
				<div class="modal-body"> 
				</div>
			</div>
		</div>
	</div>  
@endsection
@section('javascript')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/typeahead.bundle.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2Lze4Darf903qJrfbrjAqyMhJGqvel0A&libraries=places" ></script>
<script src="{{ asset('/js/jquery.geocomplete.js') }}"></script>
<script>
$(function(){
	$("#job_location").geocomplete({
		map: ".map_canvas",
		details: "form",
		types: ["geocode", "establishment"],
		});
	});
	$(document).ready( function (){
		$('input.typeahead').typeahead({
			source:  function (query, process) {
			return $.get("{{route('skillsemployer')}}", { query: query }, function (data) {
					console.log(data);
					data = $.parseJSON(data);
					return process(data);
				});
			}
		});
		$( "#job_post" ).validate({
			rules: {
				skills: "required",
			},
			messages: {
				skills: "Please enter what do you want to get done?"
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else {
					error.insertAfter( element );
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
			},submitHandler: function(form) {
				$(form).find("button[type=submit]").prop('disabled',true);
				var dataS = $(form).serialize();
				$.ajax({
					type: $(form).attr('method'),
					url: $(form).attr('action'),
					data: dataS,
					success: function(data) {
						if(data.error==true){
							$.each(data.errors, function(key, value){
								var input = '#job_post #'+key;
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							$.ajax({
								url: "{{route('employer.job.basic')}}",
								type: "post",
								data: {
									'skill_id':data.skill_id,
									'jobid':data.jobid,
									"_token": "{{ csrf_token() }}",
								},
								success: function(data) {
									if(data.error==true){
										$('#jobModal').modal('hide'); 
									}else{
										$('#jobModal').find('.modal-body').html(data.view);
										$('#jobModal').modal({backdrop: 'static', keyboard: false}, 'show'); 
									}
									if($('input:radio[name^="question_type"]:checked').val() == 'normal'){
										$('#placeholder_box').show();
										$('#options_box').hide();
									}else{
										$('#placeholder_box').hide();
										$('#options_box').show();
									}
									$('#job_date').datepicker();
									$('#job_time').timepicker();
									$("#job_location").trigger("geocode");
									
									$("#job_location").geocomplete({
									details: "form",
									types: ["geocode", "establishment"],
									});
								}
							});
						}
					}
				});
			},
		});
	});
	$(document.body).on('click', '.location_submit', function(){
		var id = $(this).data('id');
		$( "#job_location_form" ).validate({
			rules: {
				job_location: "required",
			},
			messages: {
				job_location: "Gig location field is must.",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );
				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else {
					error.insertAfter( element );
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
			},submitHandler: function(form) {
				$(form).find("button[type=submit]").prop('disabled',true);
				var dataS = $(form).serialize();
				$.ajax({
					type: $(form).attr('method'),
					url: $(form).attr('action'),
					data: dataS,
					success: function(data) {
						if(data.error==true){
							$.each(data.errors, function(key, value){
								var input = '#job_location_form input[name=' + key + ']';
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							$(".q_box").addClass('hide').removeClass('show');
							var n_id = id+1;
							$("#q_box"+n_id).addClass('show').removeClass('hide');
						}
					}
				});
			},
		});
	});	
	$(document.body).on('click', '.date_submit', function(){
		var id = $(this).data('id');
		$( "#job_date_form" ).validate({
			rules: {
				job_date: "required",
				job_time: "required",
			},
			messages: {
				job_date: "Gig date field is must.",
				job_time: "Gig time field is must.",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else {
					error.insertAfter( element );
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
			},submitHandler: function(form) {
				$(form).find("button[type=submit]").prop('disabled',true);
				var dataS = $(form).serialize();
				$.ajax({
					type: $(form).attr('method'),
					url: $(form).attr('action'),
					data: dataS,
					success: function(data) {
						console.log(data);
						if(data.error==true){
							$.each(data.errors, function(key, value){
								var input = '#job_date_form input[name=' + key + ']';
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							$(".q_box").addClass('hide').removeClass('show');
							var n_id = id+1;
							$("#q_box"+n_id).addClass('show').removeClass('hide');
						}
					}

				});
			},
		});
	}); 
	$(document.body).on('click', '.ans_submit', function(){
		var id = $(this).data('id');
		var skillid = $(this).data('skillid');
		var jobid = $(this).data('jobid');
		var finalval = $(this).data('finalval');
		$(this).closest('form').validate({
			rules: {
				question_id: "required",
				answer: "required",
			},
			messages: {
				question_id: "Question not found try again later",
				answer: "Answer is must.",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else {
					error.insertAfter( element );
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
			},submitHandler: function(form) {
				$(form).find("button[type=submit]").prop('disabled',true);
				var dataS = $(form).serialize();
				$.ajax({
					type: $(form).attr('method'),
					url: $(form).attr('action'),
					data: dataS,
					success: function(data) {
						if(data.error==true){
							$.each(data.errors, function(key, value){
								var input = '#skillquestionsModal #q_box'+id+' .eb';
								$(input).addClass('has-error');
								$('#skillquestionsModal #q_box'+id+' #'+key+'-error').removeClass('hide').addClass('show').text(value);
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							$(".q_box").addClass('hide').removeClass('show');
							var n_id = id+1;
							$("#q_box"+n_id).addClass('show').removeClass('hide');
							
						}
					}

				});
			},
		});
	}); 
	$(document.body).on('click', '.agree_questions_yes', function(){
		var id = $(this).data('id');
		var skillid = $(this).data('skillid');
		var jobid = $(this).data('jobid');
		var finalval = $(this).data('finalval');
		$(this).closest('form').validate({
			rules: {
				question_id: "required",
				user_id: "required",
			},
			messages: {
				question_id: "Some error occured please try again later.",
				user_id: "Some error occured please try again later.",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else {
					error.insertAfter( element );
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
			},submitHandler: function(form) {
				$(form).find("button[type=submit]").prop('disabled',true);
				var dataS = $(form).serialize();
				$.ajax({
					type: $(form).attr('method'),
					url: $(form).attr('action'),
					data: dataS + '&vote=yes',
					success: function(data) {
						if(data.error==true){
							$.each(data.errors, function(key, value){
								var input = '#skillquestionsModal #q_box'+id+' .eb';
								$(input).addClass('has-error');
								$('#skillquestionsModal #q_box'+id+' #'+key+'-error').removeClass('hide').addClass('show').text(value);
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							$(".q_box").addClass('hide').removeClass('show');
							var n_id = id+1;
							$("#q_box"+n_id).addClass('show').removeClass('hide');
							
						}
					}

				});
			},
		});
	});
	$(document.body).on('click', '.agree_questions_no', function(){
		var id = $(this).data('id');
		var skillid = $(this).data('skillid');
		var jobid = $(this).data('jobid');
		var finalval = $(this).data('finalval');
		$(this).closest('form').validate({
			rules: {
				question_id: "required",
				user_id: "required",
			},
			messages: {
				question_id: "Some error occured please try again later.",
				user_id: "Some error occured please try again later.",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else {
					error.insertAfter( element );
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
			},submitHandler: function(form) {
				$(form).find("button[type=submit]").prop('disabled',true);
				var dataS = $(form).serialize();
				$.ajax({
					type: $(form).attr('method'),
					url: $(form).attr('action'),
					data: dataS + '&vote=no',
					success: function(data) {
						if(data.error==true){
							$.each(data.errors, function(key, value){
								var input = '#skillquestionsModal #q_box'+id+' .eb';
								$(input).addClass('has-error');
								$('#skillquestionsModal #q_box'+id+' #'+key+'-error').removeClass('hide').addClass('show').text(value);
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							$(".q_box").addClass('hide').removeClass('show');
							var n_id = id+1;
							$("#q_box"+n_id).addClass('show').removeClass('hide');
						}
					}

				});
			},
		});
	});
	$(document.body).on('click', '#add_new_question_form_sub', function(){
		var id = $(this).data('id');
		var typename = $(this).data("name");
		console.log(typename);
		$(this).closest('form').validate({
			rules: {
				question: "required",
				question_type: "required",
				option1:{
					required: {
						depends: function(element) {
						return ($("#single_option").is(':checked') || $("#multiple_option").is(':checked'));
						}
					} 
				},
				option2:{
					required: {
						depends: function(element) {
						return ($("#single_option").is(':checked') || $("#multiple_option").is(':checked'));
						}
					} 
				}
			},
			messages: {
				question: "Please enter your question",
				question_type: "Please select your question type",
				option1: "Please add option",
				option2: "Please add option",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else {
					error.insertAfter( element );
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
			},submitHandler: function(form) {
				$(form).find("input[type=submit]").prop('disabled',true);
				var dataS = $(form).serialize();
				$.ajax({
					type: $(form).attr('method'),
					url: $(form).attr('action'),
					data: dataS,
					success: function(data) {
						if(data.error==true){
							$.each(data.errors, function(key, value){
								var input = '#skillquestionsModal #'+key;
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
							});
						}else{
							$(form).find("input[type=submit]").prop('disabled',false);
							if(typename == 'savesubmit'){
								Swal.fire(
										'Added!',
										'Your gig has been added.',
										'success'
										).then((isConfirm) => {
										if(isConfirm){
											location.reload();
										}
										});
							}else{
								$(".q_box").addClass('hide').removeClass('show');
								var n_id = id+1;
								$("#q_box"+n_id).addClass('show').removeClass('hide');
								
								if($('#q_box'+n_id+' input:radio[name^="question_type"]:checked').val() == 'normal'){
									$('#q_box'+n_id+' #placeholder_box').show();
									$('#q_box'+n_id+' #options_box').hide();
								}else{
									$('#q_box'+n_id+' #placeholder_box').hide();
									$('#q_box'+n_id+' #options_box').show();
								}
							}
						}
					}

				});
			},
		});
	});
	$(document).on('change', 'input:radio[name^="question_type"]', function (event) {
		if(this.value == 'normal'){
			$('.show').find('#placeholder_box').show();
			$('.show').find('#options_box').hide();
		}else{
			$('.show').find('#placeholder_box').hide();
			$('.show').find('#options_box').show();
		}
	});
	$(document.body).on('click', '.back', function(){
		var id = $(this).data('id');
		$(".q_box").addClass('hide').removeClass('show');
		var n_id = id-1;
		$("#q_box"+n_id).addClass('show').removeClass('hide');
	});

</script>	
@stop