@extends('layouts.app')
@section('title', $freelancer->accounts->name) 
@section('css') 
@endsection
@section('content')
    @php
        if(($freelancer->work_status == "proposal") || ($freelancer->work_status == "invite")|| ($freelancer->work_status == "offer"))
            $decline = $freelancer->getDecline($job->id); 
    @endphp
    <div class="container">
        <div class="row justify-content-center"> 
            @if(isset($decline))
                <div class = "col-md-12">
                    <div class="alert alert-warning">
                        <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
                        {!! $decline->getDeclineMessage('client') !!}
                    </div>
                </div>  
            @endif 
            <div class = "col-md-12">
                <div class = "card mar-top">
                    <div class = "card-body pad-no action_btn_group" data-serial  = "{!! $freelancer->serial !!}">
                        <div class = "py-30 pad-hor bord-btm">
                            <div class = "row">
                                <div class = "col-lg-7">
                                    <div class = "cfe-ui-profile-identity">
                                        <div class = "mr-10 mr-lg-30 position-relative">
                                            <img  class="img-circle img-lg" src="{{$freelancer->getImage()}}">      
                                        </div>
                                        <div class = "identity-container"> 
                                            <div class="mar-btm-5 mb-md-10">
                                                <h3  class="d-inline vertical-align-middle m-0 text-dark"> {!! $freelancer->accounts->name  !!}  </h3> 
                                                <p class = "text-dark mar-top-5">
                                                    <i class = "fa fa-map-marker"></i>  {!! $freelancer->accounts->city  !!}, {!! $freelancer->accounts->state  !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class = "col-lg-5">
                                    <div class="text-right action_btn_group" data-serial  = "{!! $freelancer->serial !!}" >
                                        @if($freelancer->work_status == "proposal")
                                            @if(!isset($decline))
                                                @php
                                                    $checkArchived =  $freelancer->checkArchived($job->id);
                                                @endphp
                                                @if($checkArchived)
                                                    <div class = "col-sm-2">
                                                        <div class="dropdown">
                                                            <button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                                                <i class = "demo-pli-gear"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-lg action_btn_group" data-serial = "{!! $freelancer->serial !!}">  
                                                                <li>
                                                                    @php
                                                                        $checkSaved = $freelancer->checkSaved( Auth::user()->id );
                                                                    @endphp
                                                                    <a href="javascript:void(0)" class = "@if($checkSaved) remove_savefreelancer @else save_freelancer_action @endif">
                                                                        @if($checkSaved)
                                                                            <i class = "fa fa-heart pad-rgt"></i> Saved Candidate
                                                                        @else
                                                                            <i class = "fa fa-heart-o pad-rgt"></i> Save Candidate
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class = "col-sm-5">
                                                        <button class="btn btn-block  btn-mint btn-card-button decline-action" data-type = "proposal" type = "button"> 
                                                            Decline
                                                        </button>
                                                    </div>
                                                    <div class = "col-sm-5">
                                                        <button class="btn btn-block btn-default btn-card-button archiveaction actives" type = "button">
                                                            <span class = "text-mint">
                                                                UnArchive
                                                            </span>
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class = "col-sm-2">
                                                        <div class="dropdown">
                                                            <button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                                                <i class = "demo-pli-gear"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-lg action_btn_group" data-serial = "{!! $freelancer->serial !!}"> 
                                                                <li><a href="javascript:void(0)" class = "archiveaction "><i class = "fa fa-thumbs-down pad-rgt"></i> Archieve</a></li>  
                                                                <li>
                                                                    @php
                                                                        $checkSaved = $freelancer->checkSaved( Auth::user()->id );
                                                                    @endphp
                                                                    <a href="javascript:void(0)" class = "@if($checkSaved) remove_savefreelancer @else save_freelancer_action @endif">
                                                                        @if($checkSaved)
                                                                            <i class = "fa fa-heart pad-rgt"></i> Saved Candidate
                                                                        @else
                                                                            <i class = "fa fa-heart-o pad-rgt"></i> Save Candidate
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" class = "decline_candidate decline-action" data-type = "proposal"> 
                                                                        <i class = "fa fa-times-circle pad-rgt"></i> Decline Candidate 
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>  
                                                    </div>
                                                    @php
                                                        $checkShortlist = $freelancer->checkShortlist($job->id);
                                                    @endphp
                                                    <div class = "col-sm-5">
                                                        <button class="btn btn-block btn-default btn-card-button shortlistaction @if($checkShortlist) actives @endif" type = "button">
                                                            <span class = "text-mint">
                                                                @if($checkShortlist)
                                                                    Short Listed
                                                                @else
                                                                    Short List
                                                                @endif
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class = "col-sm-5" >
                                                        <button class="btn btn-block  btn-mint btn-card-button" type = "button"> 
                                                            Hire Freelancer
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                        @if($freelancer->work_status == "invite")
                                            @if(!isset($decline))
                                                <div class = "col-sm-2"> 
                                                    <div class="dropdown">
                                                        <button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                                            <i class = "demo-pli-gear"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-lg action_btn_group" data-serial = "{!! $freelancer->serial !!}"> 
                                                            <li><a href="#"><i class = "fa fa-thumbs-down pad-rgt"></i> Archieve</a></li>  
                                                        </ul>
                                                    </div>  
                                                </div>
                                                <div class = "col-sm-5" >
                                                    <button class="btn btn-block btn-default btn-card-button decline-action" data-type = "invite" type = "button">
                                                        <span class = "text-mint">
                                                            Decline
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class = "col-sm-5" >
                                                    <a  href = "{!! route('employer.jobs.createoffer', $freelancer->serial ) !!}?job_id={!! $job->serial !!}" class="btn btn-block  btn-mint btn-card-button" type = "button"> 
                                                        Hire Freelancer 
                                                    </a>
                                                </div>
                                            @endif
                                        @endif

                                        @if($freelancer->work_status == "offer")
                                            @if(!isset($decline))
                                                <div class = "col-sm-5" >
                                                    <button class="btn btn-block btn-mint  btn-card-button decline-action" data-type = "offer" type = "button"> 
                                                        Decline Offer 
                                                    </button>
                                                </div>  
                                            @endif
                                        @endif

                                        @if($freelancer->work_status == "none")
                                            @if(Auth::check() && ( Auth::user()->role == '2' ))  
                                                <div class = "col-sm-5" >
                                                    <button class="btn btn-block btn-default btn-card-button" type = "button">
                                                        <span class = "text-mint"> Hire</span>
                                                    </button>
                                                </div> 
                                                <div class = "col-sm-5" >
                                                    <button class="btn btn-block  btn-mint btn-card-button" type = "button"> 
                                                        Invite
                                                    </button>
                                                </div>
                                                <div class = "col-sm-2">
                                                    @php
                                                        $checkSaved = $freelancer->checkSaved( Auth::user()->id );
                                                    @endphp
                                                    <button class="btn   @if($checkSaved) remove_savefreelancer @else save_freelancer_action @endif  btn-default btn-card-button btn-rounded" type = "button">
                                                        <span class = "text-mint"> 
                                                            @if($checkSaved)
                                                                <i class = "fa fa-heart"></i>
                                                            @else
                                                                <i class = "fa fa-heart-o"></i>
                                                            @endif
                                                        </span>
                                                    </button>
                                                </div> 
                                            @endif
                                        @endif

                                    </div>   
                                </div> 
                            </div>
                        </div> 
                        @if(($freelancer->work_status == "proposal") || ($freelancer->work_status == "invite")  || ($freelancer->work_status == "offer"))
                            <div class = "py-0">
                                <div class = "d-flex row">
                                    <div class = "col-md-4 pad-no bord-rgt">
                                        <aside class = "pad-all"> 
                                            <section class="air-card-divider-sm">
                                                <div class = "pad-hor">
                                                    @if($freelancer->work_status == "proposal")
                                                        @php
                                                            $job = $freelancer->proposal->getJob(); 
                                                        @endphp
                                                    <h4 class = "text-bold text-dark pt-17"> Applicant </h4>
                                                    <p class = "pt-14 text-dark">  {!! $freelancer->accounts->firstname !!} has applied to or been invited to your <i> {!! $job->headline!!} </i> </p>
                                                    @endif

                                                    @if($freelancer->work_status == "offer")
                                                        @php
                                                            $job = $freelancer->offer->getJob(); 
                                                        @endphp
                                                        <h4 class = "text-bold text-dark pt-17"> Applicant </h4>
                                                        <p class = "pt-14 text-dark"> 
                                                            Pending offer for  <i> {!! $job->headline!!} </i>  
                                                        </p>
                                                    @endif

                                                    @if($freelancer->work_status == "invite")
                                                        @php
                                                            $job = $freelancer->invite->getJob(); 
                                                        @endphp
                                                    <h4 class = "text-bold text-dark pt-17"> Applicant </h4>
                                                    <p class = "pt-14 text-dark"> 
                                                        Pending applicant for  <i> {!! $job->headline!!} </i>  
                                                    </p>
                                                    @endif

                                                </div>
                                            </section> 
                                        </aside>
                                    </div>
                                    <div class = "col-md-8 pad-no">
                                        @if($freelancer->work_status == "invite")
                                            <section class="air-card-divider-sm bord-btm">
                                                <div class = "pad-hor">
                                                    <div class = "row"> 
                                                        <div class = "col-md-4">
                                                            <h3 class = "text-dark"> Proposal Details </h3>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </section>
                                            <section class="air-card-divider-sm bord-btm">
                                                <div class = "pad-all">
                                                    <div class = "row"> 
                                                        <div class = "col-md-12 m-btm-30">
                                                            <h4 class = "text-dark"> Invitation  </h4>
                                                            <p class = "description-detaillist">{!!  $freelancer->invite->notes  !!}</p>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </section> 
                                        @endif 

                                        @if($freelancer->work_status == "offer")
                                            <section class="air-card-divider-sm bord-btm">
                                                <div class = "pad-hor">
                                                    <div class = "row"> 
                                                        <div class = "col-md-4">
                                                            <h3 class = "text-dark"> Offer Details </h3>
                                                        </div> 
                                                        <div class = "col-md-8">
                                                            <h4 class="text-right mb-0 text-dark pad-rgt">
                                                                @if($freelancer->offer->payment_type == "hourly")
                                                                <div>${!! $freelancer->offer->amount !!}  /hr</div> 
                                                                @else
                                                                <div>${!! $freelancer->offer->amount !!}</div> 
                                                                @endif
                                                                <small class = "mar-top-5 text-dark text-bold">Offered Amount</small> 
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                            <section class="air-card-divider-sm bord-btm">
                                                <div class = "pad-all">
                                                    <div class = "row"> 
                                                        <div class = "col-md-12 m-btm-30">
                                                            <h4 class = "text-dark"> Work Details  </h4>
                                                            <p class = "description-detaillist">{!!  $freelancer->offer->work_details  !!}</p>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </section>
                                        @endif

                                        @if($freelancer->work_status == "proposal")
                                            <section class="air-card-divider-sm bord-btm">
                                                <div class = "pad-hor">
                                                    <div class = "row"> 
                                                        <div class = "col-md-4">
                                                            <h3 class = "text-dark"> Proposal Details </h3>
                                                        </div>
                                                        <div class = "col-md-8">
                                                            <h4 class="text-right mb-0 text-dark pad-rgt">
                                                                @if($job->payment_type == "hourly")
                                                                <div>${!! $freelancer->proposal->proposal_amount !!}  /hr</div> 
                                                                @else
                                                                <div>${!! $freelancer->proposal->proposal_amount !!}</div> 
                                                                @endif
                                                                <small class = "mar-top-5 text-dark text-bold">Proposed Bid</small> 
                                                            </h4>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </section>
                                            <section class="air-card-divider-sm bord-btm">
                                                <div class = "pad-all">
                                                    <div class = "row"> 
                                                        <div class = "col-md-12 m-btm-30">
                                                            <h4 class = "text-dark"> Message from Gig doer </h4>
                                                            <p>{!! nl2br($freelancer->proposal->coverletter) !!}</p> 
															@php
																$answers = $freelancer->proposal->getAnswers();  
															@endphp 
															@foreach($answers as $answer) 
                                                                <p class = "text-dark text-bold mar-top clearfix"> {!! $answer->question !!} </p> 
                                                                <div class = "up-line-clamp-v2 profile-detail" style = "-webkit-line-clamp: 4;">
                                                                    <span class  = "text-pre-line  pt-14 ">{!! $answer->answer !!}</span>
                                                                </div> 
                                                                <a href = "javascript:void(0)" data-expanded="false" class="pad-top  text-mint btn-link text-bold updownmore_buttton hidden"> 
                                                                    more
                                                                </a>   
															@endforeach 
                                                        </div>
                                                    </div>
                                                    @if(!isset($decline))
                                                    <div class = "row mar-top"> 
                                                        @php
                                                            $messagelist = $freelancer->checkMessaged($job->id);
                                                        @endphp

                                                        @if(!isset($messagelist))
                                                        <div class="col-sm-12">
                                                            <textarea name="message_content" class="form-control edit" rows="5"></textarea>
                                                            <span class="help-block"> 
                                                                <strong></strong>  
                                                            </span>
                                                        </div> 
                                                        <div class="col-sm-12">
                                                            <button type = "button" class = "btn btn-mint send_message_button" disabled>Send Message</button>
                                                        </div>
                                                        @else
                                                            <div class="col-sm-12">
                                                                <a href = "{!! route('messages') !!}?room={!! $messagelist->serial !!}" class = "btn btn-mint" >Send Message</a>
                                                            </div> 
                                                        @endif
                                                    </div>
                                                    @endif 
                                                </div>
                                            </section>  
                                        @endif 

                                    </div>
                                </div>
                            </div>
                        @endif 
                    </div>
                </div> 
            </div> 
            <div class = "col-md-12  @if( ($freelancer->work_status == "proposal") || ($freelancer->work_status == "invite")  || ($freelancer->work_status == "offer") )  mar-top @endif">
			    <div class = "card mar-btm @if( ($freelancer->work_status == "proposal") || ($freelancer->work_status == "invite")  || ($freelancer->work_status == "offer"))  mar-top  @endif">
                    <div class="card-body pad-no">
                        <div class = "col-md-12">
                        @include('partial.freelancer_profile')
                        </div>
                    </div>
                </div>
		    </div> 
            @php 
                $job_histories = $freelancer->getjobhistories(); 
            @endphp
            <div class = "col-md-12 mar-top">
			    <div class = "card mar-btm mar-top">
                    <div class="card-body pad-no">
                        <section class="p-0-bottom bord-btm clearfix">
                            <div class  = "pad-all">
                                <h4 class = "text-dark"> Employment History </h4>
                            </div>
                        </section>
                        <section class="p-0-bottom bord-btm clearfix">
                            @forelse($job_histories as $job_history)
                                <div class  = "employment_history_item pad-all bord-btm">
                                    <h4 class = "text-dark pt-15"> {{$job_history->job_title}} | {{$job_history->job_company}}  </h4>
                                    <p class = "mar-top-5">
                                        @if($job_history->job_start_date_month != NULL) {{ Mainhelper::getMonth($job_history->job_start_date_month)}}@endif @if($job_history->job_start_date_year != NULL){{$job_history->job_start_date_year}}@endif 
                                        -
                                        @if($job_history->job_end_date_month != NULL) {{ Mainhelper::getMonth($job_history->job_end_date_month)}}@endif @if($job_history->job_end_date_year != NULL){{$job_history->job_end_date_year}}@endif
                                    </p> 
                                    <div class = "profile-detail-group">
                                        <div class = "up-line-clamp-v2 profile-detail mar-top" style = "-webkit-line-clamp: 5;">
                                            <span class  = "text-pre-line text-dark pt-14">{!!  $job_history->job_description !!}</span>
                                        </div> 
                                        <a href = "javascript:void(0)" data-expanded="false" class="pad-top  text-mint btn-link text-bold updownmore_buttton hidden"> 
                                            more
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class  = "employment_history_item pad-all bord-btm">
                                    <p class = "mar-top-5"> No Employment Hisotry </p>
                                </div>
                            @endforelse
                        </section> 
                    </div>
                </div>
		    </div> 
        </div>
    </div> 

    @if(Auth::check() && ( Auth::user()->role == '2' )) 
        @include('employer.jobs.partial.savefreelancer_modal')
        @include('employer.jobs.partial.employer_action')
    @endif  

    @if(($freelancer->work_status == "proposal") || ($freelancer->work_status == "invite")  || ($freelancer->work_status == "offer"))
        @if(!isset($decline))
            @include('employer.jobs.partial.decline_invite')
        @endif
    @endif  

@endsection
@push('partialscripts')
    <script>
        function initPage(){
            $(".profile-detail-group").each(function(){  
                if($(this).find(".text-pre-line").length){
                    var str = $(this).find(".text-pre-line").html();    
                    if(str.split(/\r\n|\r|\n/).length <= 5)
                        $(this).find(".updownmore_buttton").addClass("hidden");
                    else
                        $(this).find(".updownmore_buttton").removeClass("hidden");     
                }
            }); 
        } 
        $(document).on("click", ".updownmore_buttton", function(){
			var obj = $(this).closest(".profile-detail-group"); 
			if($(this).attr("data-expanded") == 'false'){
				obj.find(".profile-detail").css("-webkit-line-clamp", "initial");
				$(this).attr("data-expanded", true);
				$(this).text("less");
			}
			else{
				obj.find(".profile-detail").css("-webkit-line-clamp", "5");
				$(this).attr("data-expanded", false);
				$(this).text("more");
			}
		}); 
        initPage(); 
        @if($freelancer->work_status == "proposal") 
            @if(Auth::check() && ( Auth::user()->role == '2' )) 
                // send message stuff
                function checkMessageTab(){
                    var message_content = $("textarea[name = 'message_content']").val(); 
                    if($.trim(message_content) == ""){
                        $(".send_message_button").prop("disabled", true);
                    }
                    else{
                        $(".send_message_button").prop("disabled", false);
                    }
                } 
                $(document).keyup("textarea[name = 'message_content']", function(){ 
                    checkMessageTab();
                }); 
                checkMessageTab(); 
                $(".send_message_button").click(function(){
                    var user_id         =  $(this).closest(".action_btn_group").attr('data-serial');
                    var message_content = $("textarea[name = 'message_content']").val();
                    if($.trim(message_content) == "") return -1;
                    $.ajax({
                        url: "{{ route('employer.jobs.sendmessageToEmployee', $job->serial) }}", 
                        type: 'POST',
                        dataType: 'json',
                        data: {user_id: user_id, message: message_content },
                        beforeSend: function () {
                        },
                        success: function(json) {
                            if(json.status == 1){
                                location.href =  json.url;
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
        @endif
    </script>
@endpush