@extends('layouts.master')
@section('title','Employee Management')
@section('css')
 
@endsection
@section('content')
	<div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Employees Management </h1>
		</div>
		<ol class="breadcrumb">
			<li><a href="{{route('master.dashboard')}}"><i class="demo-pli-home"></i></a></li>  
			<li class="active"> Employees Management </li>
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
                                            <th>Message</th>
                                            <th> Status </th>
											<th>Date</th> 
											<th>Action</th>
										</tr>
									</thead>
									<tbody>  
										@forelse($notifications as $notification)
											<tr>
												<td>{!! $notification->name !!}</td>
                                                <td><a href = "{!! $notification->generateURL() !!}" >{!! $notification->notifications_value !!} </a></td>
                                                <td>
                                                    @if($notification->notifications_readby) 
                                                        <span class="label label-primary"> Read </span>
                                                    @else
                                                        <span class="label label-danger"> Unread </span>
                                                    @endif
                                                </td> 
												<td>  @if($notification->created_at) {!! $notification->created_at->format('m/d/Y') !!} @endif</td>
												<td>  
													@if($notification->notifications_readby == 0)
                                                   		<a href="{!! route('master.notifications.markasread',  $notification->notifications_serial ) !!}"    class="btn btn-success margin-5"><i class = "fa fa-check"></i></a>  
													@endif
												</td>
											</tr>
										@empty
											<tr>       
												<td colspan = "10" class = "text-center"> There are  no notifications.  </td>
											</tr>
										@endforelse
									</tbody>
								</table> 
							</div>  
							<div class = "text-right">   
								{{$notifications->links()}} 
							</div>  
						</div> 
					</div>
				</div>
			</div>
		</div> 
	</div>
@endsection
@push('javascript')

<script>
	 
</script>
@endpush