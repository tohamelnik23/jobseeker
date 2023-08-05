@extends('layouts.app')
@section('title', 'Profile')
@section('css')
	<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/selectize/selectize.bootstrap3.css') }}">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
		@if ($user->accounts->caddress == NULL)
			<div class="col-md-12 alert alert-mint alert-block">	
				<a href = "javascript:void(0)" title="Edit address"  class="close" data-toggle="modal" data-target="#addressModal">
					<i class = " text-2x fa fa-pencil"></i>
				</a> 
				<strong>Updating your address increase chances to hire.</strong>
			</div>
		@elseif($user->getImage() ==  asset('img/avatar/placeholderAvatar.jpg'))
			<div class="col-md-12 alert alert-mint alert-block">	
				<a href = "javascript:void(0)" title="Edit profile picture"   class="close" data-toggle="modal" data-target="#profilepicModal">
					<i class = " text-2x fa fa-pencil"></i>
				</a> 
				<strong>Adding a profile picture increase chances of hire.</strong>
			</div>
		@elseif(count($user->educations) == 0)
			<div class="col-md-12 alert alert-mint alert-block">	
				<a href = "javascript:void(0)" title="Update education"  class="close" data-toggle="modal" data-target="#addeducationModal">
					<i class = " text-2x fa fa-pencil"></i>
				</a>	
				<strong>Adding education details increase chances to hire.</strong>
			</div>
		@elseif($user->accounts->socialsecuritynumber == NULL)
			<div class="col-md-12 alert alert-mint alert-block">	
				<a href = "javascript:void(0)"  class="close" data-toggle="modal" data-target="#socialsecuritynumberModal">
					<i class = " text-2x fa fa-pencil"></i>
				</a>	
				<strong>Adding social security number increase chances to hire.</strong>
			</div>  
		@elseif($user->accounts->caddress != NULL && $user->address_verified_status == 0)
			<div class="col-md-12 alert alert-mint alert-block">	
				<a href = "javascript:void(0)"  class="close" data-toggle="modal" data-target="#addressModal">
					<i class = " text-2x fa fa-pencil"></i>
				</a> 
				<strong>Verify your address details.</strong>
			</div>
		@elseif($user->accounts->caddress != NULL && $user->address_verified_status == 3)	
			<div class="col-md-12 alert alert-mint alert-block">	
				<a href = "javascript:void(0)"  class="close" data-toggle="modal" data-target="#verifyaddressModal">
					<i class = " text-2x fa fa-pencil"></i>
				</a>	
				<strong>Your address details documents are rejected, check the reason and upload new document.</strong>
			</div> 
		@elseif($user->accounts->caddress != NULL && $user->address_verified_status == 1)	
			<div class="col-md-12 alert alert-mint alert-block">	
				<strong>We will review your documents to verify address, it may take 48 hours.</strong>
			</div>
		@endif

		<div class="col-md-12">  
			<div class="panel pos-rel">
				<div class="pad-all clearfix"> 
					<div class  = "col-md-3 text-center ">
						<a href="#" title="Edit address" data-toggle="modal" data-target="#profilepicModal">
							<img alt="Profile Picture" class="img-lg img-circle mar-ver" src="{{$user->getImage()}}"> 
						</a>  
					</div>
					<div class  = "col-md-6"> 
						<p class="text-lg text-semibold mar-no text-main">
							{{$user->accounts->name}} 
							<a href ="javascript:void(0)"  data-toggle="modal" data-target="#miscModal"   class="btn-link pad-lft" > <i class = "fa fa-pencil"></i> </a> 
						</p> 
						<p class=" mar-top-5">{{$user->email}}</p> 
						<p class=" mar-top-5">{{ Mainhelper::formatNumber($user->cphonenumber) }}</p> 
						<p class=" mar-top-5">{{$user->accounts->headline}}</p> 
					</div> 

					<div class  = "col-md-3">  
						<div class  = " text-left"> 
							<div class = "clearfix">
								<h5 class = "text-dark"> Availability </h5>
							</div>
							<a href = "javascript:void(0)" class = "text-dark pt-15 text-bold btn-link availability-button text-capitalize">
								@if(Auth::user()->availability == "active")
									<i class = "fa fa-eye pad-rgt"> </i> 
								@else
									<i class = "fa fa-eye-slash pad-rgt"> </i> 
								@endif
								{{ Auth::user()->availability }} 
							</a>
							
							<div class = "clearfix mar-top">
								<h5 class = "text-dark"> Disired Tavel Distance </h5>
							</div>
							<a href = "javascript:void(0)" class = "text-dark  text-bold btn-link disired_travel_distance-button   text-capitalize">
								<i class = "fa fa-map-marker pad-rgt"></i>  
								@if( $user->accounts->travel_distance)
									{{ $user->accounts->travel_distance  }} miles
								@else
									Any Disctance
								@endif
							</a>

						</div> 
					</div>  
				</div>
			</div>
		</div>
		<div class = "col-md-12">
			<div class = "card mar-btm">
				<div class="card-body tab-item-group pad-no"> 
					<div class = "col-md-4 bord-rgt pad-all">  
						<div class = "pad-hor">
							<h4 class = "text-dark"> View Profile </h4>  
						</div>
						<section class="p-0-bottom  clearfix" style = "margin: 0 -10px;">   
							<div   class="view-profile-tab">
								@php
									$roles = $user->getRoles();
								@endphp
								@foreach($roles as $role_index => $profile)
								<div class="view-profile-tab-item @if(!$role_index) active @endif d-flex justify-space-between" data-tab = "itemtab{!! $profile->serial !!}" > 
									<span class="font-weight-bold flex-1 mr-5 text-dark">{!! $profile->role_title !!}</span> 
									<div   class="icon-container d-flex">
										<div   class="up-icon"  >
											<i class = "fa fa-chevron-right"></i>
										</div>
									</div>
								</div>
								@endforeach 
								<div class = "text-center mar-top"> 
									<a href = "javascript:void(0)" class = "btn-link text-mint add_new_profile"> Add New Profile </a>
								</div> 
							</div>
						</section>
					</div>
					<div class = "col-md-8">
						@foreach($roles as $role_index => $profile)
							<section class = "tab-item-content @if(!$role_index) active @endif  itemtab{!! $profile->serial !!}" data-tab = "{!! $profile->serial !!}">
								<div class  = "pad-all">
									<div class = "md-btm">
										<div class = "row">
											<div class = "col">
												<h3 class = "text-dark mar-top"> 
													{!! $profile->role_title !!}
													<!--a href = "javascript:void(0)" class = "text-dark pad-lft"> <i class = "fa fa-pencil role_action pt-20"></i> </a-->
												</h3>
											</div> 
											<div class = "d-flex align-items-center col col-auto ">
												<h4 class = "text-dark mar-top"> 
													{!! $profile->getHourlyDescription() !!}
													<!--a href = "javascript:void(0)" class = "text-dark pad-lft"> <i class = "fa fa-pencil role_action pt-20"></i> </a-->
												</h4>
											</div>
										</div>
									</div> 
									<div class = "row">
										<div class = "col-md-10 profile-detail-group">
											<div class = "up-line-clamp-v2 profile-detail" style = "-webkit-line-clamp: 7;">
												<span class  = "text-pre-line pt-14 ">{!! $profile->description !!}</span>
											</div> 
											<a href = "javascript:void(0)" data-expanded="false" class="pad-top  text-mint btn-link text-bold updownmore_buttton hidden"> 
												more
											</a>
										</div>
										<div class = "col-md-2">
											<a href = "javascript:void(0)" class = "text-dark pad-lft role_action edit" data-id = "{!! $profile->serial !!}"> <i class = "fa fa-pencil pt-20"></i> </a>
											<a href = "javascript:void(0)" class = "text-dark pad-lft deleteaction" data-url = "{!! route('employee.delete.role', $profile->serial) !!}" data-title = "Remove Role" data-string = "Do you want to remove this role?"> <i class = "fa fa-trash pt-20"></i> </a>
										</div>
										@php
											$subcategory = $profile->subcategory();
										@endphp
										@if(isset($subcategory))
										<div class = "col-md-12 mar-top text-dark pt-15">
											<span class = "text-bold"> Category Type: </span> {{ $subcategory->name  }} 
										</div>
										@endif
										<div class = "col-md-12 mar-top">
											@php
												$role_skills = $profile->getRoleSkills(); 
											@endphp
											<p>
												@foreach($role_skills as $role_skill)
												<span class="badge pad-rgt mar-top-5">{!! $role_skill->skill !!}</span> 
												@endforeach
											</p>
										</div>
									</div>
								</div>
							</section>
						@endforeach
					</div>
				</div>
			</div>
		</div>
        <div class="col-md-12">
			<div class="card mar-btm">
                <div class="card-header">
					Education
					<a href = "javascript:void(0)" title="Add Education" type="button" class="close pull-right" data-toggle="modal" data-target="#addeducationModal">				<i class = "fa fa-plus"></i>						 
					</a>
				</div>
                <div class="card-body">
					@php
						$educations = $user->geteducations();
					@endphp
					@forelse($educations as $num => $education)
						<div class="col-sm-12 @if($num > 0) border-top @endif">
							<h5>
								{{$education->school}}
							</h5>
							<span class="text-muted">
							@if($education->degree != NULL){{$education->degree}}@endif
							@if($education->fieldofstudy != NULL) ,{{$education->fieldofstudy}}@endif
							@if($education->grade != NULL) ,{{$education->grade}}@endif
							</span><br>
							<span class="text-muted">
							@if($education->startyear > 1 && $education->endyear > 1)
							{{$education->startyear}}-{{$education->endyear}}
							@endif
							</span> 
							@if($education->activities != NULL)
							<p>
							Activities and societies: {{$education->activities}}
							</p>
							@endif
							@if($education->description != NULL)
							<p>
								{{$education->description}}
							</p>
							@endif 
							<a href = "javascript:void(0)" data-id = "{!! $education->serial !!}" title="Edit education"   class="close edit-education"  style="position: absolute; right: 30px; top: 10px;">
								<i class = "fa fa-pencil"> </i>
							</a>

							<a href = "javascript:void(0)" data-string = "Do you want to remove this item?"   data-url = "{!! route('employee.delete.education', $education->serial) !!}"   data-title="Delete Education"   class="close delete-education deleteaction"  style="position: absolute; right: 10px; top: 10px;">
								<i class = "fa fa-trash"> </i>
							</a>
						</div>   
					@empty
						<div class="col-sm-12">
							<p class = "text-center">
								<i> No educational information </i>
							</p> 
						</div> 
					@endforelse
                </div>
            </div>
			<div class = "card mar-btm">
				<div class="card-header">
					Gig History
					<a href = "javascript:void(0)" title="Add Job History" type="button" class="close pull-right" data-toggle="modal" data-target="#addjobhistoryModal">				<i class = "fa fa-plus"></i>						 
					</a>
				</div>
				<div class="card-body">
					@php
						$job_histories = $user->getjobhistories();
					@endphp

					@forelse($job_histories as $num => $job_history)
						<div class="col-sm-12 @if($num > 0) border-top   @endif "> 
							<div class = "clearfix pad-ver">
								<h5>
									{{$job_history->job_title}}
								</h5> 
								<h6>
									{{$job_history->job_company}}
								</h6> 
								<span class="text-muted">
									@if($job_history->job_start_date_month != NULL) {{$job_history->job_start_date_month}}@endif/@if($job_history->job_start_date_year != NULL){{$job_history->job_start_date_year}}@endif
								</span>
								~
								<span class="text-muted ">
									@if($job_history->job_end_date_month != NULL) {{$job_history->job_end_date_month}}@endif/@if($job_history->job_end_date_year != NULL){{$job_history->job_end_date_year}}@endif
								</span>

								@if($job_history->job_description != NULL)
									<p class = "text-pre-line ">
										{{$job_history->job_description}}
									</p>
								@endif

								<a href = "javascript:void(0)" data-id = "{!! $job_history->serial !!}" title="Edit Gig History"   class="close edit-jobhistory"  style="position: absolute; right: 30px; top: 10px;">
									<i class = "fa fa-pencil"> </i>
								</a> 
								<a href = "javascript:void(0)" data-string = "Do you want to remove this item?"   data-url = "{!! route('employee.delete.jobhistory', $job_history->serial) !!}"   data-title="Delete Gig History"   class="close delete-jobhistory deleteaction"  style="position: absolute; right: 10px; top: 10px;">
									<i class = "fa fa-trash"> </i>
								</a>
							</div> 
						</div>
					@empty
						<div class="col-sm-12">
							<p class = "text-center">
								<i> There are no gig histories </i>
							</p> 
						</div> 
					@endforelse

				</div>
			</div> 
            <div class="card mar-btm">
                <div class="card-header">Address
					<a href = "javascript:void(0)" title="Edit address" type="button" class="close pull-right" data-toggle="modal" data-target="#addressModal">
						<i class = "fa fa-pencil"></i>
					</a>
					<em class=""> 
						@if($user->address_verified_status == '2')
							<small>
								<img class = "checkbox-img" src = "{!! asset('img/checkbox.png') !!}" />
							</small> 
						@elseif($user->address_verified_status == '3')
							<small> (Rejected) </small>
						@elseif($user->address_verified_status == '1')
							<small> (Confirming) </small>
						@else
							<small> (Not Confirmed - Upload the documents for verification) </small>
						@endif 
					</em>
				</div>
                <div class="card-body"> 
					@if ($user->accounts->caddress == NULL)
						<p class = "text-sm"> <i> No Address is added yet  </i> </p>
					@else
						@if($user->accounts->caddress != NULL)<p><b class="col-lg-3">Address:</b>{{$user->accounts->caddress}}</p>@endif
						@if($user->accounts->city != NULL)<p><b class="col-lg-3">City:</b>{{$user->accounts->city}}</p>@endif
						@if($user->accounts->state != NULL)<p><b class="col-lg-3">State:</b>{{$user->accounts->state}}</p>@endif
						@if($user->accounts->zip != NULL)<p><b class="col-lg-3">Zip:</b>{{$user->accounts->zip}}</p>@endif
						@if($user->accounts->oaddress != NULL)<p><b class="col-lg-3">Address 2:</b>{{$user->accounts->oaddress}}</p>@endif 

						@if(($user->address_verified_status == 0) ||  ($user->address_verified_status == 3))
							<!-- div class = "col-md-12 text-center"> 
								<a  href="#"   title="Approve address"   class="btn btn-primary" data-toggle="modal" data-target="#verifyaddressModal">
									Verify Address
								</a> 
							</div -->
						@endif

					@endif 
                </div>
            </div>
			@php
				$driver_license =  $user->getDriverLicense();
			@endphp
			<div class="card mar-btm">
                <div class="card-header">
					Driver License 
					<a href = "javascript:void(0)" title="Edit Driver License" type="button" class="close pull-right" data-toggle="modal" data-target="#driverlicenseModal">
						<i class = "fa fa-pencil"></i>
					</a> 
					@if(isset($driver_license))
						<em class=""> 
							@if($driver_license->verified == '2')
								<small>  
									<img class = "checkbox-img" src = "{!! asset('img/checkbox.png') !!}" />
								</small> 
							@elseif($driver_license->verified == '3')
								<small> (Rejected) </small>
							@elseif($driver_license->verified == '1')
								<small> (Confirming) </small>
							@else
								<small> (Not Confirmed - Upload the documents for verification) </small>
							@endif
						</em>
					@else
						<em class=""> 
							<small> (Not Added) </small>
						</em>
					@endif  
				</div>
				<div class="card-body"> 
					@if(!isset($driver_license))
						<p class = "text-sm"> <i> No Driver License is added  </i> </p>
					@else
						@if($driver_license->plate_number != NULL)<p><b class="col-lg-3">Number:</b>{{$driver_license->plate_number}}</p>@endif
						@if($driver_license->state != NULL)<p><b class="col-lg-3">State:</b>{{$driver_license->state}}</p>@endif 
						<p><b class="col-lg-3">Expiration:</b>{{$driver_license->expiration_year}}/{{$driver_license->expiration_month}}</p> 
					@endif 
                </div> 
			</div> 
			<div class="card mar-top mar-btm">
				<div class="card-header">
					Certifications
					<a href = "#" title="Add Certification" type="button" class="close pull-right" data-toggle="modal" data-target="#addtransportationModal">					
						<i class = " fa fa-plus"></i>
					</a>
				</div>
				<div class="card-body">
					@php 
						$certifications = 	$user->getCertifications();
					@endphp
					@forelse($certifications as $num => $certification)
						<div class="col-sm-12 @if($num > 0) border-top @endif">
							<p> {!! $certification->description !!} </p>  
							<a href = "javascript:void(0)" data-id = "{!! $certification->serial !!}" title="Edit Certification"   class="close edit-certification"  style="position: absolute; right: 30px; top: 10px;">
								<i class = "fa fa-pencil"> </i>
							</a>  
							<a href = "javascript:void(0)" data-string = "Do you want to remove this certification?"   data-url = "{!! route('employee.delete.certification', $certification->serial) !!}"   data-title="Delete Certification"   class="close delete-certification deleteaction"  style="position: absolute; right: 10px; top: 10px;">
								<i class = "fa fa-trash"> </i>
							</a> 
						</div>
					@empty
						<div class="col-sm-12 mar-top">
							<p class = "text-center">
								<i> There are no certifications yet </i>
							</p> 
						</div>
					@endforelse
				</div>
			</div>  
        </div>
    </div>
