@push('partialcss')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/> 
@endpush

<div class="tab-item-group row">
    <div class = "col-md-4 bord-rgt pad-all">
        <div class = "pad-hor">
            <h4 class = "text-dark"> View Profile </h4>  
        </div>
        <section class=" pad-btm bord-btm clearfix" style = "margin: 0 -10px;">
            <div class="view-profile-tab">
                @php
                    $roles = $freelancer->getRoles();
                @endphp
                @foreach($roles as $role_index => $role)
                <div class="view-profile-tab-item @if(!$role_index) active @endif d-flex justify-space-between" data-tab = "itemtab{!! $role->serial !!}" > 
                    <span class="font-weight-bold flex-1 mr-5 text-dark">{!! $role->role_title !!}</span> 
                    <div   class="icon-container d-flex">
                        <div   class="up-icon"  >
                            <i class = "fa   fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
                @endforeach  
            </div>
        </section>
        <section class = "up-card-section ui-profile-summary clearfix bord-btm">
            <div class = "d-flex space-between text-dark">
                <div class="col-compact">
                    <div class="pt-17 text-bold">${!! Mainhelper::getAroundAmount($freelancer->TotalEarnings()) !!}</div> 
                    <div>
                        <small class = "pt-12">Total Earnings</small>
                    </div>
                </div>
                @php
                    $total_jobs    = $freelancer->getTotalJobs();
                @endphp
                <div class="col-compact">
                    <div class="pt-17 text-bold">{!! number_format($total_jobs) !!}</div> 
                    <div>
                        <small class = "pt-12">Total Posts</small>
                    </div>
                </div> 

                @php
                    $total_hours    = $freelancer->getTotalHours('employee');
                @endphp
                <div class="col-compact">
                    <div class="pt-17 text-bold">{{ number_format($total_hours['total_worked']) }} </div> 
                    <div>
                        <small class = "pt-12">Total Hours</small>
                    </div>
                </div>   
            </div> 
        </section>

        <section class = "up-card-section bord-btm">
            <h4 class = "text-dark"> Verifications </h4> 
            @php
                $driver_license =  $freelancer->getDriverLicense(); 
            @endphp
            <div class  = "d-flex mar-btm">
                <div class = "flex-1 pt-16  @if(isset($driver_license)) text-mint   @endif"><i class = "fa fa-drivers-license"></i></div>
                <div class = "flex-4 pt-15 text-dark">Drive License Verified</div>
                <div class = "flex-1 pt-16  @if(isset($driver_license)) text-mint   @endif">
                    @if(isset($driver_license))
                        <i class = "fa fa-check"></i>
                    @else
                        <i class = "fa fa-window-minimize"></i>
                    @endif
                </div>
            </div>

            <div class  = "d-flex mar-btm">
                <div class = "flex-1 pt-16  @if($freelancer->address_verified_status == '2') text-mint @endif"><i class = "fa  fa-address-book"></i></div>
                <div class = "flex-4 pt-15 text-dark">Address Verified</div>
                <div class = "flex-1 pt-16  @if($freelancer->address_verified_status == '2') text-mint @endif">                    
                    @if($freelancer->address_verified_status == '2')
                        <i class = "fa fa-check"></i>
                    @else
                        <i class = "fa fa-window-minimize"></i>
                    @endif
                </div>
            </div>

            @php
                $bank_information =  $freelancer->getBankInformation(); 
            @endphp
            
            <div class  = "d-flex mar-btm">
                <div class = "flex-1 pt-16 @if(isset($bank_information)) text-mint   @endif"><i class = "fa fa-bank"></i></div>
                <div class = "flex-4 pt-15 text-dark">Bank Info Verified</div>
                <div class = "flex-1 pt-16 @if(isset($bank_information)) text-mint   @endif">
                    @if(isset($bank_information))
                        <i class = "fa fa-check"></i>
                    @else
                        <i class = "fa fa-window-minimize"></i>
                    @endif
                </div>
            </div>

        </section>

    </div>
    <div class = "col-md-8">
        <div class = "clearfix bord-btm">
            @foreach($roles as $role_index => $role)
                <section class = "tab-item-content @if(!$role_index) active @endif  itemtab{!! $role->serial !!}" data-tab = "{!! $role->serial !!}">
                    <div class  = "pad-all">
                        <div class = "md-btm">
                            <div class = "row">
                                <div class = "col">
                                    <h3 class = "text-dark mar-top"> 
                                        {!! $role->role_title !!} 
                                    </h3>
                                </div> 
                                <div class = "d-flex align-items-center col col-auto ">
                                    <h4 class = "text-dark mar-top"> 
                                        {!! $role->getHourlyDescription() !!} 
                                    </h4>
                                </div>
                            </div>
                        </div> 
                        <div class = "row">
                            <div class = "col-md-10 profile-detail-group">
                                <div class = "up-line-clamp-v2 profile-detail" style = "-webkit-line-clamp: 5;">
                                    <span class  = "text-pre-line pt-14 ">{!! $role->description !!}</span>
                                </div> 
                                <a href = "javascript:void(0)" data-expanded="false" class="pad-top  text-mint btn-link text-bold updownmore_buttton hidden"> 
                                    more
                                </a>
                            </div> 
                            <div class = "col-md-12 mar-top">
                                @php
                                    $role_skills = $role->getRoleSkills(); 
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
        @php
            $job_histories = $freelancer->getJobHistoyList();
        @endphp
        @if(count($job_histories))
            <section class = "clearfix">
                <div class = "pad-all">
                    <div class = "row">
                        <div class = "col-xs-12 bord-btm">
                            <h4 class = "text-dark"> Work History </h4>
                        </div> 
                        @foreach($job_histories as $job_history)
                        <div class = "col-xs-12 mar-btm pad-btm bord-btm">
                            <h4  role="presentation" class="mb-10 pt-17">
                                <a href = "javascript:void(0)" tabindex="0" class="text-mint btn-link text-bold">
                                    {!! $job_history->headline !!}
                                </a>
                            </h4>
                            @if($job_history->status  != 10)
                                <div class = "job-in-progress">
                                    <p> {!! date('M Y', strtotime($job_history->start_time ))!!} - Present </p>
                                    <p> Job in progress </p>
                                </div>
                            @else 
                                @php
                                    $feedback = $job_history->getFeedback( $freelancer->id );
                                @endphp
                                <div class = "job-completed row mar-top">
                                    @if(isset($feedback))
                                    <div class = "col-md-12">
                                        <span class = "total-rating" data-star = '{!! $feedback->rate_total !!}'></span> 
                                        <strong class = "pt-14 text-dark m-rgt-5"> {!! $feedback->rate_total !!} </strong>
                                        <small class="text-muted pt-14">
                                            {!! date('M d, Y', strtotime($job_history->start_time ))!!} 
                                            <span>
                                                -
                                                <span>{!! date('M d, Y', strtotime($job_history->start_time ))!!}</span>
                                            </span>
                                        </small> 
                                    </div> 
                                    <div class = "col-md-12">
                                        <p class="mb-0 break text-pre-line text-dark pt-14">
                                            @if($feedback->message)
                                            <em>"{{ $feedback->message }}"</em> 
                                            @endif
                                        </p>
                                    </div> 
                                    @else
                                        <div class = "col-md-12"> 
                                            <small class="text-muted pt-14">
                                                    {!! date('M d, Y', strtotime($job_history->start_time ))!!} 
                                                <span>
                                                    -
                                                    <span> {!! date('M d, Y', strtotime($job_history->start_time ))!!} </span>
                                                </span>
                                            </small> 
                                        </div> 
                                        <div class = "col-md-12">
                                            <p class="mb-0 break text-pre-line text-dark pt-14">
                                                No feedback given
                                            </p>
                                        </div>
                                    @endif
                                </div> 
                            @endif

                            @if($job_history->payment_type == "hourly")
                                <div class = "row mar-top">
                                    <div class = "col-xs-4 text-dark"> <strong> ${!! number_format($job_history->getTotalPaid(), 2) !!} </strong> </div>
                                    <div class = "col-xs-4 text-dark"> <strong> ${!! $job_history->amount !!} / hr </strong></div>
                                    @php
                                        $total_start_time       =   $job_history->totalTimeHours('since_start');
                                        list($hour, $minute)    =   explode(':', $total_start_time );
                                        $hour                   =  (int) $hour;
                                    @endphp
                                    <div class = "col-xs-4 text-dark"> <strong> {!! $hour !!} @if($hour == 1) hr @else hrs @endif </strong></div>
                                </div>
                            @else
                                <div class = "row mar-top">
                                    <div class = "col-xs-4 text-dark"> <strong> ${!! number_format($job_history->getTotalPaid(), 2) !!} </strong> </div>
                                    <div class = "col-xs-4 pt-14"> Fixed Price </div> 
                                </div>
                            @endif

                        </div>
                        @endforeach
                    </div> 
                </div>
            </section>
        @endif


    </div>
</div>

@push('partialscripts')
    <script src="{{asset('plugins/simplerating/jquery.star-rating-svg.js')}}"></script>
    <script> 
        $(document).on("click", ".view-profile-tab-item", function(){
			var tab_id = $(this).attr('data-tab');
			$(".view-profile-tab-item").removeClass("active");
			var obj = $(this).closest(".tab-item-group");
			$(this).addClass("active"); 
			obj.find(".tab-item-content").removeClass("active");
			obj.find(".tab-item-content." + tab_id).addClass("active");
		});

        $(".total-rating").each(function(){
            $(this).starRating({
                initialRating: $(this).attr('data-star'),
                starSize:   16,
                totalStars: 5,  
                disableAfterRate: false,
                readOnly: true
            });
        }); 

    </script>
@endpush