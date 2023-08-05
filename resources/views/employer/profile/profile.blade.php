@extends('layouts.app')
@section('title', 'Profile')
@section('css')
<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
		@if($user->accounts->profilepic != NULL && $user->profile_pic_verified_status == 0)	
			<div class="col-md-12 alert alert-warning alert-block">	
				<button title="Verify profile picture" type="button" class="close" data-toggle="modal" data-target="#verifyprofilepicModal">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" data-supported-dps="24x24" fill="currentColor" width="24" height="24" focusable="false">
					  <path d="M21.71 5L19 2.29a1 1 0 00-.71-.29 1 1 0 00-.7.29L4 15.85 2 22l6.15-2L21.71 6.45a1 1 0 00.29-.74 1 1 0 00-.29-.71zM6.87 18.64l-1.5-1.5L15.92 6.57l1.5 1.5zM18.09 7.41l-1.5-1.5 1.67-1.67 1.5 1.5z"></path>
					</svg>
				</button>	
				<strong>Verify your profile picture.</strong>
			</div>
		@endif 

		<div class = "col-md-3">
			@include('general.partial.settings_leftsidebar') 
		</div>
		<div class="col-md-9">  
			<div class = "col-sm-12"> 
				<div class="card">
					<div class = "card-header bg-light">
						<h4 class="text-dark">Account</h4>
					</div>
					<div class = "card-body">
						<div class="pad-all clearfix"> 
							<div class = "row">
								<div class  = "col-md-3 text-center "> 
									<a href="#" title="Edit address" data-toggle="modal" data-target="#profilepicModal">
										<img alt="Profile Picture" class="img-lg img-circle" src="{{$user->getImage()}}">  
									</a>
								</div>
								<div class  = "col-md-6 text-dark">
									<p class="text-lg text-semibold mar-no text-main">
										{!! Auth::user()->accounts->name !!}
										<a href ="javascript:void(0)"  data-toggle="modal" data-target="#miscModal" class="btn btn-link" > <i class="fa fa-pencil"></i> </a> 
									</p>
									<p class=" mar-top-5">{{$user->email}}</p> 
									<p class=" mar-top-5">{{$user->cphonenumber}}</p> 
									@if($user->accounts->headline)
									<p class=" mar-top-5">{{$user->accounts->headline}}</p>  
									@endif
								</div>
							</div>
						</div> 
					</div> 
				</div> 	 
			</div> 
			  
			<div class="col-sm-12 mar-top">
				<div class="card">
					<div class="card-header bg-light">
						<h4 class="text-dark">
							Address
							<button title="Edit address" type="button" class="close pull-right" data-toggle="modal" data-target="#addressModal">
								<i class = "fa fa-pencil text-2x"></i>
							</button>
						</h4>
					</div>
					<div class="card-body">
						@if($user->accounts->caddress == NULL && $user->accounts->city == NULL && $user->accounts->state == NULL && $user->accounts->zip == NULL && $user->accounts->oaddress == NULL)
							<div class="alert alert-primary" role="alert">
							First <a href="#" data-toggle="modal" data-target="#addressModal" class="alert-link">add</a> your address.
							</div>
						@else
							@if($user->accounts->caddress != NULL)<p><b class="col-lg-3 text-dark">Address:</b>{{$user->accounts->caddress}}</p>@endif
							@if($user->accounts->city != NULL)<p><b class="col-lg-3 text-dark">City:</b>{{$user->accounts->city}}</p>@endif
							@if($user->accounts->state != NULL)<p><b class="col-lg-3 text-dark">State:</b>{{$user->accounts->state}}</p>@endif
							@if($user->accounts->zip != NULL)<p><b class="col-lg-3 text-dark">Zip:</b>{{$user->accounts->zip}}</p>@endif
							@if($user->accounts->oaddress != NULL)<p><b class="col-lg-3 text-dark">Address 2:</b>{{$user->accounts->oaddress}}</p>@endif
						@endif
					</div>
				</div>
				<br>
			</div>
		</div>
    </div>
</div> 
<!-- address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">Edit  address </h4>
            </div> 
			<div class="modal-body">
				@include('employer.profile.address')
			</div>
		</div>
	</div>
</div> 
<!-- address Modal -->
<!-- Profile Modal -->
<div class="modal fade" id="profilepicModal" tabindex="-1" role="dialog" aria-labelledby="profilepicModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Profile Picture</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employer.profile.profilepic')
			</div>
    	</div>
  	</div>
</div>
<!-- Profile Modal -->
<!-- Misc Modal -->
<div class="modal fade" id="miscModal" tabindex="-1" role="dialog" aria-labelledby="miscModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Edit intro</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employer.profile.misc')
			</div>
		</div>
  	</div>
</div>
<!-- Misc Modal -->
<!-- Verify profile picture Modal -->
<div class="modal fade" id="verifyprofilepicModal" tabindex="-1" role="dialog" aria-labelledby="verifyprofilepicModallLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="verifyprofilepicModalLabel">Verify Profile picture</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employer.profile.verifyprofilepic')
			</div>
		</div>
  	</div>