</div>

@include('employee.profile.partial.availability_action')
@include('employee.profile.partial.distance_action')

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
				@include('employee.profile.profilepic') 

			</div>
		</div>
  	</div>
</div>
<!-- add education Modal -->
<div class="modal fade" id="addeducationModal" tabindex="-1" role="dialog" aria-labelledby="addeducationModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Education</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employee.profile.education')
			</div>
		</div>
  	</div>
</div> 
<!-- Add Job History Modal -->
<div class="modal fade" id="addjobhistoryModal" tabindex="-1" role="dialog" aria-labelledby="addjobhistoryModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Gig History</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employee.profile.jobhistory')
			</div>
		</div>
  	</div>
</div> 
<div class="modal fade" id="updateeducationModal" tabindex="-1" role="dialog" aria-labelledby="updateroleModal" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" >Education details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				 
			</div>
		</div>
  	</div>
</div>  
<div class="modal fade" id="updatejobhistoryModal" tabindex="-1" role="dialog" aria-labelledby="updatejobhistoryModal" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" >Gig History details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				 
			</div>
		</div>
  	</div>
</div>  
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
				@include('employee.profile.misc')
			</div>
		</div>
  	</div>
</div> 
<!-- social security number Modal -->
<div class="modal fade" id="socialsecuritynumberModal" tabindex="-1" role="dialog" aria-labelledby="socialsecuritynumberModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Social security number</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employee.profile.socialsecuritynumber')
			</div>
		</div>
  	</div>
