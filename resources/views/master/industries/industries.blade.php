@extends('layouts.master')
@section('title','Gig Type Management')
@push('css')
	<link href="{{ asset('plugins/jstree/themes/default/style.min.css') }}" rel="stylesheet">
@endpush
@section('content')
	<div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Gig Type Management </h1>
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
				<div class = "panel">
					<div class = "panel-body">
						<div class = "row">
							<div class="col-sm-10 col-sm-offset-2">
								<div id="jstree-industry">
									<ul>
										@foreach($industries as $industry)
											<li class="jstree-open clickable" data-type = "node"  data-action = "edit" data-id = "{{ $industry->serial  }}">
												{!! $industry->industry !!}
												@php
													$subcategories =  $industry->subcategories;  
												@endphp 
												<ul>
													<li data-jstree='{"type":"html"}' data-industry = "{!! $industry->industry !!}"  data-type = "subcategory" data-action = "add" data-category = "{{ $industry->serial   }}">Add</li>
													@foreach($subcategories as $subcategory)
														<li data-jstree='{"type":"file"}' data-type = "subcategory" data-action = "edit"  data-id = "{{ $subcategory->serial  }}">
															{{ $subcategory->name }}
														</li>
													@endforeach
												</ul>
											</li>
										@endforeach
										<li data-jstree='{"type":"html"}' data-type = "node"  data-action = "add">Add New</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>  
			</div>
		</div>  
	</div>  
	<!-- add Skills -->
	<div class="modal fade" id="addIndustryModal" tabindex="-1" role="dialog" aria-labelledby="addIndustryModal" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">  
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
					<h4 class="modal-title">   Add new Gig Type  </h4>
				</div> 
				<div class="modal-body"> 
					<form id="addIndustryForm" method="post" class="form-horizontal" action="{{{ route('master.industries.add') }}}">
						@csrf
						<div class="row">
							<div class="form-group col-lg-12">
								<label class="col-sm-12 control-label text-left" for="industry"> Gig Type </label>
								<div class="col-sm-12  @if ($errors->has('industry')) has-error @endif">
									<input type="text" class="form-control" id = "industry" name = "industry" placeholder ="" value="{{old('industry','')}}"/>
									<em id="industry-error" class="error help-block hide">
									</em>
								</div>
							</div>
						</div>
						<div class="row my-lg-3">
							<div class="form-group col-lg-12">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-primary"   name = "save" >Add</button>
									<button type="button" class="btn btn-secondary" data-dismiss = "modal">Close</button>
								</div>
							</div>
						</div>	
					</form>	 
				</div>
			</div>
		</div>
	</div> 
	<!-- edit -->
	<div class="modal fade" id="editindustrysm" role="dialog"   aria-labelledby="editindustrysm" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
					<h4 class="modal-title">   Gig Type - <span class="text-bold" id="editindustrysm-title"></span>  </h4>
				</div> 
				<div class="modal-body"  id = "editindustrysmBody"> 
				</div>   
			</div>
		</div>
	</div> 

	<!-- Add Category -->
    <div class="modal fade" id="addSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addSubCategoryModal" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">  
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
					<h4 class="modal-title">   Add New Sub Category in   </h4>
				</div> 
				<div class="modal-body"> 
					<form id="addSubCategoryForm" method="post" class="form-horizontal" action="">
						@csrf
						<div class="row">
							<div class="form-group col-lg-12">
								<label class="col-sm-12 control-label text-left" for="subcategory"> Sub  Category </label>
								<div class="col-sm-12">
									<input type="text" class="form-control" id = "subcategory" name = "subcategory" placeholder ="" value = ""/>
									<em id="subcategory-error" class="error help-block hide">
									</em>
								</div>
							</div>
						</div>
						<div class="row my-lg-3">
							<div class="form-group col-lg-12">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-primary"   name = "save" >Add</button>
									<button type="button" class="btn btn-secondary" data-dismiss = "modal">Close</button>
								</div>
							</div>
						</div>	
					</form>	 
				</div>
			</div>
		</div>
	</div>  
    <!-- edit -->
	<div class="modal fade" id = "editSubCategoryModal" role="dialog"   aria-labelledby = "editSubCategoryModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
					<h4 class="modal-title">   Sub Category - <span class="text-bold" id="editSubCategoryModal-title"></span>  </h4>
				</div> 
				<div class="modal-body"  id = "editSubCategoryModalBody"> 
				</div>   
			</div>
		</div>
	</div> 
