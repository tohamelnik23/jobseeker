@extends('layouts.app')
@section('title', $job->headline) 
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/> 
<link href="{!! asset('plugins/snackbar/snackbar.min.css') !!}" rel="stylesheet">
@endsection 
@section('content') 
    <div class="container">
        <div class="row justify-content-center">
            @if(Auth::check() && ( $view_way == "private" )) 
                @include('employer.jobs.jobdetail_topbar') 
            @else
                <div class = "col-md-12">
                    <h2 class="text-main pad-btm mar-no pad-no  text-semibold text-left text-dark">
                        Work details
                    </h2> 
                </div>
            @endif
            
            @if(Auth::check() && (Auth::user()->role == 1))
                @if($job->status == 1)    
                    @php
                        $invite =  $job->getInvite(Auth::user()->id);
                    @endphp 
                    @if(isset($invite))
                        <div class = "col-md-12 mar-top">
                            <div class="alert alert-success">
                                <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button> 
                                You have already invited <a href = "{!! route('jobs_invites_details', $invite->serial) !!}" class = "mar-lft  alert-link mar-top"> <strong> View Details </strong> </a>                             
                            </div> 
                        </div>
                    @endif
                @else
                    <div class  = "col-md-12 mar-top">
                        <p class = "pull-left pt-15 text-danger">This {{ $job->type }} is closed </p>
                    </div>
                @endif
                @php
                    $proposal = $job->getProposal(Auth::user()->id);
                @endphp
                @if(isset($proposal))
                    <div class = "col-md-12 mar-top">
                        <div class="alert alert-success">
                            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button> 
                            You have already submitted a proposal   <a href = "{!! route('jobs_proposal_details', $proposal->serial) !!}" class = "mar-lft  alert-link mar-top"> <strong> View Proposal </strong> </a>                             
                        </div> 
                    </div>
                @else 
                    @php
                        $decline =  $job->getDecline(Auth::user()->id);
                    @endphp 
                    @if(isset($decline))
                        <div class = "col-md-12 mar-top">
                            <div class="alert alert-warning">
                                <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button> 
                                @if($decline->decline_user == Auth::user()->id)
                                    Declined by you
                                @else
                                    Withdrawn by client
                                @endif
                                <strong> : {!! $decline->content !!} </strong>
                            </div>  
                        </div>
                    @endif
                @endif
            @endif            
            <div class = "col-md-12 mar-top">
                <div class = "card mar-top">
                    <div class = "card-body  @if($view_way == 'private') bg-gray @endif  pad-no">
                        <div class = "col-lg-9 @if($view_way == 'private') bg-light @endif  pad-all bord-rgt">
                            <div class = "clearfix pad-all">
                                @if($view_way == "private")
                                    @if($job->type == "gig")
                                        <h4 class="m-sm-bottom text-dark m-lg-top text-capitalize">Gig details</h4>
                                        <p class = "text-danger text-bold"> <i> YOUR GIG POSTING IS LIVE </i> </p>
                                    @else
                                        <h4 class="m-sm-bottom text-dark m-lg-top">Shift details</h4>
                                        <p class = "text-danger text-bold"> <i> YOUR SHIFT POSTING IS LIVE </i> </p>
                                    @endif
                                @else
                                    <span class="m-sm-bottom text-dark m-lg-top h3">
                                        <span class = "text-mint text-uppercase">[{{ $job->type }}]</span> {!!  $job->headline !!}
                                    </span>
                                    @if($job->type == "shift")
                                        <span class = "h4 text-mint">
                                            {!! $job->getDisplayDate() !!}   {!! $job->getJobTime() !!}
                                            @if($job->shift_end_date_time)
                                                ~ {!! date('H:i A' , strtotime($job->shift_end_date_time))  !!}
                                            @endif
                                        </span>
                                    @endif
                                @endif
                                <hr class = "mar-btm" /> 
                                <section class = "air-card-divider-sm">
                                    <p class = "text-dark">  Posted  {!! $job->updated_at->diffForhumans() !!}   </p>
                                    <p class = "mar-top">
                                        <span> <i class = "fa fa-map-marker"></i> {!! $job->city !!}, {!! $job->state !!}</span>
                                    </p>
                                    <hr class = "mar-no" />
                                </section>
                                <section class = "air-card-divider-sm">
                                    <div class = "job-description">
                                        {!! nl2br($job->description) !!}
                                    </div>
                                    <hr class = "mar-btm" />
                                </section>
                                <section class = "air-card-divider-sm">
                                    <div class = "row">
                                        <div class = "col-xs-4">
                                            <p class = "text-dark" style = "margin-bottom: 4px;">
                                                @if($job->payment_type == "hourly")
                                                    <i class = "fa fa-clock-o pt-15 pad-rgt"></i> 
                                                @else
                                                    <i class="fa fa-tag"></i>
                                                @endif
                                                {!! $job->getBudget() !!}
                                            </p>
                                            <p class = "pad-lft mar-lft text-capitalize" style = "margin-top: 0px;">{!! $job->payment_type !!}</p> 
                                        </div>
                                        <div class = "col-xs-4">
                                            <p class = "text-dark" style = "margin-bottom: 4px;">
                                                <i class = "fa fa-calendar-check-o pt-15 pad-rgt"></i>  
                                                {!! $job->getDisplayDate() !!}   {!! $job->getJobTime() !!}
                                                @if($job->shift_end_date_time)
                                                    ~ {!! date('H:i A' , strtotime($job->shift_end_date_time))  !!}
                                                @endif
                                            </p>
                                        </div>
                                        <div class = "col-xs-4">
                                            <p class = "text-dark" style = "margin-bottom: 4px;">
                                                <i class = "fa fa-location-arrow pt-15 pad-rgt"></i>  
                                                @if($job->location_type == "remote")
                                                    <span class = "text-dark">Remote Work</span>
                                                @else
                                                    <span class = "text-dark">Local Work</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <hr class = "mar-no" /> 
                                </section> 
                                @php
                                    $attachments = $job->get_attachments();
                                @endphp
                                @if(count($attachments))
                                <section class = "air-card-divider-sm pad-no">
                                    <h5 class="m-sm-bottom text-dark"> Attachments ({{ count($attachments) }}) </h5>
                                    <ul class = "list-unstyled">
                                        @foreach($attachments as $attachment)
                                        @php
                                            $url = $attachment->getURL(); 
                                        @endphp
                                            @if($url != "")
                                                <li class = "pt-17 text-dark">
                                                    <i class = "fa fa-paperclip"></i>
                                                    <a href = "{{ $url }}"  target=”_blank” class = "btn-link pt-15 text-mint">
                                                        <span class = "text-mint text-bold">{{ $attachment->org_file_name }}</span>
                                                    </a>   
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>                                
                                    <hr class = "mar-btm" />
                                </section>
                                @endif
 

                                @php
                                    $questions = $job->getQuestions();
                                @endphp
                                @if(count($questions))
                                <section class = "air-card-divider-sm">
                                    <div class="form-group clearfix">
                                        <div class="col-lg-12">
                                            <h5 class="mar-top"> You will be asked to answer the following questions when submitting a propsal: </h5>
                                        </div>
                                        <div class="col-lg-12">
                                            <ul>
                                                @foreach($questions as $question)
                                                <li class="text-dark pt-14">{!! $question->question !!}</li>
                                                @endforeach
                                            </ul> 
                                        </div>
                                    </div>
                                    <hr class = "mar-btm" />
                                </section>
                                @endif
                                @php
                                    $skills = $job->getSkills();
                                @endphp
                                <section class = "air-card-divider-sm clearfix"> 
                                    <h5 class="m-sm-bottom text-dark  ">Skills and expertise </h5>
                                    <p>
                                        @foreach($skills as $skill)
                                            <span class="badge pad-rgt mar-top-5">{!! $skill->skill !!}</span> 
                                        @endforeach
                                    </p>
                                    <hr class = "mar-btm" />
                                </section> 
                            </div>
                        </div>  
                        <div class = "col-lg-3">
                            @if(Auth::check() && (Auth::user()->role == 2))  
                                @if($job->user_id == Auth::user()->id )
                                    <div class="sidebar m-0-top  p-0-top-bottom">
                                        <section class="p-0-bottom mar-top"> 
                                            <ul class="ats-actions list-unstyled">
                                                @if($view_way == "private")
                                                    <li class="m-sm-bottom">
                                                        <a href="{!! route('jobs_details', $job->serial) !!}" class = "btn-link text-mint">
                                                            <i class="fa fa-eye vertical-align-middle m-0-left  mar-rgt pt-15"></i>View posting
                                                        </a>
                                                    </li> 
                                                @endif 
                                                @if($job->status !== 2)
                                                <li class="m-sm-bottom">
                                                    <a href="{!! route('employer.jobs.edit', $job->serial) !!}" class = "btn-link text-mint">
                                                        <i class="fa fa-pencil vertical-align-middle  m-0-left mar-rgt pt-15"></i>Edit posting
                                                    </a>
                                                </li> 
                                                <li class="m-sm-bottom">
                                                    <a href="javascript:void(0)"  data-gig-serial = "{!! $job->serial !!}"  class = "btn-link text-mint remove_gig_posting"> 
                                                        <i class="fa fa-trash  vertical-align-middle m-0-left mar-rgt pt-15"></i>Remove posting 
                                                    </a>
                                                </li>
                                                @endif 
                                                <li class="m-sm-bottom">
                                                    <a href="{!! route('employer.jobs.repost', $job->serial) !!}"  class = "btn-link text-mint">
                                                        <i class="fa fa-rotate-left vertical-align-middle  m-0-left mar-rgt pt-15"></i>Reuse posting
                                                    </a>
                                                </li>
                                                @if($view_way == "public")
                                                    <li class="m-sm-bottom">
                                                        <a href="{!!  route('employer.jobs.mainaction', [ $job->serial ,'applicants'])  !!}"  class = "btn-link text-mint">
                                                            <i class="fa fa-users vertical-align-middle  m-0-left mar-rgt pt-15"></i> View  proposals
                                                        </a>
                                                    </li> 
                                                @endif
                                            </ul>
                                        </section>
                                    </div>          
                                    @include('employer.jobs.partial.closejob')
                                @else
                                    <div class="sidebar m-0-top  p-0-top-bottom">
                                        <section class="p-0-bottom mar-top clearfix"> 
                                            <div class = "col-md-12">
                                                <p class = "text-dark"> Want to do something similar? </p>
                                            </div> 
                                            <div class = "col-md-12">
                                                <a  href = "#" class = "btn btn-mint btn-block text-capitalize"> Post a <span class = "text-capitalize"> {{ $job->type }} </span> Like This </a>
                                            </div>
                                        </section>
                                    </div>
                                @endif 
                                    @if($view_way == "public")
                                        <hr /> 
                                    @endif 
                            @else
                                <div class="sidebar mar-top  p-0-top-bottom">
                                    @if(Auth::check()) 
                                        <section class="p-0-bottom mar-top clearfix">
                                            @if( ($job->status == 1) && $job->proposeEnable(Auth::user()->id))
                                                @if(isset($invite))
                                                    <a href = "javascript:void(0)" disabled  class = "btn btn-mint btn-block mar-topp text-capitalize">  Submit a Proposal </a>
                                                @else
                                                    <a @if($job->checkApplied(Auth::user()->id)) href = "javascript:void(0)" disabled @else href = "{!! route('jobs_proposals', $job->serial) !!}"  @endif class = "btn btn-mint btn-block mar-top text-capitalize">  Submit a Proposal </a>
                                                @endif
                                            @endif 
                                            <a  href = "javascript:void(0)" class = "btn btn-mint-border savejob_action text-mint  btn-block mar-top text-capitalize" data-serial = "{!! $job->serial  !!}">
                                                @if($job->SavedJob( Auth::user()->id))
                                                    <i class = "fa fa-heart pad-rgt-5"></i>   Saved
                                                @else
                                                    <i class = "fa fa-heart-o pad-rgt-5"></i> Save a {{ $job->type }}   
                                                @endif
                                            </a>
                                        </section> 
                                    @endif  
                                    @php
                                        $client =  $job->getClient();
                                    @endphp             
                                    @if(isset($client))
                                    <section class="p-0-bottom mar-top clearfix"> 
                                        <h4 class = "text-dark"> About the client </h4> 
                                        @include('partial.client_history')
                                    </section>
                                    @endif      
                                </div> 
                            @endif 
                            <div class="sidebar m-0-top mar-btm  p-0-top-bottom">
                                <section class="p-0-bottom mar-top mar-btm clearfix">  
                                    <h4 class="m-sm-bottom text-dark m-lg-top text-capitalize"> {{ $job->type }} Link  </h4>
                                    <div class = "form-group">
                                        <input id = "job_link_detail" type = "text" disabled class = "form-control" value = "{!! route('jobs_details', $job->serial) !!}">
                                    </div>
                                    <a href = "javascript:void(0)" class = "btn-link text-mint copy_job_link"> Copy Link </a>
                                </section>
                            </div>
                        </div>
                    </div> 
                </div> 
                @if($view_way == 'public')
                    @php
                        if(!isset($client)){
                            $client =  $job->getClient(); 
                        }
                        $total_hired_jobs   =  $client->total_job_posted('hired');
                    @endphp     
                    @if($total_hired_jobs)
                        <div class = "card mar-ver">
                            <div class = "card-header bg-light">
                                <h3 class = "text-dark"> Client's recent history ({!! $total_hired_jobs !!}) </h3>
                            </div>
                            @php
                                $total_jobs =   $client->total_job_posted('progress');
                            @endphp 
                            <div class = "card-body">
                                <div class = "col-lg-12"> 
                                    @if(count($total_jobs))
                                    <div class="category_stuff bord-btm mar-btm clearfix">
                                        <div class="col-md-12">
                                            <a href="javascript:void(0)" class="category-anchor">
                                                <h4 class="text-dark mar-ver">  
                                                    Work in progress 
                                                    <span class="pull-right text-bold text-dark anchor-down" >  
                                                        <i class="fa fa-chevron-down"></i>
                                                    </span> 
                                                    <span class="pull-right text-bold text-dark anchor-up"  > 
                                                        <i class="fa fa-chevron-up"></i>
                                                    </span>   
                                                </h4>
                                            </a> 				
                                        </div> 
                                        <div class="col-md-12 category-items">
                                            <div class = "row">
                                                <div class = "col-md-12">
                                                    @foreach($total_jobs as $job_offer)
                                                        @php
                                                            $job = $job_offer->getJob();
                                                        @endphp
                                                    <div class = "row mb-20">
                                                        <div class = "jobs-header col-md-12"> 
                                                            @if(isset($job))
                                                                @if( $job->status == 2)
                                                                    <h4 class = "text-dark mar-no"> {{ $job->headline }} </h4>
                                                                @else
                                                                    <h4 class = "mar-no"> 
                                                                        <a href = "{!! route('jobs_details', $job->serial) !!}" class = "text-mint btn-link text-bold"> {{ $job->headline }} </a>
                                                                    </h4>
                                                                @endif 
                                                            @else
                                                                <h4 class = "text-dark mar-no"> {{ $job_offer->contract_title }} </h4>
                                                            @endif 
                                                            @php
                                                                $freelancer = $job_offer->getFreelancer(); 
                                                            @endphp
                                                            <p class = "text-dark mar-top-5 text-capitalize"> <i> work in progress</i> </p>
                                                            <p class = "mar-no">
                                                                <span class = "pt-14 text-dark"> Freelancer: </span>
                                                                <span>
                                                                    <a class = "text-mint btn-link text-bold" href = "{!! route('freelancers.detail',  $freelancer->serial) !!}"> {{ $freelancer->accounts->name }} </a> 
                                                                </span>
                                                            </p> 
                                                        </div>
                                                        <div class = "job-states col-md-12 text-dark pt-14">
                                                            <p> {!! date('M Y', strtotime($job_offer->start_time)) !!} - Present </p>
                                                            @if($job_offer->payment_type == "fixed" ) 
                                                                <p> Fixed-price ${!! number_format($job_offer->getTotalPaid(), 2) !!} </p>
                                                            @else
                                                                @php
                                                                    $total_start_time = $job_offer->totalTimeHours('since_start');
                                                                    list($hour, $minute)    =   explode(':', $total_start_time );
                                                                    $hour                   =  (int) $hour;
                                                                @endphp 
                                                                <p class = "mar-no"> <span class = "text-bold"> {!! $hour !!} @if($hour == 1) hr @else hrs @endif </span> @ <span class = "text-bold"> ${!! $job_offer->amount !!}/hr </span> </p>
                                                                <p> Billed: ${!! number_format($job_offer->getTotalPaid(), 2) !!} </p>
                                                            @endif
                                                        </div>
                                                    </div> 
                                                    @endforeach
                                                </div> 
                                            </div>
                                        </div> 
                                    </div>
                                    @endif 
                          
                                    @if($total_hired_jobs - count($total_jobs))
                                        <div class = "finished_feedback_div"> 
                                            <div class = "finished_feedback bord-btm">
                                            </div>
                                            <div class = "pad-all">
                                                <a href = "javascript:void(0)" class = "text-mint text-bold btn-feedback-more hidden btn-link"> View More </a>
                                            </div>
                                        </div>
                                    @endif  
                                </div>
                            </div> 
                        </div>
                    @endif 
                    @php
                       // $total_open_jobs    = $client->total_job_posted('open '); 
                    @endphp  
                @endif 
            </div>
        </div>
    </div> 
