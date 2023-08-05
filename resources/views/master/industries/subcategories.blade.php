@extends('layouts.master')
@section('title','Sub Category Management')
@section('css') 
@endsection 
@section('content')
    <div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Sub Category Management </h1>
		</div>

        <ol class="breadcrumb">
            <li><a href="{{route('master.dashboard')}}"><i class="demo-pli-home"></i></a></li>  
			<li><a href="{{route('master.industries')}}">  Gig Types </a></li> 
			<li class="active">  {!! $industry->industry !!}   </li>
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
								<a href = "#"  title="Add New Category" type="button" class="btn btn-primary pull-right addnew_category">					
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
										@forelse($subcategoreis as $subcategory)
											<tr>
												<td> {!! $subcategory->name !!}</td> 
												<td> @if($subcategory->created_at) {!! $subcategory->created_at->format('m/d/Y') !!}  @endif</td>
												<td>
													<a href="javascript:void(0)" class="btn btn-success margin-5 editsubcategory" data-id = "{!! $subcategory->serial  !!}">
														Edit
													</a> 
													<a href="javascript:void(0)" class="btn btn-danger margin-5 deleteaction"   data-string = "Do you want to remove this item?"   data-url = "{!! route('master.industries.subcategories.delete', [ $industry->serial, $subcategory->serial ]) !!}"   data-title="Delete Job Type">
														Delete
													</a>
												</td>
											</tr>
										@empty
											<tr>       
												<td colspan = "10" class = "text-center"> There are  no sub category.  </td>
											</tr>
										@endforelse

									</tbody>
								</table> 
							</div>  
							<div class = "text-right">   
								{{$subcategoreis->links()}} 
							</div> 
						</div>    

                    </div>
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
					<h4 class="modal-title">   Add New Sub Category in  {!! $industry->industry !!}  </h4>
				</div> 
				<div class="modal-body"> 
					<form id="addSubCategoryForm" method="post" class="form-horizontal" action="{{{ route('master.industries.subcategories.add', $industry->serial) }}}">
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
<script>
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
				url: "{{route('master.industries.subcategories.edit', $industry->serial)}}",
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
</script>
@endpush