@endsection
@push('javascript')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/typeahead.bundle.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.js?'.time())}}"></script>
<script src="{{ asset('plugins/jstree/jstree.min.js') }}"></script>
<script>
	/******************************************* Category *********************************************/ 
	$(document).ready( function (){
		$( "#addIndustryForm" ).validate({
			rules: {
				industry: "required",
			},
			messages: {
				industry: "Please enter industry name",
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
								var input = '#addIndustryForm input[name=' + key + ']';
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
								$('#addIndustryFormresponce').addClass('hide');
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							swal({   
								title: "Job Type",   
								text: "Job Type has been added successfully!",   
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
	function editindustry($id){
		$.ajax({
			url: "{{route('master.industries.edit')}}",
			type: "post",
			data: {
				id: $id,
				"_token": "{{ csrf_token() }}",
				},
				success: function(response) {
					$("#editindustrysm-title").html(response.title);
					$("#editindustrysmBody").html(response.viewdata);
				}
		});
		$('#editindustrysm').modal(); 
	}
	$(document).on("click", ".editindustry", function(){
		editindustry($(this).attr('data-id')); 
	}); 
	$('#editindustrysmBody').on('click', '.submitButton', function(){  
		$("#editindustryForm").validate({
				rules: {
					industry: "required",
				},
				messages: {
					industry: "Please enter industry name",
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
									var input = '#editindustryForm input[name=' + key + ']';
									$(input).parent().addClass('has-error');
									$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
									$('#editindustryFormresponce').addClass('hide');
								});
							}else{
								$(form).find("button[type=submit]").prop('disabled',false);
								swal({   
									title: "Job Type",   
									text: "Job Type has been updated successfully!",   
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
	/*********************************************** Sub Category **********************************************/
	$(".addnew_category").click(function(){
        $('#addSubCategoryForm')[0].reset();
        $("#addSubCategoryModal").modal("show");
    }); 
    $( "#addSubCategoryForm" ).validate({
        rules: {
            subcategory: "required",
        },
        messages: {
            subcategory: "Please enter sub category name",
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
                            var input = '#addSubCategoryForm input[name=' + key + ']';
                            $(input).parent().addClass('has-error');
                            $('#'+key+'-error').removeClass('hide').addClass('show').text(value); 
                        });
                    }else{
                        $(form).find("button[type=submit]").prop('disabled',false);
                        swal({   
                            title: "Sub Category",   
                            text: "Sub Category has been added successfully!",   
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
    function editSubCategory($id){ 
		$.ajax({
			url: "{{route('master.industries.subcategories.edit')}}",
			type: "post",
			data: {
				id: $id,
				"_token": "{{ csrf_token() }}",
				},
				success: function(response) {
					$("#editSubCategoryModal-title").html(response.title);
					$("#editSubCategoryModalBody").html(response.viewdata);
					$('#editSubCategoryModal').modal("show"); 
				}
		}); 
	} 
	$(document).on("click", ".editsubcategory", function(){
		editSubCategory($(this).attr('data-id'));  
	});
    $('#editSubCategoryModal').on('click', '.submitButton', function(){ 
		$("#editsubcategoryForm").validate({
				rules: {
					subcategory: "required",
				},
				messages: {
					subcategory: "Please enter sub category",
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
									var input = '#editsubcategoryForm input[name=' + key + ']';
									$(input).parent().addClass('has-error');
									$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
									//$('#editsubcategoryForm').addClass('hide');
								});
							}else{
								$(form).find("button[type=submit]").prop('disabled',false);
								swal({   
									title: "Sub Category",   
									text: "Sub Category has been updated successfully!",   
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
	/********************************************  JS TREE **********************************************/
	$('#jstree-industry').jstree({
        'core' : {
            'check_callback' : true
        },
        'plugins' : [ 'types', 'dnd' ],
        'dnd' : {
            "is_draggable": function (node) {
                return false;  // flip switch here.
            }
        },
        'types' : {
            'default' : {
                'icon' : 'demo-pli-folder',
                'draggable' : false,
            },
            'html' : {
                'icon' : 'fa fa-plus-circle',
                'draggable' : false
            },
            'file' : {
                'icon' : 'demo-pli-file',
                'draggable' : false
            },
            'jpg' : {
                'icon' : 'demo-pli-file-jpg',
                'draggable' : false
            },
            'zip' : {
                'icon'      : 'demo-pli-file-zip',
                'draggable' : false
            }
        }
    });
	var selected_node_id = "";
	$('#jstree-industry').on("select_node.jstree", function (e, data) { 
        selected_node_id 	= 	data.node.id;
		var data_type  		= 	$("#" + selected_node_id).attr('data-type');
		var data_action 	=	$("#" + selected_node_id).attr('data-action'); 
		if(data_type == "node"){
			if(data_action == "add"){
				$('#addIndustryForm')[0].reset();
				$("#addIndustryModal").modal("show");
			}
			if(data_action == "edit"){
				editindustry( $("#" + selected_node_id).attr('data-id')   );  
			}
		} 
		if(data_type == "subcategory"){
			if(data_action == "add"){
				var url = "{{  route('master.industries.subcategories.add',  ':request_type') }}";  
        		url     =  url.replace(':request_type',  $("#" + selected_node_id).attr('data-category'));

				$("#addSubCategoryModal .modal-title").html( "Add New Sub Category in " + $("#" + selected_node_id).attr('data-industry')); 
				$('#addSubCategoryForm')[0].reset();
				$('#addSubCategoryForm').attr('action', url); 
				$("#addSubCategoryModal").modal("show");
			}
			if(data_action == "edit"){
				editSubCategory($("#" + selected_node_id).attr('data-id'));  
			}
		} 
    }); 
</script>
@endpush