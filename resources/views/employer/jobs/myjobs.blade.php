@extends('layouts.app')
@section('title', 'My Gigs')
@section('css')
<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">

@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
		<div class="col-md-12">
			@include('partial.alert')
		</div> 
		<div class="col-md-12 mar-top-5 text-right demo-nifty-btn">
			<a  href = "{!! route('employer.job.add') !!}" class = "btn btn-mint btn-rounded"> Post a New Gig </a>
			<a  href = "{!! route('employer.shift.add') !!}" class = "btn btn-mint btn-rounded"> Post a New Shift </a> 
		</div>  
		<div class="col-md-12">
			<div class="card mar-ver">
				<div class="card-header bg-light">
					<h4 class = "text-dark"> My Postings </h4>
				</div>
				<div class="card-body pad-no">  
					@forelse($posted_jobs as $posted_job)
					<div class = "posting-item clearfix   pad-hor"> 
						<div class = "row">
							<div class="col-md-10">
								<div>
									<h4 class="m-0-bottom m-md-right m-xs-top">
										<a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'applicants']) !!}" class="break"> 
											<span class = "text-dark">
												@if($posted_job->type == "gig")
													<span class = "text-mint"> [GIG] </span>
												@else
													<span class = "text-mint"> [SHIFT] </span>
												@endif
											</span>
											<span class = "text-dark"> {!! $posted_job->headline !!} </span>
										</a>
									</h4>
								</div>
							</div>
							<div class = "col-md-2 js-stop-propagation text-right">  
								<div class="dropdown">
									<button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
										<i class = "demo-pli-gear"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-lg"> 
										<li><a href ="{!! route('employer.jobs.mainaction', [ $posted_job->serial  , 'applicants' ]) !!}">View Proposals</a></li> 
										<li class="divider"></li>
										<li><a href="{!! route('employer.jobs.mainaction', [ $posted_job->serial ,'job-details']) !!}">View Gig Posting</a></li>
										<li><a href="{!! route('employer.jobs.edit', $posted_job->serial) !!}">Edit Gig Posting</a></li> 
										<li><a href="{!! route('employer.jobs.repost', $posted_job->serial) !!}">Reuse Job Posting</a></li>
										<li><a class = "remove_gig_posting" data-gig-serial = "{!! $posted_job->serial !!}" href = "javasript:void(0)" >Remove Gig Posting</a></li>
									</ul>
								</div>  
							</div>
						</div>
						<div class = "row m-sm-top">
							<div class = "col-md-6 col-sm-12">
								<p class="m-0 text-muted text-capitalize">  {!! $posted_job->payment_type  !!}   </p>
								<p class="m-0 text-muted m-sm-top">
									Created  {!! $posted_job->created_at->diffForhumans() !!}  
								</p>
							</div>
							<div class = "col-md-6 col-sm-12">
								<div class = "row opening-counts-title">
									<div class = "col-xs-4 m-0-bottom p-0-right">
										<div class="opening-counts-value">
											<a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'applicants']) !!}">
												@php
													$posted_proposals = $posted_job->countActions('proposals');
												@endphp
												@if($posted_proposals)
													<strong class="text-mint text-bold "> {!! $posted_proposals  !!}  </strong> 
												@else
													<strong class="text-dark text-bold "> 0  </strong> 
												@endif
											</a>
										</div>  
										<p class="m-0 m-sm-top text-muted">  
											<a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'applicants']) !!}">
												Proposals  
											</a>
										</p> 
									</div>  
									<div class="col-xs-4 m-0-bottom p-md-left"> 
										<div class="opening-counts-value">
											<a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'messaged']) !!}">
												@php
													$messaged_proposals = $posted_job->countActions('messaged');
												@endphp
												@if($messaged_proposals)
													<strong class="text-mint text-bold "> {!! $messaged_proposals  !!}  </strong> 
												@else
													<strong class="text-dark text-bold "> 0  </strong> 
												@endif
											</a> 
										</div>
										<p class="m-0 m-sm-top text-muted"> 
											<a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'messaged']) !!}">
												Messaged 
											</a> 
										</p>
									</div> 

									<div class="col-xs-4 m-0-bottom p-lg-left">
										<div class="opening-counts-value"> 
											<a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'hired']) !!}"> 
												@php
													$hired_proposals = $posted_job->countActions('hired');
												@endphp 
												@if($hired_proposals)
													<strong class="text-mint text-bold "> {!! $hired_proposals  !!}  </strong> 
												@else
													<strong class="text-dark text-bold "> 0  </strong> 
												@endif
											</a>
										</div>
										<p class="m-0 m-sm-top text-muted"> Hired 	</p>
									</div>  
								</div>
							</div> 
						</div>  
					</div> 
					@empty
						<div class = "row mar-all pad-all">
							<div class="col-md-12 text-center">
								<a  href = "{!! route('employer.job.add') !!}" class = "btn btn-mint"> Post a Gig </a>
							</div> 
						</div> 
					@endforelse
				</div>
			</div> 
		</div> 
		@if(count($draft_jobs))
		<div class="col-md-12">
			<div class="card mar-ver">
				<div class="card-header bg-light">
					<h4 class = "text-dark">My Drafts </h4>
				</div>
				<div class="card-body pad-no">
					@forelse($draft_jobs as $draft_job)
					<div class = "posting-item clearfix   pad-hor">
						<div class = "row">
							<div class="col-md-10">
								<div>
									<h4 class="m-0-bottom m-md-right m-xs-top">
										<a href = "{!! route('employer.jobs.edit', $draft_job->serial) !!}" class="break">
											<span class = "text-dark">
												@if($posted_job->type == "gig")
													GIG -
												@else
													SHIFT -
												@endif
											</span>
											<span class = "text-dark"> {!! $draft_job->headline !!} </span>
										</a>
									</h4>
								</div>
							</div>
							<div class = "col-md-2 js-stop-propagation text-right">  
								<div class="dropdown">
									<button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
										<i class = "demo-pli-gear"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-lg"> 
										<li><a href="{!! route('employer.jobs.edit', $draft_job->serial) !!}">Edit Draft</a></li>   
										<li><a href="javascript:void(0)" class = "deleteaction"  >Remove Draft</a></li>
									</ul>
								</div>  
							</div>
						</div>
						<div class = "row m-sm-top">
							<div class = "col-md-12 col-sm-12"> 
								<p class="m-0 text-muted m-sm-top">
                                    Saved  {!! $draft_job->updated_at->diffForhumans() !!}  
                                </p>
							</div> 
						</div> 
					</div> 
					@empty
						 
					@endforelse 

				</div>
			</div> 
		</div>
		@endif  
    </div>
</div>
@include('employer.jobs.partial.closejob')
@endsection
@section('javascript') 

@stop