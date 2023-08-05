@extends('layouts.master')
@section('title','Gig Owner Management')
@section('css')
<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}"  type="text/css"/>
@endsection
@section('content')

	<div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Gig Owners Management </h1>
		</div>
		<ol class="breadcrumb">
			<li><a href="{{route('master.dashboard')}}"><i class="demo-pli-home"></i></a></li>  
			<li class="active">  Gig Owners Management </li>
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
							<div class="table-responsive">
								<table class="table table-striped table-hover table-vcenter">
									<thead>
										<tr class="bg-teal">
											<th>Name</th>
											<th>E-mail</th>
											<th>Need to Verify</th>
											<th>Created</th>
											<th>Action</th>
										</tr>
									</thead> 
									<tbody>
										@forelse($employers as $employer)
											<tr>
												<td>{!! $employer->name !!}</td>
												<td>{!! $employer->email !!}</td>
												<td>								
													@if($employer->profile_pic_verified_status == 1) 
														YES
													@else
														No
													@endif 
												</td>
												<td>  @if($employer->created_at) {!! $employer->created_at->format('m/d/Y') !!} @endif</td>
												<td> 
													<a href="{!! route('master.members.employer.show', ['id' =>$employer->id]) !!}"    class="btn btn-success margin-5"   >Edit</a>  
												</td>
											</tr>
										@empty
											<tr>       
												<td colspan = "10" class = "text-center"> There are  no Gig Owners.  </td>
											</tr>
										@endforelse	
									</tbody>
								</table>
							</div>
							<div class = "text-right">   
								{{$employers->links()}} 
							</div> 
						</div>
					</div>
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

<script src="{!!asset('jquery-datatable/extensions/export/dataTables.buttons.min.js')!!}"></script>
<script src="{!!asset('jquery-datatable/extensions/export/buttons.flash.min.js')!!}"></script>
<script src="{!!asset('jquery-datatable/extensions/export/jszip.min.js')!!}"></script>
<script src="{!!asset('jquery-datatable/extensions/export/pdfmake.min.js')!!}"></script>
<script src="{!!asset('jquery-datatable/extensions/export/vfs_fonts.js')!!}"></script>
<script src="{!!asset('jquery-datatable/extensions/export/buttons.html5.min.js')!!}"></script>
<script src="{!!asset('jquery-datatable/extensions/export/buttons.print.min.js')!!}"></script> 
<script>
	
</script>
@endpush