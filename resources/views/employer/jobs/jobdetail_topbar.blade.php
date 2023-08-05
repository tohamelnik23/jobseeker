<h2 class="text-main pad-btm text-dark text-thin">  
   <span class = "text-mint text-uppercase">[{{ $job->type }}]</span> {!!  $job->headline !!}
</h2>
<div class = "col-md-12"> 
    <div class="m-0-top buckets-container"> 
        <div class="breadcrumb breadcrumb-lg m-0 pad-no">  
            <a  tabindex="1"  href="{!! route('employer.jobs.mainaction', [ $job->serial ,'job-details']) !!}" class="@if($job_tab == 'job_details') active @endif clearfix" style="position:relative;"> 
                <div> 
                    <span class="d-md-none">View Gig</span> 
					@if($job->type == "gig")
						<span class="d-none d-md-inline">View Gig Post</span>
					@else
						<span class="d-none d-md-inline">View Shift Post</span>
					@endif
                </div>
            </a>
            @php
                $invited_freelancers = $job->countActions('invited');
            @endphp 
            <a tabindex="2"  href="{!! route('employer.jobs.mainaction', [ $job->serial ,'suggested']) !!}" class = "@if($job_tab == 'invite') active @endif clearfix" style="position:relative;"> 
                <div>
                    <span class="d-md-none">Invite</span> 
                    <span class="d-none d-md-block">Invite Freelancers</span>  
                    @if($invited_freelancers )
                    <span class="subhead d-none d-md-block" > Unanswered Invites  
                        <strong> (<span>{!! $invited_freelancers  !!}</span>) </strong>  
                    </span>
                    @endif 
                </div>
            </a>
            @php
                $posted_proposals = $job->countActions('proposals');
            @endphp
            <a  tabindex="3"  href = "{!! route('employer.jobs.mainaction', [ $job->serial ,'applicants']) !!}" class="@if($job_tab == 'proposal') active @endif clearfix"  style="position:relative;"> 
                <div> 
                    <span> Review<span class="d-none d-md-inline"> Proposals</span>
                        <strong class="d-none d-sm-inline"> 
                            (<span>{!! $posted_proposals  !!}</span>) 
                        </strong> 
                    </span>
                    @if($job->countShortlisted() )
                    <span class="subhead d-none d-md-block" > Shortlisted  
                        <strong> (<span>{!! $job->countShortlisted()  !!}</span>) </strong>  
                    </span>
                    @endif
                </div>
            </a> 
            @php 
                $sent_offers = $job->countActions('pending_offers'); 
            @endphp 
            <a tabindex="4" href="{!! route('employer.jobs.mainaction', [$job->serial ,'offers']) !!}" class = "@if($job_tab == 'hired') active @endif clearfix" style="position:relative;"> 
                <div>
                    <span> 
                        <span>Hire</span> 
                        <strong class="d-none d-sm-inline" > 
                            (<span>{!! $job->countActions('hired') !!}</span>) 
                        </strong>
                    </span>

                    @if($sent_offers)
                    <span class="subhead d-none d-md-block" > Pending Offers  
                        <strong> (<span>{!! $sent_offers !!}</span>) </strong>  
                    </span>
                    @endif

                </div>
            </a>
        </div> 
    </div>
</div> 