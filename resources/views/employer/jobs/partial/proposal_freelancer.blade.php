@php 
    if($job_tab == "proposal"){
        $proposal           =  $job->getProposal($freelancer->id);
        $profile_freelancer =  $proposal->getProfile();  
    } 
    if($job_tab == "invite")
        $profile_freelancer =  $freelancer->getBestProfile($job_skills);  
    
    if($job_tab == "hired"){ 
        $offer              =  $job->getOffer($freelancer->id);
        $profile_freelancer =  $offer->getProfile();  
    } 
    $decline = $freelancer->getDecline($job->id); 
@endphp
<section class = "freelancer_item_card">
    <div class = "freelancer_item_result row">
        <div class = "col-md-2">
            <div class = "row">  
                <div class="col-md-12">
                @if($job_tab == "invite")
                    @if($freelancer->checkInvited($job->id))
                        <a target = "_blank" href="{!!  route('employer.jobs.mainaction.user', [$job->serial ,  $freelancer->serial  ]) !!}"> 
                            <img  class="img-circle img-lg" src = "{{$freelancer->getImage()}}"> 
                        </a>
                    @else
                        <a target = "_blank" href="{!!   route('freelancers.detail', $freelancer->serial) !!}"> 
                            <img  class="img-circle img-lg" src = "{{$freelancer->getImage()}}"> 
                        </a> 
                    @endif
                @else
                    <a target = "_blank" href="{!!  route('employer.jobs.mainaction.user', [$job->serial ,  $freelancer->serial  ]) !!}"> 
                        <img  class="img-circle img-lg" src = "{{$freelancer->getImage()}}"> 
                    </a>
                @endif
                </div> 
            </div> 
            <div class="row m-md-top"   style=""> 
                <div class="col-md-10"> 
                    @php
                       $checkInvited =  $freelancer->checkInvited($job->id);
                    @endphp
                    @if($checkInvited) 
                            @if($subrequest == "saved")
                                @if($checkInvited == 1) <span class="badge badge-relationship btn-block text-center m-0-top-bottom m-0-left btn-sm"> Invited  </span> @endif
                                @if($checkInvited == 2) <span class="badge badge-relationship btn-block text-center m-0-top-bottom m-0-left btn-sm"> Applied  </span> @endif
                            @else
                                @if($checkInvited == 1) <span class="badge badge-relationship btn-block text-center m-0-top-bottom m-0-left btn-sm"> Invited   </span> @endif 
                            @endif 
                       
                    @endif 
                </div>
            </div> 
        </div> 
        <div class = "col-md-10">
            <div class = "row"> 
                <div class="col-xs-12 col-md-6 col-lg-7"> 
                    <h5 class="m-0-top-bottom display-inline-block">
                        @if($job_tab == "invite")
                            @if($freelancer->checkInvited($job->id))
                                <a target = "_blank" href="{!!  route('employer.jobs.mainaction.user', [$job->serial ,  $freelancer->serial  ])  !!}"   class="freelancer-tile-name" title="{!! $freelancer->accounts->name !!}"> 
                                    <span class = " text-mint">{!! $freelancer->accounts->name !!}</span> 
                                </a> 
                            @else
                                <a target = "_blank" href="{!!  route('freelancers.detail', $freelancer->serial) !!}"   class="freelancer-tile-name" title="{!! $freelancer->accounts->name !!}"> 
                                    <span class = " text-mint">{!! $freelancer->accounts->name !!}</span> 
                                </a> 
                            @endif
                        @else
                            <a target = "_blank" href="{!!  route('employer.jobs.mainaction.user', [$job->serial ,  $freelancer->serial  ]) !!}"   class="freelancer-tile-name" title="{!! $freelancer->accounts->name !!}"> 
                                <span class = " text-mint">{!! $freelancer->accounts->name !!}</span>
                            </a> 
                        @endif
                    </h5>
                    <h4 class="m-0 freelancer-tile-title text-dark">{!! $profile_freelancer->role_title !!}</h4> 
                    <p class = "text-dark"> {!! Mainhelper::getStateFromValue($freelancer->accounts->state)   !!} </p> 
                    @if($job_tab !== "hired")
                        @if(($job->payment_type == 'hourly') && ($job_tab == "proposal"))
                            @if($job_tab == "proposal")
                                <h5 class = "text-dark"> ${!! $freelancer->proposal_amount !!} /hr </h5>
                            @endif 
                        @else
                            <h5 class = "text-dark">{!! $profile_freelancer->getHourlyDescription() !!} </h5>
                        @endif
                    @else
                        @if($offer->payment_type == "fixed")
                            <h5 class = "text-dark"> Budget : ${!! $offer->amount !!} </h5>
                        @else
                            <h5 class = "text-dark"> ${!! $offer->amount !!} /hr </h5>
                        @endif

                        @if($offer->status == 10)
                            <p class = "text-dark"> Completed:  {!! date('M d, Y',  strtotime($offer->end_time)  ) !!}</p>
                        @endif
                    @endif
                </div>

                @if($job_tab == "proposal") 
                    @if(isset($decline))
                        <div class="text-right col-md-6 col-lg-5">
                            <div class = "col-md-12">
                                <p class = "text-dark">{!! $decline->getDeclineMessage('client', 'short') !!}</p>
                            </div>
                        </div>
                    @else
                        @php
                            $checkArchived =  $freelancer->checkArchived($job->id);
                        @endphp
                        @if($checkArchived)
                            <div class="text-right col-md-6 col-lg-5">
                                <div class = "action_btn_group" data-serial = "{!! $freelancer->serial !!}">
                                    <div class = "col-md-12">
                                        <button class="btn  btn-default btn-card-button decline-action dis-title-button" data-type = "proposal" type = "button"> 
                                            <span class = "text-mint"> Decline </span>
                                        </button> 
                                        <button class="btn  dis-title-button  btn-mint btn-card-button archiveaction actives" type = "button">
                                            UnArchive 
                                        </button>  
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-right col-md-6 col-lg-5">
                                <div class = "action_btn_group" data-serial = "{!! $freelancer->serial !!}">
                                    <div class = "col-md-12">
                                        <button class="btn  archiveaction  btn-default btn-card-button btn-rounded m-rgt-5" type = "button">
                                            <span class = "text-mint"> <i class = "fa fa-thumbs-o-down"></i> </span>
                                        </button>
                                        @php
                                            $checkShortlist = $freelancer->checkShortlist($job->id);
                                        @endphp
                                        <button class="btn shortlistaction @if($checkShortlist) actives @endif  btn-default btn-card-button btn-rounded mar-rgt" type = "button">
                                            <span class = "text-mint">
                                                @if($checkShortlist)
                                                    <i class = "fa fa-thumbs-up"></i>
                                                @else
                                                    <i class = "fa fa-thumbs-o-up"></i>
                                                @endif
                                            </span>
                                        </button> 
                                        @php
                                            $messageList = $job->getMessageList( $freelancer->id );
                                        @endphp 
                                        <a  @if(isset($messageList)) href = "{!! route('messages') !!}?room={!! $messageList->serial !!}"    @else href = "{!! route('employer.jobs.mainaction.user', [$job->serial, $freelancer->serial]) !!}"    @endif  class="btn  btn-default btn-card-button dis-title-button" type = "button">
                                            <span class = "text-mint"> Message </span>
                                        </a>
                                        @php
                                            $enable_hire  = 1;
                                            if(isset($decline))
                                                $enable_hire = 0;
                                            
                                            if($enable_hire){
                                                $offer = $freelancer->checkOffer($job->id); 
                                                if(isset($offer))
                                                    $enable_hire = 0;
                                            }
                                        @endphp
                                        <a @if(!$enable_hire) href = "#" @else href = "{!! route('employer.jobs.sendoffer', [$job->serial, $freelancer->serial]) !!}" @endif  @if(!$enable_hire) disabled @endif  class="btn dis-title-button  btn-mint btn-card-button" > 
                                            Hire 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif

                @if($job_tab == "invite")
                    <div class="text-right col-md-6 col-lg-5" >
                        <div class = "action_btn_group" data-serial = "{!! $freelancer->serial !!}">
                            <div class = "col-md-2">
                                @php
                                    $checkSaved = $freelancer->checkSaved( Auth::user()->id );
                                @endphp
                                <button class="btn   @if($checkSaved) remove_savefreelancer @else save_freelancer_action    @endif  btn-default btn-card-button btn-rounded" type = "button">
                                    <span class = "text-mint"> 
                                        @if($checkSaved)
                                            <i class = "fa fa-heart"></i>
                                        @else
                                            <i class = "fa fa-heart-o"></i>
                                        @endif
                                    </span>
                                </button>
                            </div>

                            @if($subrequest == "pending")
                            <div class = "col-md-5" >
                                <button class="btn btn-block  btn-mint  btn-card-button  decline-action" data-type = "invite" type = "button">
                                    Decline  
                                </button>
                            </div> 
                            @endif
                             
                            <div class = "col-md-5" >
                                <a class="btn btn-block   btn-card-button"  href = "{!! route('employer.jobs.createoffer', $freelancer->serial) !!}?job_id={!! $job->serial !!}" >
                                    <span class = "text-mint"> Hire </span>
                                </a>
                            </div>

                            @if($subrequest != "pending")
                            <div class = "col-md-5" >
                                @if($freelancer->checkInvited($job->id))
                                <button class="btn btn-block btn-default btn-card-button" disabled type = "button"> 
                                   <i class = "fa fa-check"></i>  Invited 
                                </button>
                                @else
                                    <button class="btn btn-block btn-invite-action  btn-mint btn-card-button" type = "button"> 
                                        Invite to Job 
                                    </button>
                                @endif
                            </div> 
                            @endif


                        </div>
                    </div>
                @endif 
                @if($job_tab == "hired")
                    <div class="text-right col-md-6 col-lg-5" >
                        <div class = "action_btn_group" data-serial = "{!! $freelancer->serial !!}">
                            @if($subrequest == "offers")
                                @if(isset($decline))  
                                    <div class="text-right col-md-5 col-lg-5">
                                        <div class = "col-md-12">
                                            <p class = "text-dark">{!! $decline->getDeclineMessage('client', 'short') !!}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class = "col-md-5" > 
                                        <button type = "button"  class="btn btn-block btn-default btn-card-button decline-action" data-type = "offer"  type = "button">
                                            <span class = "text-mint"> Decline Offer </span>
                                        </button>
                                    </div>  
                                @endif
                                <div class = "col-md-5">
                                    <a href = "{!! route('employer.jobs.viewoffer',  $freelancer->offer_serial) !!}" class="btn btn-block  btn-mint btn-card-button" > 
                                        View Contract 
                                    </a>
                                </div>      
                            @else
                                <div class = "col-md-5" > 
                                    <a href = "{!! route('employer.contract_details', $offer->serial) !!}"  type = "button"  class="btn btn-block btn-mint btn-card-button" type = "button">
                                        View Details
                                    </a>
                                </div>
                            @endif
                        </div>                                                                         
                    </div>
                @endif 
                @if($job_tab == "proposal")
                    <div class = "col-xs-12"> 
                        @if(isset($messageList))
                            @php
                                $first_message = $messageList->getLastMessage($freelancer->id); 
                            @endphp 
                            @if(isset($first_message))
                            <small class = "pt-14"> <i class = "fa fa-envelope"></i> Received  {!! $first_message->created_at->diffForhumans() !!}. 
                                @if(strlen($first_message->message_content) > 30 )
                                    {!!  substr($first_message->message_content, 0, 30)  !!} ...
                                @else
                                    {!!   $first_message->message_content  !!} 
                                @endif
                            </small>
                            @endif                                         
                        @endif
                        <p class = "text-dark ellipsis-multiline mar-top-5"> <strong> Cover Letter </strong>{{ $freelancer->coverletter }}</p>
                    </div>
                @endif 
                @if($job_tab == "invite")
                    @if($subrequest == "suggested") 
                        @php
                            $role_skills = $profile_freelancer->getRoleSkills(); 
                        @endphp
                        @if(count($role_skills))
                            <div class = "col-xs-12"> 
                                @foreach($role_skills as $role_skill_index => $role_skill)
                                    @if($role_skill_index <= 3) 
                                        <span class="badge pad-rgt">{!! $role_skill->skill !!}</span>
                                    @endif
                                @endforeach 
                                @if( count($role_skills) > 3)
                                    <a href = "#" class="btn-link text-mint text-bold pad-rgt">{!! ( count($role_skills) - 3 ) !!} more</a>
                                @endif
                            </div>
                        @endif
                    @endif
                    @if($subrequest == "saved")
                        <div class = "col-xs-12 action_btn_group" data-serial = "{!!  $freelancer->serial !!}"> 
                            <p class = "text-dark ellipsis-multiline mar-top-5">{{ $profile_freelancer->description }}</p> 
                            <a  href = "javascript:void(0)" class = "btn-link save_freelancer_action  mar-top-5"><p class = "text-dark ellipsis-multiline mar-top-5"><i class = "fa fa-heart text-mint"></i> <strong> Note-</strong>{{ $freelancer->notes }}</p></a> 
                        </div>
                    @endif
                @endif 
               
            </div> 
        </div>
    </div> 
</section>