@extends('layouts.master')
@section('title','Gig  Owner Profile | ' . $user->accounts->name)
@push('css')
	<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">	
	<link rel="stylesheet" href="{{asset('jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}"  type="text/css"/>
@endpush

@section('content')
	<div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Gig  Owner Management </h1>
		</div>
		<ol class="breadcrumb">
			<li><a href="{{route('master.dashboard')}}"><i class="demo-pli-home"></i></a></li>
			<li><a href="{{route('master.members.employers')}}"> Gig  Owners  Management </a></li>  
			<li class="active"> Gig  Owner Profile </li>
		</ol>
	</div>  
	<div id="page-content"> 
		@if(Session::has('success'))
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
				{!! session('success') !!}
			</div>
		@endif
		<div class = "row">
			<div class="col-sm-12">
				<div class="row">  
					<div class="col-md-3"> 
						<div class="panel pos-rel">
							<div class="pad-all text-center"> 
								<a href="#">
									<img alt="Profile Picture" class="img-lg img-circle mar-ver" src="{{$user->getImage()}}">
									<p class="text-lg text-semibold mar-no text-main">{{$user->accounts->name}}</p> 
									<p class=" mar-top-5">{{$user->email}}</p> 
									<p class=" mar-top-5">{{$user->cphonenumber}}</p> 
									<p class=" mar-top-5">{{$user->accounts->headline}}</p>                 
								</a>  
							</div>
						</div> 
					</div> 
					<div class="col-md-9">
						<div class="panel panel-primary mar-btm">  
							<div class="panel-heading">
								<div class="panel-control">
									<em class="text-light"> 
										@if($user->profile_pic_verified_status == '2')
											<small> 
												<img class = "checkbox-img" src = "{!! asset('img/checkbox.png') !!}" />
											</small> 
										@elseif($user->profile_pic_verified_status == '3')
											<small> (Rejected) </small>
										@elseif($user->profile_pic_verified_status == '1')
											<small class = "text-bold"> (Approved Requiring) </small>
										@else
											<small> (Not Approved) </small>
										@endif 
									</em>
								</div>
								<h3 class="panel-title">Profile Picture Verification</h3>
							</div>   
							<div class="panel-body"> 
								@if($user->accounts->profilepic != NULL) 
									<div class="row">
										<div class = "col-md-12 text-center">  
											@if( ($user->profile_pic_verified_status != '0')  && ($user->profile_pic_verified_status != '2') ) 
												<a class="btn btn-primary profilepic_Approve" href="#" title="Approve address">
													Approve  
												</a>
											@endif 
											@if( ($user->profile_pic_verified_status != '0') && ($user->profile_pic_verified_status != '3'))
												<a class="btn btn-danger profilepic_Reject" href="#" title="Approve address">
													Reject
												</a>
											@endif 
										</div>
									</div>
								@endif 
								<div class = "row">
									<div class = "col-md-12"> 
										<div class="tab-base mar-top" style = "border: 1px solid;">  
											<ul class="nav nav-tabs">
												<li class="active">
													<a data-toggle="tab" href="#picture-lft-tab-1">
														New Documents 
														@if(count($picverification) > 0)
															<span class="badge badge-purple">{!! count($picverification)  !!}</span>
														@endif
													</a>
												</li>
												<li>
													<a data-toggle="tab" href="#picture-lft-tab-2">
														Rejected Documents
														@if(count($picrverification) > 0)
															<span class="badge badge-purple">{!! count($picrverification)  !!}</span>
														@endif
													</a>
												</li>
												<li>
													<a data-toggle="tab" href="#picture-lft-tab-3">
														Approved Documents 
														@if(count($picaverification) > 0)
															<span class="badge badge-purple">{!! count($picaverification)  !!}</span>
														@endif
													</a>
												</li> 
											</ul> 
											<div class="tab-content">
												<div id="picture-lft-tab-1" class="tab-pane fade active in"> 
													@if(count($picverification) > 0)
														<div class="row"> 
															@foreach($picverification as $document)
																<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;"> 
																	<img src="{{ $document->getPath()  }}" class="card-img-top" style="max-height:150px; max-width: 100%; min-height:150px;"> 
																	<div class="card-body mar-top">
																		<p  class = "mar-top">{!! nl2br($document->note) !!}</p>
																		<a href="{{  $document->getPath()  }}" class="btn btn-warning" target="_blank">
																			<i class = "fa fa-eye"></i>
																		</a>
																		<a class="btn btn-primary noteModal" href="javascript:void(0)"   data-id = "{!! $document->id !!}">
																			<i class = "fa fa-pencil"></i>
																		</a>
																	</div>
																</div> 
															@endforeach
														</div>
													@else
														<div class="row">
															<div class = "col-md-12">
																<p class = "mar-top"> There are no new documents  </p>
															</div>
														</div>
													@endif 
												</div>
												<div id="picture-lft-tab-2" class="tab-pane fade">
													@if(count($picrverification) > 0) 
														<div class="row"> 
															@foreach($picrverification as $document)
																<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;"> 
																	<img src="{{ $document->getPath()  }}" class="card-img-top" style="max-height:150px; max-width: 100%; min-height:150px;"> 
																	<div class="card-body mar-top">
																		<p class = "mar-top">{!! nl2br($document->note) !!}</p>
																		<a href="{{  $document->getPath()  }}" class="btn btn-warning" target="_blank">
																			<i class = "fa fa-eye"></i>
																		</a>
																		<a class="btn btn-primary noteModal" href="javascript:void(0)"   data-id = "{!! $document->id !!}">
																			<i class = "fa fa-pencil"></i>
																		</a>
																	</div>
																</div>
															@endforeach
														</div>
													@else
														<div class="row">
															<div class = "col-md-12">
																<p class = "mar-top"> There are no rejected documents  </p>
															</div>
														</div>
													@endif
												</div>
												<div id="picture-lft-tab-3" class="tab-pane fade">
													@if(count($picaverification) > 0)
														<div class="row"> 
															@foreach($picaverification as $document)
																<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;"> 
																	<img src="{{ $document->getPath()  }}" class="card-img-top" style="max-height:150px; max-width: 100%; min-height:150px;"> 
																	<div class="card-body mar-top">
																		<p class = "mar-top">{!! nl2br($document->note) !!}</p>
																		<a href="{{  $document->getPath()  }}" class="btn btn-warning" target="_blank">
																			<i class = "fa fa-eye"></i>
																		</a>
																		<a class="btn btn-primary noteModal" href="javascript:void(0)"   data-id = "{!! $document->id !!}">
																			<i class = "fa fa-pencil"></i>
																		</a>
																	</div>
																</div>
															@endforeach
														</div>
													@else
														<div class="row">
															<div class = "col-md-12">
																<p class = "mar-top">  There are no approved documents  </p>
															</div>
														</div>
													@endif 
												</div>
											</div>
										</div> 
									</div>
								</div> 
							</div>
						</div>  
					</div>  
				</div>
			</div>
		</div>
	</div>
	
	 
