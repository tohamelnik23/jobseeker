@extends('layouts.app')
@section('title', 'Contracts') 
@section('css') 
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            @include('partial.alert')
        </div>
        <div class = "col-md-12">
            <h2 class="text-main pad-btm mar-no  pad-lft text-semibold text-left text-dark">  
                My Gigs
            </h2>
        </div>
        <div class="col-md-12">
			<div class="card mar-ver">
				<div class="card-body pad-no">
                    <header class="pad-all bord-btm">
                        <div class = "row">
                            <div class = "col-md-6">
                                <h3 class="text-main pad-btm mar-no  pad-lft text-semibold text-left text-dark">  
                                    Active Contracts
                                </h3>
                            </div>
                            @if(count($contracts)) 
                            <div class = 'col-md-6'>
                                <div class="input-group mar-btm">
                                    <input type="text" placeholder="Search contract" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn btn-mint" type="button"><i class="fa fa-search"></i></button>
                                    </span>
                                </div> 
                            </div>
                            @endif
                        </div>
                    </header>
                    @if(count($contracts)) 
                        @php
                            $count_hourly_contract       =  Auth::user()->countContract('hourly');
                            $count_active_milestones     =  Auth::user()->countContract('active_milestone');
                            $count_awaiting_milestones   =  Auth::user()->countContract('awaiting_milestones');
                            $count_payment_requests      =  Auth::user()->countContract('payment_requests');
                        @endphp 
                    <div class="panel mar-top freelancer_card_panel"> 
                        <div class="panel-heading bord-btm">
                            <div class="panel-control pull-left">
                                <ul class="nav nav-tabs">
                                    <li class = "active"><a href="#contract-details-all" data-toggle="tab"> ALL </a></li> 
                                    @if($count_hourly_contract)
                                        <li class = ""><a href="#contract-details-hourly"  data-toggle="tab" class = ""> HOURLY ({!! $count_hourly_contract !!}) </a></li>
                                    @endif
                                    <li class = ""><a href="#contract-details-active_milestones" data-toggle="tab" class = ""> ACTIVE MILESTONES ({!! $count_active_milestones !!}) </a></li>
                                    <li class = ""><a href="#contract-details-awaiting_milestones" data-toggle="tab" class = ""> AWAITING MILESTONES ({!! $count_awaiting_milestones !!}) </a></li>
                                    <li class = ""><a href="#contract-details-payment-request" data-toggle="tab" class = ""> PAYMENT REQUEST ({!! $count_payment_requests !!}) </a></li>
                                </ul>
                            </div>
                        </div> 
                        <div class="panel-body pad-no">
                            <div class="tab-content">
                                <div class="tab-pane fade   active in " id = "contract-details-all">
                                    @foreach($contracts as $contract)
                                        @php
                                            $posted_job     =  $contract->getJob();
                                            $client         =  $contract->getClient();
                                            $messagelist    =  $contract->getMessageList();  
                                        @endphp  
                                        <div class = "posting-item clearfix  pad-hor">
                                            <div class = "row m-sm-top">
                                                <div class = "col-md-4 col-sm-6">
                                                    <div class="opening-counts-value">
                                                        <a href="{!! route('jobs_contract_details', $contract->serial) !!}">
                                                            <strong class="text-dark">{!!  $contract->contract_title  !!}</strong>  
                                                        </a>
                                                    </div> 
                                                    <p class="mar-top-5  text-dark"> Hired by  {!! $client->accounts->name !!}  </p>  
                                                    @if($contract->status == 8)
                                                        <p class="mar-top-5  text-danger">
                                                            This contract is in disupte
                                                        </p>
                                                    @elseif($contract->status == 2)
                                                        <p class="mar-top-5  text-danger">
                                                            This contract is paused
                                                        </p>
                                                    @endif
                                                </div>   
                                                <div class = "col-md-4 col-sm-4">
                                                    @if($contract->payment_type == "fixed")
                                                        @php
                                                            $first_milestone =  $contract->getActiveMilestone();
                                                            $total_paid      =  $contract->getTotalPaid(); 
                                                        @endphp 
                                                        @if(isset($first_milestone))
                                                            <div class="clearfix mar-btm-5">
                                                                <span class = "text-bold text-dark"> ${!! $first_milestone->amount !!} </span>
                                                                <span class = "pad-lft-5"> funded </span>  
                                                            </div> 
                                                            <div class="clearfix mar-btm-5">
                                                                <span class = "text-bold text-dark">
                                                                    Milestone: {!! $first_milestone->headline  !!}
                                                                </span> 
                                                            </div>
                                                            <div class="clearfix mar-btm-5">
                                                                <span class = "text-bold text-dark">
                                                                    ${!! $total_paid  !!} paid of ${!! $contract->amount  !!}
                                                                </span> 
                                                            </div>
                                                        @endif 
                                                    @endif
                                                    @if($contract->payment_type == "hourly")
                                                        @php
                                                            $total_time_hour        =   $contract->totalTimeHours('this_week');
                                                            list($hour, $minute)    =   explode(':', $total_time_hour );
                                                            $hour                   =   (int) $hour;
                                                        @endphp
                                                        <div class="clearfix mar-btm-5">
                                                            <span class = "text-bold text-dark"> ${!! number_format($contract->getTotalPaid(), 2) !!} | {!! $contract->amount !!}/hr </span> 
                                                        </div>
                                                        <p class="mar-top-5 mar-btm-5  text-mint"> {!! $hour !!} @if($hour == 1) hr @else hrs @endif  this week </p> 
                                                    @endif
                                                </div>
                                                <div class = "col-md-4 col-sm-4 js-stop-propagation text-right"> 
                                                    <div class="dropdown">
                                                        <a href = "{!!  route('messages', $messagelist->serial)  !!}?room={!! $client->serial !!}" class = "mar-rgt btn btn-mint">Send Message</a> 
                                                        <!--button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                                            <span class = "text-bold text-dark">  ... </span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-lg"> 
                                                            <li><a href="#">Submit work / Request $</a></li> 
                                                            <li class="divider"></li> 
                                                            <li><a href="#">Propose new contract</a></li>
                                                        </ul-->
                                                    </div> 

                                                </div> 
                                            </div>
                                        </div> 
                                    @endforeach
                                </div>

                                @if($count_hourly_contract)
                                    <div class="tab-pane fade" id = "contract-details-hourly">
                                        @foreach($contracts as $contract)
                                            @if($contract->payment_type == "hourly")
                                                @php
                                                    $posted_job     =  $contract->getJob();
                                                    $client         =  $contract->getClient();
                                                    $messagelist    =  $contract->getMessageList();   
                                                @endphp 
                                                <div class = "posting-item clearfix  pad-hor">
                                                    <div class = "row m-sm-top">
                                                        <div class = "col-md-4 col-sm-6">
                                                            <div class="opening-counts-value">
                                                                <a href="{!! route('jobs_contract_details', $contract->serial) !!}">
                                                                    <strong class="text-dark"> {!!  $contract->contract_title  !!}  </strong>  
                                                                </a>
                                                            </div>
                                                            <p class="mar-top-5  text-dark"> Hired by  {!! $client->accounts->name !!}  </p>  
                                                            @if($contract->status == 3)
                                                                <p class="mar-top-5  text-danger">
                                                                    This contract is in disupte
                                                                </p>
                                                            @elseif($contract->status == 2)
                                                                <p class="mar-top-5  text-danger">
                                                                    This contract is paused
                                                                </p>
                                                            @endif
                                                        </div> 
                                                        <div class = "col-md-4 col-sm-4">  
                                                            @php
                                                                $total_time_hour        =   $contract->totalTimeHours('this_week');
                                                                list($hour, $minute)    =   explode(':', $total_time_hour );
                                                                $hour                   =   (int) $hour;
                                                            @endphp
                                                            <div class="clearfix mar-btm-5">
                                                                <span class = "text-bold text-dark"> ${!! number_format($contract->getTotalPaid(), 2) !!} | {!! $contract->amount !!}/hr </span> 
                                                            </div>
                                                            <p class="mar-top-5 mar-btm-5  text-mint"> {!! $hour !!} @if($hour == 1) hr @else hrs @endif  this week </p>  
                                                        </div>
                                                        <div class = "col-md-4 col-sm-4 js-stop-propagation text-right"> 
                                                            <div class="dropdown">
                                                                <a href = "{!!  route('messages', $messagelist->serial)  !!}?room={!! $client->serial !!}" class = "mar-rgt btn btn-mint">Send Message</a> 
                                                            </div>  
                                                        </div> 
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <div class="tab-pane fade" id="contract-details-active_milestones">
                                    @if($count_active_milestones)
                                        @foreach($contracts as $contract)
                                            @if($contract->payment_type == "fixed")
                                                @php
                                                    $first_milestone = $contract->getActiveMilestone();
                                                @endphp
                                                @if(isset($first_milestone))
                                                    @php
                                                        $posted_job     =  $contract->getJob();
                                                        $client         =  $contract->getClient();
                                                        $messagelist    =  $contract->getMessageList();  
                                                    @endphp 
                                                    <div class = "posting-item clearfix  pad-hor">
                                                        <div class = "row m-sm-top">
                                                            <div class = "col-md-4 col-sm-6">
                                                                <div class="opening-counts-value">
                                                                    <a href="{!! route('jobs_contract_details', $contract->serial) !!}">
                                                                        <strong class="text-dark"> {!!  $posted_job->headline  !!}  </strong>  
                                                                    </a>
                                                                </div>
                                                                <p class="mar-top-5  text-dark"> Hired by  {!! $client->accounts->name !!}  </p>  
                                                                @if($contract->status == 8)
                                                                    <p class="mar-top-5  text-danger">
                                                                        This contract is in disupte
                                                                    </p>
                                                                @elseif($contract->status == 2)
                                                                    <p class="mar-top-5  text-danger">
                                                                        This contract is paused
                                                                    </p>
                                                                @endif
                                                            </div> 
                                                            <div class = "col-md-4 col-sm-4"> 
                                                                @php 
                                                                    $total_paid      =  $contract->getTotalPaid(); 
                                                                @endphp 
                                                                @if(isset($first_milestone))
                                                                    <div class="clearfix mar-btm-5">
                                                                        <span class = "text-bold text-dark"> ${!! $first_milestone->amount !!} </span>
                                                                        <span class = "pad-lft-5"> funded </span>  
                                                                    </div> 
                                                                    <div class="clearfix mar-btm-5">
                                                                        <span class = "text-bold text-dark">
                                                                            Milestone: {!! $first_milestone->headline  !!}
                                                                        </span> 
                                                                    </div>
                                                                    <div class="clearfix mar-btm-5">
                                                                        <span class = "text-bold text-dark">
                                                                            ${!! $total_paid  !!} paid of ${!! $contract->amount  !!}
                                                                        </span> 
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class = "col-md-4 col-sm-4 js-stop-propagation text-right"> 
                                                                <div class="dropdown">
                                                                    <a href = "{!!  route('messages', $messagelist->serial)  !!}?room={!! $client->serial !!}" class = "mar-rgt btn btn-mint">Send Message</a>  
                                                                    <!--button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                                                        <span class = "text-bold text-dark">  ... </span>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-lg"> 
                                                                        <li><a href="#">Submit work / Request $</a></li> 
                                                                        <li class="divider"></li> 
                                                                        <li><a href="#">Propose new contract</a></li>
                                                                    </ul-->
                                                                </div> 

                                                            </div> 
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        <div class = "  clearfix  pad-all">
                                            <p class = "pt-14"> You have no fixed-price contracts that are active milestones at this time. </p>
                                        </div>
                                    @endif
                                </div> 
                                <div class="tab-pane fade" id="contract-details-awaiting_milestones">
                                    @if($count_awaiting_milestones)
                                        @foreach($contracts as $contract)
                                            @if($contract->payment_type == "fixed")
                                                @php
                                                    $first_milestone = $contract->getAwaitingMilestone();
                                                @endphp
                                                @if(!isset($first_milestone))
                                                    @php
                                                        $posted_job     =  $contract->getJob();
                                                        $client         =  $posted_job->getClient();
                                                        $messagelist    =  $posted_job->getMessageList(Auth::user()->id);  
                                                    @endphp 
                                                    <div class = "posting-item clearfix  pad-hor">
                                                        <div class = "row m-sm-top">
                                                            <div class = "col-md-4 col-sm-6">
                                                                <div class="opening-counts-value">
                                                                    <a href="{!! route('jobs_contract_details', $contract->serial) !!}">
                                                                        <strong class="text-dark"> {!!  $posted_job->headline  !!}  </strong>  
                                                                    </a>
                                                                </div>
                                                                <p class="mar-top-5  text-dark"> Hired by  {!! $client->accounts->name !!}  </p>  
                                                                @if($contract->status == 3)
                                                                    <p class="mar-top-5  text-danger">
                                                                        This contract is in disupte
                                                                    </p>
                                                                @elseif($contract->status == 2)
                                                                    <p class="mar-top-5  text-danger">
                                                                        This contract is paused
                                                                    </p>
                                                                @endif
                                                            </div> 
                                                            <div class = "col-md-4 col-sm-4"> 
                                                                @php 
                                                                    $total_paid      =  $contract->getTotalPaid(); 
                                                                @endphp 
                                                                @if(isset($first_milestone))
                                                                    <div class="clearfix mar-btm-5">
                                                                        <span class = "text-bold text-dark"> ${!! $first_milestone->amount !!} </span> 
                                                                    </div> 
                                                                    <div class="clearfix mar-btm-5">
                                                                        <span class = "text-bold text-dark">
                                                                            Milestone: {!! $first_milestone->headline  !!}
                                                                        </span> 
                                                                    </div>
                                                                    <div class="clearfix mar-btm-5">
                                                                        <span class = "text-bold text-dark">
                                                                            ${!! $total_paid  !!} paid of ${!! $contract->amount  !!}
                                                                        </span> 
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class = "col-md-4 col-sm-4 js-stop-propagation text-right"> 
                                                                <div class="dropdown">
                                                                    <a href = "{!!  route('messages', $messagelist->serial)  !!}?room={!! $client->serial !!}" class = "mar-rgt btn btn-mint">Send Message</a>  
                                                                    <!--button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                                                        <span class = "text-bold text-dark">  ... </span>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-lg"> 
                                                                        <li><a href="#">Submit work / Request $</a></li> 
                                                                        <li class="divider"></li> 
                                                                        <li><a href="#">Propose new contract</a></li>
                                                                    </ul-->
                                                                </div> 

                                                            </div> 
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        <div class = "  clearfix  pad-all">
                                            <p class = "pt-14"> You have no fixed-price contracts that are awaiting milestones at this time. </p>
                                        </div>
                                    @endif
                                </div>  
                                <div class="tab-pane fade" id="contract-details-payment-request">
                                    @if($count_payment_requests)
                                        @foreach($contracts as $contract)
                                            @if($contract->payment_type == "fixed")
                                                @php
                                                    $submitwork = $contract->getSubmitWork();
                                                @endphp
                                                @if(isset($submitwork))
                                                    @php
                                                        $posted_job     =  $contract->getJob();
                                                        $client         =  $contract->getClient();
                                                        $messagelist    =  $contract->getMessageList();  
                                                    @endphp 
                                                    <div class = "posting-item clearfix  pad-hor">
                                                        <div class = "row m-sm-top">
                                                            <div class = "col-md-4 col-sm-6">
                                                                <div class="opening-counts-value">
                                                                    <a href="{!! route('jobs_contract_details', $contract->serial) !!}">
                                                                        <strong class="text-dark"> {!!  $posted_job->headline  !!}  </strong>  
                                                                    </a>
                                                                </div>
                                                                <p class="mar-top-5  text-dark"> Hired by  {!! $client->accounts->name !!}  </p>  
                                                                @if($contract->status == 8)
                                                                    <p class="mar-top-5  text-danger">
                                                                        This contract is in disupte
                                                                    </p>
                                                                @elseif($contract->status == 2)
                                                                    <p class="mar-top-5  text-danger">
                                                                        This contract is paused
                                                                    </p>
                                                                @endif
                                                            </div> 
                                                            <div class = "col-md-4 col-sm-4"> 
                                                                @php 
                                                                    $first_milestone =  $contract->getActiveMilestone();
                                                                    $total_paid      =  $contract->getTotalPaid(); 
                                                                @endphp 
                                                                @if(isset($first_milestone))
                                                                    <div class="clearfix mar-btm-5">
                                                                        <span class = "text-bold text-dark"> ${!! $first_milestone->amount !!} </span>
                                                                        <span class = "pad-lft-5"> funded </span>  
                                                                    </div> 
                                                                    <div class="clearfix mar-btm-5">
                                                                        <span class = "text-bold text-dark">
                                                                            Milestone: {!! $first_milestone->headline  !!}
                                                                        </span> 
                                                                    </div>
                                                                    <div class="clearfix mar-btm-5">
                                                                        <span class = "text-bold text-dark">
                                                                            ${!! $total_paid  !!} paid of ${!! $contract->amount  !!}
                                                                        </span> 
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class = "col-md-4 col-sm-4 js-stop-propagation text-right"> 
                                                                <div class="dropdown">
                                                                    <a href = "{!!  route('messages', $messagelist->serial)  !!}?room={!! $client->serial !!}" class = "mar-rgt btn btn-mint">Send Message</a>  
                                                                    <!--button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                                                        <span class = "text-bold text-dark">  ... </span>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-lg"> 
                                                                        <li><a href="#">Submit work / Request $</a></li> 
                                                                        <li class="divider"></li> 
                                                                        <li><a href="#">Propose new contract</a></li>
                                                                    </ul-->
                                                                </div> 

                                                            </div> 
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        <div class = "clearfix  pad-all">
                                        <p class = "pt-14"> You have no fixed-price contracts payment requests at this time. </p>
                                        </div>
                                    @endif
                                </div>  
                            </div>
                        </div> 
                    </div> 
                    @else
                        <div class = "row pad-all">
                            <div class = "col-md-12 pad-all">
                                <p class = "text-dark pt-15">
                                    Contracts you're actively working on will appear here. 
                                    <a href = "{!! route('search') !!}" class = "text-mint btn-link"> Start searching for new projects now! </a>
                                </p>
                            </div>
                        </div>
                    @endif

				</div>
			</div> 
		</div>


    </div>
</div>
@endsection
@section('javascript')
<script>
     
</script>
@stop