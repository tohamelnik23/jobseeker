@extends('layouts.master')
@section('title','Gig  Doer Profile | ' . $user->accounts->name)
@push('css')
<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}"  type="text/css"/>
<style>
	.tab-pane{
		min-height: 150px;
	}
</style>
@endpush
@section('content') 
	<div id="page-head">
		<div id="page-title">
			<h1 class="page-header text-overflow"> Gig  Doer Management </h1>
		</div>
		<ol class="breadcrumb">
			<li><a href="{{route('master.dashboard')}}"><i class="demo-pli-home"></i></a></li>
			<li><a href="{{route('master.members.employees')}}"> Gig  Doers  Management </a></li>  
			<li class="active"> Gig  Doer Profile </li>
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
					<div class = "col-sm-3"> 
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
						<div class="panel freelancer_card_panel" style = "background-color: inherit;"> 
							<div class="panel-heading bord-btm">
								<div class="panel-control pull-left">
									<ul class="nav nav-tabs">
										<li class = "active ">
											<a href = "#"   class = "text-uppercase"> Verification  </a>
										</li>
										<li class = "">
											<a  href = "{!!  route('master.members.loans', $user->id) !!}"  class = "text-uppercase"> Advances </a>
										</li> 
									</ul>
								</div>
							</div>
							<div class="panel-body pad-no"> 
							</div>
						</div>
						<div class="panel panel-primary mar-btm"> 
							<!--Panel heading-->
							<div class="panel-heading">
								<div class="panel-control">
									<em class="text-light"> 
										@if($user->address_verified_status == '2')
											<small>
												<img class = "checkbox-img" src = "{!! asset('img/checkbox.png') !!}" />
											</small> 
										@elseif($user->address_verified_status == '3')
											<small> (Rejected) </small>
										@elseif($user->address_verified_status == '1')
											<small class = "text-bold"> (Approved Requiring) </small>
										@else
											<small> (Not Approved) </small>
										@endif 
									</em>
								</div>
								<h3 class="panel-title">Address</h3>
							</div> 
							<!--Panel body-->
							<div class="panel-body">
								@if ($user->accounts->caddress == NULL)
									<p class = "text-sm"> <i> No Address is added yet  </i> </p>
								@else
									@if($user->accounts->caddress != NULL)<p><b class="col-lg-3">Address:</b>{{$user->accounts->caddress}}</p>@endif
									@if($user->accounts->oaddress != NULL)<p><b class="col-lg-3">Address 2:</b>{{$user->accounts->oaddress}}</p>@endif 
									@if($user->accounts->city != NULL)<p><b class="col-lg-3">City:</b>{{$user->accounts->city}}</p>@endif
									@if($user->accounts->state != NULL)<p><b class="col-lg-3">State:</b>{{$user->accounts->state}}</p>@endif
									@if($user->accounts->zip != NULL)<p><b class="col-lg-3">Zip:</b>{{$user->accounts->zip}}</p> @endif  
									<div class="row">
										<div class = "col-md-12 text-center">

											@if (($user->address_verified_status != 0) &&  ($user->address_verified_status != '2'))
												<a class="btn btn-primary address_Approve" href="#" title="Approve address" >
													Approve
												</a>
											@endif
											
											@if(($user->address_verified_status != 0) &&  ($user->address_verified_status != '3'))
												<a class="btn btn-danger address_Reject" href="#" title="Approve address">
													Reject
												</a>
											@endif
											
										</div>
									</div>

									<div class = "row">
										<div class = "col-md-12"> 
											<div class="tab-base mar-top" style = "border: 1px solid;">  
												<ul class="nav nav-tabs">
													<li class="active">
														<a data-toggle="tab" href="#address-lft-tab-1">
															New Documents 
															@if(count($verification) > 0)
															<span class="badge badge-purple">{!! count($verification)  !!}</span>
															@endif
														</a>
													</li>
													<li>
														<a data-toggle="tab" href="#address-lft-tab-2">
															Rejected Documents
															@if(count($rverification) > 0)
																<span class="badge badge-purple">{!! count($rverification)  !!}</span>
															@endif
														</a>
													</li>
													<li>
														<a data-toggle="tab" href="#address-lft-tab-3">
															Approved Documents 
															@if(count($averification) > 0)
																<span class="badge badge-purple">{!! count($averification)  !!}</span>
															@endif
														</a>
													</li>
												</ul>
												<!--Tabs Content-->
												<div class="tab-content">
													<div id="address-lft-tab-1" class="tab-pane fade active in"> 
														@if(count($verification) > 0)
															<div class="row"> 
																@foreach($verification as $document)
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
													<div id="address-lft-tab-2" class="tab-pane fade">
														@if(count($rverification) > 0) 
															<div class="row"> 
																@foreach($rverification as $document)
																	<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;"> 
																		<img src="{{ $document->getPath()  }}" class="card-img-top" style="max-height:150px;  max-width: 100%;min-height:150px;"> 
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
													<div id="address-lft-tab-3" class="tab-pane fade">
														@if(count($averification) > 0)
															<div class="row"> 
																@foreach($averification as $document)
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
								@endif
							</div>
						</div> 						
						<!--div class="panel panel-primary mar-btm">  
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
						</div-->  
						@php
							$driver_license =  $user->getDriverLicense(); 
						@endphp 
						<div class="panel panel-primary mar-btm"> 
							<!--Panel heading-->
							<div class="panel-heading">
								<div class="panel-control">
									<em class="text-light"> 
										@if(isset($driver_license)) 
											@if($driver_license->verified == '2')
												<small>  <img class = "checkbox-img" src = "{!! asset('img/checkbox.png') !!}" />  </small> 
											@elseif($driver_license->verified == '3')
												<small> (Rejected) </small>
											@elseif($driver_license->verified == '1')
												<small class = "text-bold"> (Confirmed Requiring) </small>
											@else
												<small> (Not Confirmed) </small>
											@endif 
										@else
											<small> (Not Added) </small>
										@endif
									</em>
								</div>
								<h3 class="panel-title">Driver License Verification</h3>
							</div>  
							<!--Panel body-->
							<div class="panel-body"> 
								@if( isset($driver_license) ) 
									<div class="row">
										<div class = "col-md-12 text-center">  
											@if( ($driver_license->verified != '0')  && ($driver_license->verified != '2') ) 
												<a class="btn btn-primary driverlicense_Approve" href="#" title="Approve Driver License">
													Approve  
												</a>
											@endif 
											@if( ($driver_license->verified != '0') && ($driver_license->verified != '3'))
												<a class="btn btn-danger driverlicense_Reject" href="#" title="Reject DriverLicense">
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
													<a data-toggle="tab" href="#driver-lft-tab-1">
														New Documents 
														@if(count($driver_verification) > 0)
															<span class="badge badge-purple">{!! count($driver_verification)  !!}</span>
														@endif
													</a>
												</li>
												<li>
													<a data-toggle="tab" href="#driver-lft-tab-2">
														Rejected Documents
														@if(count($driver_rverification) > 0)
															<span class="badge badge-purple">{!! count($driver_rverification)  !!}</span>
														@endif
													</a>
												</li>
												<li>
													<a data-toggle="tab" href="#driver-lft-tab-3">
														Approved Documents 
														@if(count($driver_averification) > 0)
															<span class="badge badge-purple">{!! count($driver_averification)  !!}</span>
														@endif
													</a>
												</li> 
											</ul>
											<!--Tabs Content-->
											<div class="tab-content">
												<div id="driver-lft-tab-1" class="tab-pane fade active in"> 
													@if(count($driver_verification) > 0)
														<div class="row"> 
															@foreach($driver_verification as $document)
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
												<div id="driver-lft-tab-2" class="tab-pane fade">
													@if(count($driver_rverification) > 0) 
														<div class="row"> 
															@foreach($driver_rverification as $document)
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
												<div id="driver-lft-tab-3" class="tab-pane fade">
													@if(count($driver_averification) > 0)
														<div class="row"> 
															@foreach($driver_averification as $document)
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

						@php
							$bank_information =  $user->getBankInformation(); 
						@endphp  
						<div class="panel panel-primary mar-btm"> 
							<!--Panel heading-->
							<div class="panel-heading">
								<div class="panel-control">
									<em class="text-light"> 
										@if(isset($bank_information))										
											@if($bank_information->verification_status == 3)
												<small>  <img class = "checkbox-img" src = "{!! asset('img/checkbox.png') !!}" />  </small>  
											@elseif($bank_information->verification_status == '2')
												<small> (Rejected) </small>
											@elseif($bank_information->verification_status == '1')
												<small class = "text-bold"> (Confirmed Requiring) </small>
											@else
												<small> (Not Confirmed) </small>
											@endif 
										@else
											<small> (Not Added) </small>
										@endif
									</em>
								</div>
								<h3 class="panel-title">Bank Verification</h3>
							</div>  
							<!--Panel body-->
							<div class="panel-body"> 

								@if(!isset($bank_information) ) 
									<p class = "text-sm"> <i> No Bank Information is added yet  </i> </p>
								@else
									<p><b class="col-lg-3">Address:</b>{{$bank_information->bank_name}}</p>
									<p><b class="col-lg-3">Routing Number:</b>{{$bank_information->routing_number}}</p>
									<p><b class="col-lg-3">Account Number:</b>{{$bank_information->account_number}}</p>

									<div class="row">
										<div class = "col-md-12 text-center">
											@if(isset($bank_information) ) 
												<div class="row">
													<div class = "col-md-12 text-center">  
														@if( ($bank_information->verification_status != '0')  && ($bank_information->verification_status != '3') ) 
															<a class="btn btn-primary bankinfo_Approve" href="#" title="Approve Driver License">
																Approve  
															</a>
														@endif 
														@if( ($bank_information->verification_status != '0') && ($bank_information->verification_status != '2'))
															<a class="btn btn-danger bankinfo_Reject" href="#" title="Reject DriverLicense">
																Reject
															</a>
														@endif
													</div>
												</div>
											@endif   
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
	<div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ddtransportationLabel"> Note </h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body"> 
				</div>
			</div>
		</div>
	</div>