</div>
<!-- Certification Modal -->
<div class="modal fade" id="addtransportationModal" tabindex="-1" role="dialog" aria-labelledby="addtransportationModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ddtransportationLabel">Certification details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employee.profile.transportation')
			</div>
		</div>
  	</div>
</div> 
<div class="modal fade" id="updatetransportationModal" tabindex="-1" role="dialog" aria-labelledby="updatetransportationModal" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ddtransportationLabel">Certification details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"> 
			</div>
		</div>
  	</div>
</div>  
<div class="modal fade" id="updateroleModal" tabindex="-1" role="dialog" aria-labelledby="updateroleModal" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ddtransportationLabel">Role details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"> 
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
                <h4 class="modal-title">Edit address </h4>
            </div> 
			<div class="modal-body">
				@include('employee.profile.address') 
			</div>
		</div>
  	</div>
</div> 
<!-- Driver License Modal -->
<div class="modal fade" id="driverlicenseModal" tabindex="-1" role="dialog" aria-labelledby="driverlicenseModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">Edit Driver License </h4>
            </div>	
			<div class="modal-body">
				@include('employee.profile.driverlicense')
			</div> 
		</div>
  	</div>
</div> 
<!--
<div class="modal fade" id="verifyaddressModal" tabindex="-1" role="dialog" aria-labelledby="verifyaddressModallLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ddtransportationLabel">Verify address details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employee.profile.verifyaddress')
			</div>
		</div>
  	</div>
