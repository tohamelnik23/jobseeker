@extends('layouts.master')
@section('title','Skills Management')
@section('css')
<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}"  type="text/css"/>
@endsection
@section('content')
	<div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Skills Management </h1>
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
								<a href = "javascript:void(0)"  title="Add Skill" type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addskillModal">					
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
										@forelse($skills as $skill)
											<tr>
												<td>{!! $skill->skill !!}</td>
												<td>  @if($skill->created_at) {!! $skill->created_at->format('m/d/Y') !!} @endif</td>
												<td> 
													<a href="javascript:void(0)"    class="btn btn-success margin-5 editskill" data-id = "{!! $skill->id  !!}"  >Edit</a>
													<a href="javascript:void(0)"    class="btn btn-danger margin-5 deleteaction"   data-string = "Do you want to remove this skill?"   data-url = "{!! route('master.skills.delete', $skill->id) !!}"   data-title="Delete Skill">
														Delete
													</a>
												</td>
											</tr>
										@empty
											<tr>       
												<td colspan = "10" class = "text-center"> There are  no skills.  </td>
											</tr>
										@endforelse
									</tbody>
								</table> 
							</div> 

							<div class = "text-right">   
								{{$skills->links()}} 
							</div> 


						</div> 
					</div>
				</div>
			</div>
		</div> 
	</div> 
	<!-- add Skills -->
	<div class="modal fade" id="addskillModal" tabindex="-1" role="dialog" aria-labelledby="addskillModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">  
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
					<h4 class="modal-title">   Add new Skill  </h4>
				</div> 
				<div class="modal-body"> 
					<form id="addskillForm" method="post" class="form-horizontal" action="{{{ route('master.skills.add') }}}">
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
						<div class="row">
							<div class="form-group col-lg-12">
								<label class="col-sm-12 control-label text-left" for="skill">Skill</label>
								<div class="col-sm-12  @if ($errors->has('skill')) has-error @endif">
									<input type="text" class="form-control" id="skill" name="skill" placeholder="Skill" value="{{old('skill','')}}"/>
									<em id="skill-error" class="error help-block hide">
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
  	<div class="modal fade" id="editskillsm" role="dialog"   aria-labelledby="editskillsm" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
					<h4 class="modal-title">   Skills - <span class="text-bold" id="editskillsm-title"></span>  </h4>
				</div> 
				<div class="modal-body"  id="editskillsmBody"> 
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
			$( "#addskillForm" ).validate({
				rules: {
					skill: "required",
				},
				messages: {
					skill: "Please enter skill name",
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
									var input = '#addskillForm input[name=' + key + ']';
									$(input).parent().addClass('has-error');
									$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
									$('#addskillFormresponce').addClass('hide');
								});
							}else{
								$(form).find("button[type=submit]").prop('disabled',false);
								swal({   
									title: "Skill",   
									text: "Skill has been added successfully!",   
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
			
		});	 
		function editskill($id){
			$.ajax({
					url: "{{route('master.skills.edit')}}",
					type: "post",
					data: {
						id: $id,
						"_token": "{{ csrf_token() }}",
						},
						success: function(response) {
							$("#editskillsm-title").html(response.title);
							$("#editskillsmBody").html(response.viewdata);
						}
				});
				$('#editskillsm').modal(); 
		}
		$(document).on("click", ".editskill", function(){
			editskill( $(this).attr('data-id') ); 
		});
		$('#editskillsmBody').on('click', '.submitButton', function(){ 
			var form = "#editskillForm"; 
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
							var input = '#editskillForm input[name=' + key + ']';
							$(input).parent().addClass('has-error');
							$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
							$('#editskillFormresponce').addClass('hide');
						});
					}else{		
						$(form).find("button[type=submit]").prop('disabled',false);
						swal({   
							title: "Skill",   
							text: "Skill has been updated successfully!",   
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
		});  
	</script>
@endpush