</div>
<!-- Verify profile picture Modal -->
@endsection
@section('javascript')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/typeahead.bundle.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2Lze4Darf903qJrfbrjAqyMhJGqvel0A&libraries=places" ></script>
<script src="{{ asset('js/jquery.geocomplete.js') }}"></script>
<script> 
	$(document).ready( function (){
		/*
		$("#caddress").trigger("geocode"); 
		$("#caddress").geocomplete({
			details: "form",
			types: ["geocode", "establishment"],
		}); 
		*/
		$( "#addressForm" ).validate({
			rules: {
				city: "required",
				state: "required",
				zip: "required",
				caddress: "required",
			},
			messages: {
				city: "Please enter your City",
				state: "Please enter your State",
				zip: "Please enter your Zip",
				caddress: "Please enter your address"
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
								var input = '#addressForm input[name=' + key + ']';
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
								$('#responce').addClass('hide');
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							swal({   
								title: "Address details",   
								text: "Your address details has been updated successfully!",   
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
		$( "#miscForm" ).validate({
			rules: {
				headline: "required",
				birthdate: "required",
				industry: "required",
				socialsecuritynumber: "required",
			},
			messages: {
				headline: "Please enter your headline",
				birthdate: "Please enter your birthdate",
				industry: "Please select your industry",
				socialsecuritynumber: "Please enter your social security number",
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
								var input = '#miscForm input[name=' + key + ']';
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
								//$('#miscFormresponce').addClass('hide');
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							swal({   
								title: "Profile details",   
								text: "Your Profile details has been updated successfully!",   
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
	
	var uploadedDocumentMap = {};
	Dropzone.options.avtarDropzone = {
		url: '{{{ route("employer.media.store") }}}',
		maxFiles: 1,
		maxFilesize: 5, // MB
		addRemoveLinks: true,
		acceptedMimeTypes: "image/jpeg,image/jpg,image/png",
		headers: {
		  'X-CSRF-TOKEN': "{{ csrf_token() }}"
		},
		autoProcessQueue: false,
		previewTemplate: "<div class=\"dz-preview dddd dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail class=\"rounded-circle\"/></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>",
		sending: function(file, xhr, formData){
				formData.append('user_id',{{$user->id}});
				formData.append('tag',"avtar");
		},
		success: function (file, response) {
			$('#profilepicModal').modal('hide'); 
			swal({
				title: "Your avatar",   
				text: "The avatar has been uploaded successfully!",   
				type: "success",   
				confirmButtonText: "Close" 
				}).then(function(isConfirm) {
				if(isConfirm){
					location.reload();
				}
			});		 
		},
		removedfile: function (file) {
			$.ajax({
				url: '{{{ route("employer.media.delete") }}}',
				type: "post",
				data: {
					id: file.id,
					tag:'removeAvtar',
					'user_id':{{$user->id}},
					"_token": "{{ csrf_token() }}",
				}
			});

		  	file.previewElement.remove();
		  	var name = '';
		  	if (typeof file.file_name !== 'undefined') {
				name = file.file_name; 
		  	} else {
				name = uploadedDocumentMap[file.name];
		 	}
		 
		//$("img.avtar-img").attr('src',"{{ asset('/template/img/dashboardph/placeholderAvatar.jpg')}}");
		},
		init: function () {
			var myDropzone = this,
			submitButton = document.querySelector("#avtar-submit"); 
			submitButton.addEventListener('click', function(e) {
				e.preventDefault();
			var file_count =myDropzone.getAcceptedFiles().length;
				if(file_count<1){
					$('.image-upload-error').css('display','block').text('Please upload your avatar.');
						return false;
					} 
				myDropzone.processQueue();
			}); 
		}
	}

	var uploadedDocumentMap5 = {}
	Dropzone.options.profilepicDropzone = {
		url: '{{{ route("employer.media.store") }}}',
		maxFilesize: 2, // MB
		addRemoveLinks: true,
		acceptedMimeTypes: "image/jpeg,image/jpg,image/png,.pdf",
		uploadMultiple: true,
		parallelUploads :3,
		headers: {
		'X-CSRF-TOKEN': "{{ csrf_token() }}"
		},
		autoProcessQueue: false,
		previewTemplate: "<div class=\"dz-preview dddd dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail class=\"rounded-circle\"/></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>",
		
		sending: function(file, xhr, formData)
			{
				formData.append('user_id',{{$user->id}});
				formData.append('tag',"picture");
			},
		success: function (file, response) {
				swal({   
						title: "Your document",   
						text: "Your document to verify profile picture has been uploaded successfully!",   
						type: "success",   
						confirmButtonText: "Close" 
						}).then(function(isConfirm) {
						if(isConfirm){
							location.reload();
						} 
					});
				$('#verifyprofilepicModal').modal('hide');
			
				uploadedDocumentMap5[file.name] = response.name;
			},
		error: function(file, errorMessage) {
			errors = true;
		},
		removedfile: function (file) {
			$.ajax({
				url: '{{{ route("employer.media.delete") }}}',
				type: "post",
				data: {
					id: file.id,
					tag:'removeprofilepic',
					'user_id':{{$user->id}},
					"_token": "{{ csrf_token() }}",
			}
			});
		file.previewElement.remove();
		var name = '';
		if (typeof file.file_name !== 'undefined') {
			name = file.file_name; 
		} else {
			name = uploadedDocumentMap5[file.name];
		}
		},
		init: function () {
			var myDropzone = this,
			submitButton = document.querySelector("#profilepic-submit");

		submitButton.addEventListener('click', function(e) {
			e.preventDefault();
		var file_count =myDropzone.getAcceptedFiles().length;
			if(file_count<1){
				$('.image-upload-error-profilepic').css('display','block').text('Please upload your document as per above instructions max filesize is 2 MB.');
					return false;
				}
			
			myDropzone.processQueue();
		});
		
		}
	} 
</script>	
@stop