</div>
 Verify address Modal --> 
<!-- Verify profile picture Modal -->
<!--div class="modal fade" id="verifyprofilepicModal" tabindex="-1" role="dialog" aria-labelledby="verifyprofilepicModallLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="verifyprofilepicModalLabel">Verify Profile picture</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employee.profile.verifyprofilepic')
			</div>
		</div>
  	</div>
</div -->   
<!-- Profile Item Stuff --> 
<div class="modal fade" id="addProfileModal" tabindex="-1" role="dialog" aria-labelledby="addProfileModal" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
			<div class="modal-header bg-light bord-btm">
				<h5 class="modal-title text-dark" id  = "addProfileModalLabel"> Add Profile </h5>
				<button type="button" class = "close" data-dismiss = "modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('employee.profile.role_profile') 
			</div>
		</div>
  	</div>
</div>
@endsection
@section('javascript')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js?'.time())}}"></script>
<script type="text/javascript" src="{{ asset('js/typeahead.bundle.min.js?'.time())}}"></script>  
<script src="{{  asset('plugins/selectize/selectize.min.js') }}"     type="text/javascript"></script> 
<script type="text/javascript" src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2Lze4Darf903qJrfbrjAqyMhJGqvel0A&libraries=places" ></script>
<script src="{{ asset('/js/jquery.geocomplete.js') }}"></script>
<script> 
	$(document).ready( function (){ 
		/****************************************  Skill Role Stuff  ***********************************/ 
		function industry_change(){
            var current_id =  $("#addRoleForm select[name = 'industry']").find("option:selected").val(); 
			console.log( current_id ); 
            var flag        = 0;
            var first_value = "";
            $("#addRoleForm select[name = 'subcategory']").find("option").each(function(){
                if( $(this).attr('data-subcategory') == current_id){
                    $(this).removeClass("hidden"); 
                    if(first_value == ""){
                        first_value =  $(this).val();
                    }
                }
                else{
                    $(this).addClass("hidden");
                }
            });  
            if($("#addRoleForm select[name = 'subcategory'] option:selected").hasClass('hidden')){
                $("#addRoleForm select[name = 'subcategory']").val(first_value);
            }
        } 
		$(document).on("change", "#addRoleForm select[name = 'industry']", function(){
			industry_change();
		});
		$(document).on("click", ".view-profile-tab-item", function(){
			var tab_id = $(this).attr('data-tab');
			$(".view-profile-tab-item").removeClass("active");
			var obj = $(this).closest(".tab-item-group");
			$(this).addClass("active"); 
			obj.find(".tab-item-content").removeClass("active");
			obj.find(".tab-item-content." + tab_id).addClass("active");
		});		
		$(document).on("click", ".updownmore_buttton", function(){
			var obj = $(this).closest(".profile-detail-group"); 
			if($(this).attr("data-expanded") == 'false'){
				obj.find(".profile-detail").css("-webkit-line-clamp", "initial");
				$(this).attr("data-expanded", true);
				$(this).text("less");
			}
			else{
				obj.find(".profile-detail").css("-webkit-line-clamp", "7");
				$(this).attr("data-expanded", false);
				$(this).text("more");
			}
		});
		$(".add_new_profile").click(function(){  
			$('#addRoleForm')[0].reset(); 
			var $select = 	$('#skills').selectize({
								placeholder: 'Enter Skills Here',
								plugins: ['remove_button']
							});
			var control = $select[0].selectize;
			control.clear();
			$('#addRoleForm input[name = "hourly_rate_from"]').val("");
			$('#addRoleForm input[name = "hourly_rate_end"]').val("");
			$('#addRoleForm input[name = "role_title"]').val("");
			$('#addRoleForm textarea[name = "description"]').val("");
			
			$("#addProfileModalLabel").html("Add Profile");
			industry_change();
			$("#addProfileModal").modal("show");
		});
		function CheckRoleModal(obj, validdate_scope = "all"){
            var flag = 1; 
            var validate_string = ""; 
            validate_string 	= ".form-control.edit";  
            obj.find(  validate_string  ).each(function(){
                if($(this).prop('disabled') == false){
                    var attr_name   = $(this).attr('name');
                    var str_content = $.trim($(this).val()); 
                    var data_error_srting = "";
                    var minlength = $(this).attr('minlength'); 
                    if (typeof minlength !== typeof undefined && minlength !== false) { 
                    }
                    else{
                        minlength = 0;
                    }
                    var error_string = $(this).attr('data-content');
                    if (typeof error_string !== typeof undefined && error_string !== false) { 
                    }
                    else{
                        error_string =  "This field is required";
                    }
                    if( (str_content == "") || ( str_content.length < minlength)){                 
                        flag = 0;
                        addErrorItem($(this), error_string);
                    }
                    else
                        addErrorItem($(this));
                }
                else
                    addErrorItem($(this));            
            });
            return flag;
        } 
		$(document).on("click", ".add_profile" , function(){
            var flag        =  CheckRoleModal($("#addRoleForm"));
            if(flag){
                $.ajax({ 
                    url:   "{!! route('employee.add.role') !!}",
                    type: 'POST',
                    data:  $("#addRoleForm").serialize(),
                    dataType: 'json',
                    beforeSend: function (){
                    },
                    success: function(json){
                        if(json.status){
                           location.reload();
                        }
                        else{
                            $.niftyNoty({
                                type: 		'danger',
                                icon : 		'fa fa-check',
                                message : 	 json.msg,
                                container : 'floating',
                                timer : 5000
                            });
                        }
                    },
                    complete: function () {
                    },
                    error: function() {
                    }
                });  
            } 
        }); 
		$(document).on("click", ".role_action", function(){
			var role_id = $(this).attr('data-id');
			if($(this).hasClass("edit")){
				$.ajax({
					url: "{{ route('employee.get.role') }}", 
					type: 'POST',
					dataType: 'json', 
					data: {role: role_id },     
					beforeSend: function () {           
					},                                        
					success: function(json) {
						if(json.status == 1){
							$("#addProfileModal .modal-body").html( json.html );
							var $select = 	$('#addProfileModal .skills').selectize({
								placeholder: 'Enter Skills Here',
								plugins: ['remove_button']
							});
							$("#addProfileModalLabel").html("Edit Profile");
							industry_change();
							$("#addProfileModal").modal("show");
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
			}
		});		
		function initPage(){
			$(".profile-detail-group").each(function(){ 
				var str = $(this).find(".text-pre-line").html();  
				if(str.split(/\r\n|\r|\n/).length <= 7)
					$(this).find(".updownmore_buttton").addClass("hidden");
				else
					$(this).find(".updownmore_buttton").removeClass("hidden");
			}); 
		}
		initPage();
		/*
			$(".addHeadlineButton").click(function(){
				$("#headlineModal .headline").val( $(this).attr('data-value') ); 
				$("#headlineModal").modal("show");
			}); 
			$("#caddress").trigger("geocode"); 
			$("#caddress").geocomplete({
				details: "form",
				types: ["geocode", "establishment"],
			});
		*/
		$( "#addressForm" ).validate({
			rules: {
				city: 		"required",
				state: 		"required",
				zip: 		"required",
				caddress: 	"required",
			},
			messages: {
				city: "Please enter your City",
				state: "Please enter your State",
				zip: "Please enter your Zip",
				caddress: "Please enter your address"
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
		$("#driverlicenseForm" ).validate({
			rules: {
				driver_state: 		"required",
				plate_number: 		"required",
				expiration_year:    "required",
				expiration_month: 	"required",
			},
			messages: {
				driver_state: "Please enter the state",
				plate_number: "Please enter the plate number",
				expiration_year: "Please enter expiration year",
				expiration_month: "Please enter expiration month"
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
				$( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
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
								var input = '#driverlicenseForm input[name=' + key + ']';
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value); 
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false); 
							swal({   
								title: "Drive License details",   
								text: "Your Drive License has been updated successfully!",   
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
		$( "#miscForm").validate({
			rules: {
				headline: "required",
				birthdate: "required",
				industry:  "required",
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
		$( "#socialsecuritynumberForm").validate({
			rules: {
				socialsecuritynumber: "required",
			},
			messages: {
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
								var input = '#socialsecuritynumberForm input[name=' + key + ']';
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
								//$('#socialsecuritynumberFormresponce').addClass('hide');
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							swal({   
								title: "Social security number",   
								text: "Your social security number has been updated successfully!",   
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
		$("input[ name = 'socialsecuritynumber']").each(function(){ 
			$(this).mask('999-99-9999');   
		}); 
		/*********************************** Certifications  *************************** */
		$(".edit-certification").click(function(){ 
			$.ajax({
				url: "{{route('employee.get.certification')}}", 
				type: 'POST',
				dataType: 'json', 
				data: {certication: $(this).attr('data-id')  },     
				beforeSend: function () {           
				},                                        
				success: function(json) {
					if(json.status == 1){
						$("#updatetransportationModal .modal-body").html( json.html );
						$("#updatetransportationModal").modal("show"); 
						$( "#updatetransportationForm" ).validate({
							rules: {
								description: "required",
							},
							messages: {
								description: "Please enter the certification description"
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
												var input = '#addtransportationModal .'+key;
												$(input).parent().addClass('has-error');
												$('.'+key+'-error').removeClass('hide').addClass('show').text(value); 
											});

										}else{
											$(form).find("button[type=submit]").prop('disabled',false); 
											swal({
												title: "Certification details",   
												text: "Your certification details has been updated successfully!",   
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
		$( "#addtransportationForm" ).validate({
			rules: {
				description: "required",
			},
			messages: {
				description: "Please enter the certification description"
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
							$.each(data.errors, function(key, value){
								var input = '#updatetransportationModal .'+key;
								$(input).parent().addClass('has-error');
								$('.'+key+'-error').removeClass('hide').addClass('show').text(value); 
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false); 
							swal({
								title: "Certification details",   
								text: "Your certification details has been added successfully!",   
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
<script>
	var uploadedDocumentMap = {}
	Dropzone.options.avtarDropzone = {
		url: '{{{ route("media.store") }}}',
		maxFiles: 1,
		maxFilesize: 2, // MB
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
				url: '{{{ route("media.delete") }}}',
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
		}else{
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
</script> 
<script>
	/************************************************ Education Stuff ************************************/
	$(document).ready( function (){
		$( "#addeducationForm" ).validate({
			rules: {
				school: "required",
			},
			messages: {
				school: "Please enter your school name"
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
								var input = '#addeducationForm #'+key;
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value);
								//$('#educationresponce').addClass('hide');
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							swal({   
								title: "Education details",   
								text: "Your education details has been added successfully!",   
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

		$(".edit-education").click(function(){ 
			$.ajax({
				url: "{{route('employee.get.education')}}", 
				type: 'POST',
				dataType: 'json', 
				data: {education: $(this).attr('data-id')  },     
				beforeSend: function () {         
				},                                        
				success: function(json) {
					if(json.status == 1){
						$("#updateeducationModal .modal-body").html( json.html );
						$("#updateeducationModal").modal("show"); 
						$( "#editeducationForm" ).validate({
							rules: {
								school: "required"
							},
							messages: {
								school: "Please enter your school name"
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
												var input = '#updateeducationModal .'+key;
												$(input).parent().addClass('has-error');
												$('.'+key+'-error').removeClass('hide').addClass('show').text(value); 
											}); 
										}else{
											$(form).find("button[type=submit]").prop('disabled',false); 
											swal({
												title: "Education details",   
												text: "Your eduction details has been updated successfully!",   
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
	/************************************************ Job History Stuff *********************************/
		$( "#addjobhistoryForm" ).validate({
			rules: {
				job_title: 		"required",
				job_company: 	"required"
			},
			messages: {
				job_title: 	 "Please enter the gig title",
				job_company: "Please enter the company name",
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
								var input = '#addjobhistoryForm #'+key;
								$(input).parent().addClass('has-error');
								$('#'+key+'-error').removeClass('hide').addClass('show').text(value); 
							});
						}else{
							$(form).find("button[type=submit]").prop('disabled',false);
							swal({   
								title: "Gig History details",   
								text: "The gig history has been added successfully!",   
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
		$(".edit-jobhistory").click(function(){  
			$.ajax({
				url: "{{route('employee.get.jobhistory')}}", 
				type: 'POST',
				dataType: 'json', 
				data: {jobhistory: $(this).attr('data-id')  },     
				beforeSend: function () {         
				},                                        
				success: function(json) {
					if(json.status == 1){
						$("#updatejobhistoryModal .modal-body").html( json.html );
						$("#updatejobhistoryModal").modal("show"); 
						$( "#updatejobhistoryForm" ).validate({
							rules: {
								job_title: 		"required",
								job_company: 	"required"
							},
							messages: {
								job_title: 	 "Please enter the gig title",
								job_company: "Please enter the company name",
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
												var input = '#updatejobhistoryModal .'+key;
												$(input).parent().addClass('has-error');
												$('.'+key+'-error').removeClass('hide').addClass('show').text(value); 
											}); 
										}else{
											$(form).find("button[type=submit]").prop('disabled',false); 
											swal({
												title: "Gig History details",   
												text: "Your gig history details has been updated successfully!",   
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
	});
	/*********************************************** Address Approve Stuff  ************************************/ 
	@if(($user->address_verified_status == 0) ||  ($user->address_verified_status == 3))	
		Dropzone.options.addressDropzone = {
			url: '{{{ route("media.store") }}}',
			maxFilesize: 2, // MB
			addRemoveLinks: true,
			uploadMultiple: true,
			parallelUploads :3,
			acceptedMimeTypes: "image/jpeg,image/jpg,image/png,.pdf",
			headers: {
				'X-CSRF-TOKEN': "{{ csrf_token() }}"
			},
			autoProcessQueue: false,
			previewTemplate: "<div class=\"dz-preview dddd dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail class=\"rounded-circle\"/></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>",
			sending: function(file, xhr, formData) {
				formData.append('user_id',{{$user->id}});
				formData.append('tag',"address");  
				var datas = $("#addressForm").serializeArray();
				for(var i = 0; i < datas.length; i++){
					formData.append(  datas[i].name,  datas[i].value);   
				}
			},
			success: function (file, response) { 
				swal({
					title: "Your document",
					text: "Your document to verify address details has been uploaded successfully!",   
					type: "success",   
					confirmButtonText: "Close"
				}).then(function(isConfirm) {
					if(isConfirm){
						location.reload();
					}
				}); 
				$('#verifyaddressModal').modal('hide');  
			},
			removedfile: function (file) {
				$.ajax({
					url: '{{{ route("media.delete") }}}',
					type: "post",
					data: {
						id: 		file.id,
						tag:		'removeAddress', 
						"_token": 	"{{ csrf_token() }}",
					}
				});
				file.previewElement.remove(); 
			},
			init: function(){
				var myDropzone = this,
				submitButton = document.querySelector("#address-submit");
				submitButton.addEventListener('click', function(e) {
					e.preventDefault();
					var file_count = myDropzone.getAcceptedFiles().length;
					if(file_count < 1){
						$('.image-upload-error-address').css('display','block').text('Please upload your document as per above instructions max filesize is 2 MB.');
							return false;
					} 
					if($("#addressForm" ).valid()){
						myDropzone.processQueue(); 
					}  
				});
			}
		}
	@endif
	/*********************************************** Driver License  *******************************************/
	@if( (!isset($driver_license))   ||   ($driver_license->verified == 0) ||  ($driver_license->verified == 3)) 
		Dropzone.options.driverlicenseDropzone = {
			url: '{{{ route("media.store") }}}',
			maxFilesize: 2, // MB
			addRemoveLinks: true,
			uploadMultiple: true,
			parallelUploads :3,
			acceptedMimeTypes: "image/jpeg,image/jpg,image/png,.pdf",
			headers: {
				'X-CSRF-TOKEN': "{{ csrf_token() }}"
			},
			autoProcessQueue: false,
			previewTemplate: "<div class=\"dz-preview dddd dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail class=\"rounded-circle\"/></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>",
			sending: function(file, xhr, formData) {
				formData.append('user_id',{{$user->id}});
				formData.append('tag',"driverlicense");  
				var datas = $("#driverlicenseForm").serializeArray();
				for(var i = 0; i < datas.length; i++){
					formData.append(  datas[i].name,  datas[i].value);   
				}
			},
			success: function (file, response) { 
				swal({
					title: "Your document",
					text: "Your document to verify driver license details has been uploaded successfully!",   
					type: "success",   
					confirmButtonText: "Close"
				}).then(function(isConfirm) {
					if(isConfirm){
						location.reload();
					}
				}); 
				$('#driverlicenseModal').modal('hide');  
			},
			removedfile: function (file) {
				$.ajax({
					url: '{{{ route("media.delete") }}}',
					type: "post",
					data: {
						id: 		file.id,
						tag:		'removeDriverlicense', 
						"_token": 	"{{ csrf_token() }}",
					}
				});
				file.previewElement.remove(); 
			},
			init: function(){
				var myDropzone = this,
				submitButton = document.querySelector("#driverlicense-submit");
				submitButton.addEventListener('click', function(e) {
					e.preventDefault();
					var file_count = myDropzone.getAcceptedFiles().length;
					if(file_count < 1){
						$('.image-upload-error-driverlicense').css('display','block').text('Please upload your document as per above instructions max filesize is 2 MB.');
							return false;
					} 
					if($("#driverlicenseForm" ).valid()){
						myDropzone.processQueue(); 
					}  
				});
			}
		}
	@endif	 
	/*********************************************** Profile Approve Stuff  ************************************/ 
	/*
	Dropzone.options.profilepicDropzone = {
		url: '{{{ route("media.store") }}}',
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
		sending: function(file, xhr, formData){
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
		},
		error: function(file, errorMessage) {
			errors = true;
		},
		removedfile: function (file) {
			$.ajax({
				url: '{{{ route("media.delete") }}}',
				type: "post",
				data: {
					id: file.id,
					tag:'removeprofilepic',
					'user_id':{{$user->id}},
					"_token": "{{ csrf_token() }}",
				}
			});
			file.previewElement.remove(); 
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
	*/ 
</script> 
@stop