@endsection
@section('javascript')
    <script src="{{asset('plugins/simplerating/jquery.star-rating-svg.js')}}"></script>
    <script src="{!! asset('plugins/snackbar/snackbar.min.js') !!}"></script>
    <script>
        @if(Auth::check() && (Auth::user()->role == 1)) 
            $(document).on("click", ".savejob_action", function(){
                var job_id  =  $(this).attr('data-serial');
                $.ajax({
                    url: "{{ route('employee.saveaction') }}", 
                    type: 'POST',
                    dataType: 'json',
                    data: {job_id: job_id },     
                    beforeSend: function () { 
                    },                                        
                    success: function(json) {
                        if(json.status == 1){
                            location.reload();
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
        @endif 
        function copyToClipboard(val){
            var $temp = $("<input>");                                    
            $("body").append($temp);
            $temp.val(val).select();
            document.execCommand("copy");
            $temp.remove();
            Snackbar.show({text: 'The link is copied to the clipboard.',  pos: 'top-center',  actionText: '<i class = "fa fa-times"></i>'});  
        }  
        $(".copy_job_link").click(function(){
            copyToClipboard( $("#job_link_detail").val() );
        }); 
          
        @if($view_way == 'public')
            $(".total-rating").each(function(){ 
                $(this).starRating({
                    initialRating: $(this).attr('data-star'),
                    starSize:   $(this).attr('data-size'),
                    totalStars: 5,  
                    disableAfterRate: false,
                    readOnly: true
                });
            }); 
            
            @if($total_hired_jobs)
                @if($total_hired_jobs - count($total_jobs))
                    var current_offset = 0;
                    function getReviews(){
                        $.ajax({
                            url:   "{{ route('jobs_details.getfeedback',   $job->serial ) }}",
                            type: 'POST',
                            data:  { offset: current_offset },
                            dataType: 'json',
                            beforeSend: function (){
                                //$(".menupickergroup").addClass("hidden");
                                //$(".menuloading").removeClass("hidden");
                            },
                            success: function(json){
                                if(json.status){
                                    $(".finished_feedback").append(json.html); 
                                    $(".total-rating").each(function(){ 
                                        $(this).starRating({
                                            initialRating: $(this).attr('data-star'),
                                            starSize:   $(this).attr('data-size'),
                                            totalStars: 5,  
                                            disableAfterRate: false,
                                            readOnly: true
                                        });
                                    });  
                                    current_offset = json.offset;  
                                    if(json.next_button)
                                        $(".btn-feedback-more").removeClass("hidden");
                                    else
                                        $(".feedbacks_group_button").remove();
                                }
                            },
                            complete: function () { 
                            },
                            error: function() { 
                            }
                        });  
                    } 
                    $(document).on("click", ".btn-feedback-more", function(){
                        getReviews();
                    }); 
                    getReviews();   
                @endif
            @endif

        @endif

    </script>
@stop