@endsection
@section('javascript')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/typeahead.bundle.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.js?'.time())}}"></script>
<script>
$(document).ready( function (){
 @foreach($picverification as $document)
	$( "#noteForm{{$document->id}}" ).validate({
		rules: {
			note: "required",
		},
		messages: {
			note: "notes field is required"
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
							var input = '#noteForm{{$document->id}} #'+key;
							$(input).parent().addClass('has-error');
							$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
							$('#noteFormresponce{{$document->id}}').addClass('hide');
						});
					}else{
						$(form).find("button[type=submit]").prop('disabled',false);
							swal({   
								   title: "Note",   
								   text: "Note has been updated successfully!",   
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
 @endforeach
});

	function profilepic_Approve($id) {
		swal({
			title: "Are you sure?",
			text: "You want to approve profile picture info!", 
			type: "warning",
			showCancelButton: !0,
			buttonsStyling: !1,
			confirmButtonClass: "btn btn-danger",
			confirmButtonText: "Yes, approve it!",
			cancelButtonClass: "btn btn-secondary"
		}).then(t=>{
			t.value &&  $.ajax({
				url: "{{route('master.members.employees.action')}}",
				type: "post",
				data: {
					id: $id,
					"_token": "{{ csrf_token() }}",
					"tag": "profilepic_info_Approve",
				},
				success: function () {
					$(".personal_info_status").html('<span class="badge badge-dot mr-2"><i class="bg-success"></i><span class="status">Approved</span></span><a href="#!" class="table-action table-action-default" data-toggle="tooltip" data-original-title="Congratulations! approved." data-placement="right"><i class="fas fa-info-circle"></i></a>');
					swal({
						title: "Approved!", 
						text: "Profile picture info has been approved successfully.",
						type: "success",
						buttonsStyling: !1,
						confirmButtonClass: "btn btn-primary"
					}).then(function(isConfirm) {
						if(isConfirm){
							location.reload();
						} 
					});
				},
				error: function (xhr, ajaxOptions, thrownError) {
					swal("Error approving!", "Please try again", "error");
				}
			});
		});  
	} 
	function profilepic_Reject($id) {
		swal({
			title: "Are you sure?",
			text: "You want to reject profile picture info!", 
			type: "warning",
			showCancelButton: !0,
			buttonsStyling: !1,
			confirmButtonClass: "btn btn-danger",
			confirmButtonText: "Yes, reject it!",
			cancelButtonClass: "btn btn-secondary"
		}).then(t=>{
			t.value &&  $.ajax({
				url: "{{route('master.members.employees.action')}}",
				type: "post",
				data: {
					id: $id,
					"_token": "{{ csrf_token() }}",
					"tag": "profilepic_info_Reject",
				},
				success: function () {
					$(".personal_info_status").html('<span class="badge badge-dot mr-2"><i class="bg-success"></i><span class="status">Approved</span></span><a href="#!" class="table-action table-action-default" data-toggle="tooltip" data-original-title="Congratulations! approved." data-placement="right"><i class="fas fa-info-circle"></i></a>');
					swal({
						title: "Rejected!", 
						text: "Profile picture info has been rejected successfully.",
						type: "success",
						buttonsStyling: !1,
						confirmButtonClass: "btn btn-primary"
					}).then(function(isConfirm) {
						if(isConfirm){
							location.reload();
						} 
					});
				},
				error: function (xhr, ajaxOptions, thrownError) {
					swal("Error rejecting!", "Please try again", "error");
				}
			});
		});  
	}
</script>	
@stop