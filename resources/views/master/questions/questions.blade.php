@extends('layouts.master')
@section('title','Question Management')
@section('css')
<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}"  type="text/css"/>
@endsection
@section('content')
	<div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Question Management </h1>
		</div>
		<ol class="breadcrumb">
			<li><a href="{{route('master.dashboard')}}"><i class="demo-pli-home"></i></a></li>  
		</ol>
	</div> 
	<div id="page-content">
		@if(Session::has('success'))
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
				{!! session('success') !!}
			</div>
		@endif 
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<div class="panel">
					<div class="panel-body">
						<div class="col-sm-12">
							<div class = "text-right">
								<a href = "javascript:void(0)"  title="Add Skill" type="button" class="btn btn-primary pull-right addquestionModalButton">					
									<i class = "fa fa-plus"></i>
									Add New
								</a>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="table-responsive"> 
								<table class="table table-striped table-hover table-vcenter">
									<thead>
										<tr class="bg-teal">
											<th>Name</th>
											<th>Created</th>
											<th>Action</th>
										</tr>
									</thead> 
									<tbody>  
										@forelse($questions as $question)
											<tr>
												<td> {!! $question->question !!} </td>
												<td>  @if($question->created_at) {!! $question->created_at->format('m/d/Y') !!} @endif</td>
												<td> 
													<a href="javascript:void(0)"    class="btn btn-success margin-5 editquestion" data-id = "{!! $question->serial  !!}"  >Edit</a>  
												</td>
											</tr>
										@empty
											<tr>       
												<td colspan = "10" class = "text-center"> There are  no questions.  </td>
											</tr>
										@endforelse
									</tbody>
								</table> 
							</div>  
							<div class = "text-right">   
								{{$questions->links()}} 
							</div>  
						</div> 
					</div>
				</div>
			</div>
		</div> 
	</div> 
	<!-- add Skills -->
	<div class="modal fade" id="addquestionModal" tabindex="-1" role="dialog" aria-labelledby="addquestionModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">  
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
					<h4 class="modal-title">   Add New   Question  </h4>
				</div> 
				<div class="modal-body"> 
					<form id="addquestionForm" method="post" class="form-horizontal" action="{{{ route('master.questions.add') }}}">
						@csrf
						<div class="row">
							<div class="form-group col-lg-12">
								<label class="col-sm-12 control-label text-left" for="question">Question</label>
								<div class="col-sm-12  @if ($errors->has('question')) has-error @endif"> 
                                    <textarea class="form-control" id="question" cols = 5 name="question" placeholder="Question"></textarea> 
									<em id="question-error" class="error help-block hide">
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
				</div>
			</div>
		</div>
	</div>
	<!-- edit -->  
  	<div class="modal fade" id="editquestionModal" role="dialog"   aria-labelledby="editquestionModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
					<h4 class="modal-title">  Edit Question </h4>
				</div> 
				<div class="modal-body"  id="editquestionBody"> 
				</div>   
			</div>
		</div>
	</div> 
@endsection
@push('javascript')
	<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js?'.time())}}"></script>
	<script type="text/javascript" src="{{ asset('js/typeahead.bundle.min.js?'.time())}}"></script>
	<script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.js?'.time())}}"></script>
	<script type="text/javascript" src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>  
	<script type="text/javascript" src="{!!asset('jquery-datatable/jquery.dataTables.js')!!}"></script>
	<script type="text/javascript" src="{!!asset('jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')!!}"></script> 
	<script src="{!! asset('jquery-datatable/extensions/export/dataTables.buttons.min.js')!!}"></script>
	<script src="{!! asset('jquery-datatable/extensions/export/buttons.flash.min.js')!!}"></script>
	<script src="{!! asset('jquery-datatable/extensions/export/jszip.min.js')!!}"></script> 
	<script src="{!! asset('jquery-datatable/extensions/export/vfs_fonts.js')!!}"></script>
	<script src="{!! asset('jquery-datatable/extensions/export/buttons.html5.min.js')!!}"></script>
	<script src="{!! asset('jquery-datatable/extensions/export/buttons.print.min.js')!!}"></script> 
	<script> 
		$(document).ready( function (){ 
			$( "#addquestionForm" ).validate({
				rules: {
					skill: "required",
				},
				messages: {
					skill: "Please enter question name",
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
									var input = '#addquestionModal input[name=' + key + ']';
									$(input).parent().addClass('has-error');
									$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
								});
							}else{
								$(form).find("button[type=submit]").prop('disabled',false);
								swal({   
									title: "Question",   
									text: "Question has been added successfully!",   
									type: "success",   
									confirmButtonText: "Close" 
									}).then(function(isConfirm) {
									if(isConfirm){
										location.reload();
									} 
								});
							}
						}

					});
				},
			}); 
            $(".addquestionModalButton").click(function(){
                $("#addquestionForm")[0].reset();
                $("#addquestionModal").modal("show");
            });
		}); 
		function editquestion($id){
			$.ajax({
					url: "{{route('master.questions.edit')}}",
					type: "post",
					data: {
						id: $id,
						"_token": "{{ csrf_token() }}",
						},
						success: function(response) { 
							$("#editquestionBody").html(response.viewdata);
						}
				});
				$('#editquestionModal').modal(); 
		}
		$(document).on("click", ".editquestion", function(){
			editquestion( $(this).attr('data-id') ); 
		});
		$('#editquestionBody').on('click', '.submitButton', function(){ 
			var form = "#editQuestionForm"; 
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
							var input = '#editQuestionForm input[name=' + key + ']';
							$(input).parent().addClass('has-error');
							$('#edit_'+key+'-error').removeClass('hide').addClass('show').text(value); 
						});
					}else{
						$(form).find("button[type=submit]").prop('disabled',false);
						swal({
							title: "Question",   
							text: "Question has been updated successfully!",   
							type: "success",   
							confirmButtonText: "Close" 
							}).then(function(isConfirm){
							if(isConfirm){
								location.reload();
							} 
						});
					}
				} 
			}); 
		});  
	</script>
@endpush