@endsection
@push('javascript')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/typeahead.bundle.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.js?'.time())}}"></script>
<script>
	$(document).ready( function (){
		$(document).on( "click", ".noteModal", function(){ 
			//$("#noteModal").modal("show"); 
			$.ajax({
				url: "{{route('document.get.note')}}", 
				type: 'POST',
				dataType: 'json', 
				data: { document: $(this).attr('data-id')  },     
				beforeSend: function () {           
				},                                        
				success: function(json) {
					if(json.status == 1){
						$("#noteModal .modal-body").html( json.html );
						$("#noteModal").modal("show"); 
						$( "#noteForm" ).validate({
							rules: {
							//	description: "required", 
							},
							messages: {
								//description: "Please enter the certification description",
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
								var form =  $(form)[0];
								var formData = new FormData(form);  
								$.ajax({
									type: $(form).attr('method'),
									url:  $(form).attr('action'),
									processData: false,
									contentType: false, 
									data: formData,
									success: function(data) { 
										if(data.error == true){
											$(form).find("button[type=submit]").prop('disabled',false); 
											$.each(data.errors, function(key, value){
												var input = '#noteModal .'+key;
												$(input).parent().addClass('has-error');
												$('.'+key+'-error').removeClass('hide').addClass('show').text(value); 
											}); 
										}else{
											$(form).find("button[type=submit]").prop('disabled',false); 
											swal({
												title: "Role details",   
												text: "Your role details has been updated successfully!",   
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
							},
						}); 
					}
					else{
						swal({
							title: "Error Occured",   
							text:   json.msg,   
							type: "error",   
							confirmButtonText: "Close" 
							}).then(function(isConfirm) {
							if(isConfirm){
								
							}
						}); 
					}
				},
				complete: function () { 
				},
				error: function() { 
				}
			}); 
		}); 
		$(".address_Approve").click(function(){
			swal({
				title: "Are you sure?",
				text: "You want to approve address info!", 
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
						id:  {!! $user->id !!},
						"_token": "{{ csrf_token() }}",
						"tag": "address_info_Approve",
					},
					success: function () {
						$(".personal_info_status").html('<span class="badge badge-dot mr-2"><i class="bg-success"></i><span class="status">Approved</span></span><a href="#!" class="table-action table-action-default" data-toggle="tooltip" data-original-title="Congratulations! approved." data-placement="right"><i class="fas fa-info-circle"></i></a>');
						swal({
							title: "Approved!", 
							text: "Address info has been approved successfully.",
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
		}); 
		$(".address_Reject").click(function(){ 
			swal({
				title: "Are you sure?",
				text: "You want to reject address info!", 
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
						id:  {!! $user->id !!},
						"_token": "{{ csrf_token() }}",
						"tag": "address_info_Reject",
					},
					success: function () {
						$(".personal_info_status").html('<span class="badge badge-dot mr-2"><i class="bg-success"></i><span class="status">Approved</span></span><a href="#!" class="table-action table-action-default" data-toggle="tooltip" data-original-title="Congratulations! approved." data-placement="right"><i class="fas fa-info-circle"></i></a>');
						swal({
							title: "Rejected!", 
							text: "Address info has been rejected successfully.",
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
		});
		/***************************************** Profile Verification  ***********************************************/ 
		$(".profilepic_Approve").click(function(){
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
						id:  {!! $user->id !!},
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

		});
		 
		$(".profilepic_Reject").click(function(){ 
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
						id:  {!! $user->id !!},
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
		});  
		/****************************************  Driver License Verification  ****************************************/
		$(".driverlicense_Approve").click(function(){
			swal({
				title: "Are you sure?",
				text: "You want to approve driver license info!", 
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
						id:  {!! $user->id !!},
						"_token": "{{ csrf_token() }}",
						"tag": "driverlicense_info_Approve",
					},
					success: function () {
						$(".personal_info_status").html('<span class="badge badge-dot mr-2"><i class="bg-success"></i><span class="status">Approved</span></span><a href="#!" class="table-action table-action-default" data-toggle="tooltip" data-original-title="Congratulations! approved." data-placement="right"><i class="fas fa-info-circle"></i></a>');
						swal({
							title: "Approved!", 
							text: "Driver license info has been approved successfully.",
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
		});
		$(".driverlicense_Reject").click(function(){ 
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
						id:  {!! $user->id !!},
						"_token": "{{ csrf_token() }}",
						"tag": "driverlicense_info_Reject",
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
		});
		/****************************************  Driver License Verification  ****************************************/ 
		$(".bankinfo_Approve").click(function(){
			swal({
				title: "Are you sure?",
				text: "You want to approve the bank account!", 
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
						id:  {!! $user->id !!},
						"_token": "{{ csrf_token() }}",
						"tag": "bankcard_info_Approve",
					},
					success: function () {
						$(".personal_info_status").html('<span class="badge badge-dot mr-2"><i class="bg-success"></i><span class="status">Approved</span></span><a href="#!" class="table-action table-action-default" data-toggle="tooltip" data-original-title="Congratulations! approved." data-placement="right"><i class="fas fa-info-circle"></i></a>');
						swal({
							title: "Approved!", 
							text: "Driver license info has been approved successfully.",
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
		});
		$(".bankinfo_Reject").click(function(){ 
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
						id:  {!! $user->id !!},
						"_token": "{{ csrf_token() }}",
						"tag": "bankcard_info_Reject",
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
		}); 
	});
</script>	
@endpush