@extends('layouts.app')
@section('title', 'Contract Details') 
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>  
<link href="{{ asset('plugins/bootstrap-timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@php
    $job            =   $offer->getJob();
    $client         =   $offer->getClient();  
    $message_list   =   $offer->getMessageList( );
    if($offer->status == 10){
        $client_feedback        =  $offer->getFeedback( $client->id);
        $freelancer_feedback    =  $offer->getFeedback(  Auth::user()->id );
    }
@endphp 
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            @include('partial.alert')
        </div> 

        @if($offer->status == 10)
        <div class = "col-md-12 mar-top text-right">  
            <button type = "button" class = "btn btn-mint btn-card-button dis-title-button">Propose New Contract</button>        
        </div>
        @endif

        <div class = "col-md-12 mar-top">  
            <div class = "card freelancer_card">
                <div class = "card-body pad-no">
                    <div class = "row">
                        <div class = "col-md-12">
                            <div class = "clearfix pad-all">
                                <div class = "row pad-hor">
                                    <div class = "col-md-7 col-sm-6">
                                        <h3 class = "text-dark one-line-limit-noblock"> {!! $offer->contract_title !!}</h3> 
                                        @if($offer->status == 1)
                                            <p> Active since {!! date('M d, Y', strtotime($offer->start_time))  !!} </p>
                                        @endif 
                                    </div>
                                    <div class = "col-md-5 col-sm-6 d-flex"> 
                                        <div class = "pad-top">
                                            <img class="img-circle img-md" src="{!! $client->getImage() !!}"> 
                                        </div>
                                        <div class = "pad-top">
                                            <h4 class = "text-dark"> {!! $client->accounts->name !!} </h4>
                                            <!--p class = "pt-15"> 3:06 PM Wed</p -->
                                        </div>
                                    </div>  
                                    @if($offer->status == 10 )
                                        <div class = "col-md-12">
                                            <p class = "text-dark mar-no"> 
                                                <img src = "{!! asset('img/checkbox.png') !!}" height = 15 /> Completed  {!! date('M d',  strtotime($offer->end_time)  ) !!} - 
                                                @if(!isset($client_feedback))
                                                    <a  href = "{!! route('employee.contract.leavefeedback', $offer->serial)  !!}">   Give Feedback </a>
                                                    <span class = "mar-lft total-rating" data-star = '0'></span>
                                                @else
                                                    <span class = "mar-lft total-rating" data-star = '{!! $client_feedback->rate_total !!}'></span>
                                                @endif
                                            </p>
                                        </div>
                                    @elseif($offer->status == 2)
                                        <div class = "col-md-12 mar-top">
                                            <p class = "text-dark mar-no ">
                                                <span class = "text-danger text-bold"> <i>This offer is paused</i> </span>
                                            </p>
                                        </div>
                                    @endif 
                                </div>
                            </div>  
                        </div>
                        <div class = "col-md-12">
                            <div class="panel freelancer_card_panel">
                                <div class="panel-heading bord-btm">
                                    <div class="panel-control pull-left">
                                        <ul class="nav nav-tabs">
                                            <li class = "active "><a href = "#contract-details-timeearnings" data-toggle="tab" class = "text-uppercase"> Time & Earnings </a></li>  
                                            <li class = ""><a  href = "{!! route('messages') !!}?room={!! $message_list->serial !!}"    class = "text-uppercase"> Messages </a></li>
                                            <li class = ""><a href = "#contract-details-terms_settings" data-toggle="tab" class = "text-uppercase"> Terms & Settings </a></li>
                                            <li class = ""><a href = "#contract-details-feedback" data-toggle="tab" class = "text-uppercase"> Feedback </a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body pad-no">
                                    <div class="tab-content">
                                        <div class="tab-pane fade   active in " id="contract-details-timeearnings">
                                            <div class = "pad-all clearfix bord-btm">
                                                <div class = "d-flex pad-hor">
                                                    <div class = "flex-1">
                                                        <p class = "pt-15 text-bold text-dark">Last 24hrs</p>
                                                        <p class = "mar-no">
                                                            <span class = "pt-30 text-dark">{!! $offer->totalTimeHours('last_24') !!}</span> 
                                                            <span class = "pt-25"> hrs </span> 
                                                        </p>
                                                        @php
                                                            $last_timesheet = $offer->getLastTimeSheet(); 
                                                        @endphp
                                                        @if(isset($last_timesheet))
                                                            <p class = "pt-13">Last worked {!! \Carbon\Carbon::createFromTimeStamp(strtotime($last_timesheet->timesheets_date))->diffForHumans() !!} 
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class = "flex-1">
                                                        <p class = "pt-15 text-bold text-dark">This week </p>
                                                        <p class = "mar-no">
                                                            <span class = "pt-30 text-dark">{!! $offer->totalTimeHours('this_week') !!}</span> 
                                                            <span class = "pt-25"> hrs </span> 
                                                        </p>
                                                        <p class = "pt-13">of {!! $offer->weekly_limit !!}hr limit</p>
                                                     </div>
                                                    <div class = "flex-1">
                                                        <p class = "pt-15 text-bold text-dark">Last Week</p>
                                                        <p class = "mar-no">
                                                            <span class = "pt-30 text-dark">{!! $offer->totalTimeHours('last_week') !!}</span> 
                                                            <span class = "pt-25"> hrs </span> 
                                                        </p>
                                                        @php
                                                            $last_week_paid = $offer->getLastWeekPaid(); 
                                                        @endphp 
                                                        <p class = "pt-13">${!!  number_format($last_week_paid, 2) !!}</p>
                                                    </div> 
                                                    <div class = "flex-1 pad-hor">
                                                        <p class = "pt-15 text-bold text-dark">Since Start</p>
                                                        <p class = "mar-no">
                                                            <span class = "pt-30 text-dark">{!! $offer->totalTimeHours('since_start') !!}</span> 
                                                            <span class = "pt-25"> hrs </span> 
                                                        </p>
                                                        @php
                                                            $total_week_paid = $offer->getLastWeekTotalPaid(); 
                                                        @endphp
                                                        <p class = "pt-13">${!!  number_format($total_week_paid, 2) !!}</p>
                                                    </div>
                                                </div> 
                                            </div>

                                            <div class = "pad-all clearfix bord-btm">
                                                <div class = " pad-hor">
                                                    <div class = "row">
                                                        <div class = "col-xs-6">
                                                            <h4 class = "text-dark">
                                                                Timesheet 
                                                            </h4>
                                                        </div> 
                                                        <div class = "col-xs-6 text-right">
                                                            @php
                                                                $week_value =   $offer->getTimeSheet(Carbon\Carbon::now()->toDateString());  
                                                            @endphp
                                                            <div class="btn-group btn-group">
                                                                <button class="btn btn-default text-dark timesheet_button prev_week"  @if($week_value['prev_week'] == "") disabled @endif  type="button" data-date = "{!! $week_value['prev_week'] !!}" >
                                                                    <i class="demo-psi-arrow-left"></i>
                                                                </button>
                                                                <button class="btn btn-default text-dark timesheet_button this_week" type="button" data-date = "{!! $week_value['curr_week'] !!}">
                                                                    This Week
                                                                </button>
                                                                <button class="btn btn-default text-dark timesheet_button next_week" disabled type="button" data-date = "{!! $week_value['next_week'] !!}">
                                                                    <i class="demo-psi-arrow-right"></i>
                                                                </button>
                                                            </div>
                                                        </div>  
                                                    </div>  
                                                </div> 
                                            </div>

                                            <div class = "pad-all clearfix bord-btm">
                                                <div class="table-responsive">
                                                    <table class="table table-striped text-dark timesheet_table" style = "table-layout: fixed;"> 
                                                    </table>
                                                </div>
                                            </div>
                                            @php
                                                $getAdditionalEarnings  = $offer->getAllTimesheets();
                                            @endphp
                                            <div class = "pad-all clearfix mar-btm">   
                                                <div class = " pad-hor"> 
                                                    <h4 class = "text-dark"> All timesheets and earnings </h4>  
                                                    <div class="table-responsive mar-top">
                                                        <table class="table ">
                                                            <thead>
                                                                <tr>
                                                                    <th class = "text-dark text-bold">Date</th>
                                                                    <th class = "text-dark text-bold">Description</th>
                                                                    <th class = "text-dark text-bold">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($getAdditionalEarnings as $getAdditionalEarning)
                                                                    <tr>
                                                                        <td class = "text-dark">{!! date('M d, Y', strtotime($getAdditionalEarning->created_at)) !!}  </td>
                                                                        <td  class = "text-dark">{!! $getAdditionalEarning->type !!}</td>
                                                                        <td class = "text-dark">${!! $getAdditionalEarning->amount  !!}</td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td class = "text-center" colspan = "3">
                                                                            There are no timesheets.
                                                                        </td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>  
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="tab-pane fade" id="contract-details-terms_settings">
                                            <div class = "row">
                                                <div class = "col-md-6 col-sm-6 bord-rgt">
                                                    <div class = "pad-all clearfix bord-btm">
                                                        <div class = "pad-hor">
                                                            <h4 class = "text-dark"> Contract Info </h4>
                                                        </div>
                                                        <div class = "pad-all">
                                                            <div class = "col-xs-12 mar-btm">
                                                                <div class = "col-xs-6">
                                                                    <h5 class = "text-dark"> Rate </h5>
                                                                </div>
                                                                <div class = "col-xs-6">
                                                                    <p class = "text-dark pt-14">  ${!!  $offer->amount  !!} /hr </p>
                                                                </div>
                                                            </div> 
                                                            <div class = "col-xs-12 mar-btm">
                                                                <div class = "col-xs-6">
                                                                    <h5 class = "text-dark"> Weekly Limit </h5>
                                                                </div>
                                                                <div class = "col-xs-6">
                                                                    <p class = "text-dark pt-14"> {!!  $offer->weekly_limit  !!} hrs/week  </p>
                                                                </div>
                                                            </div>  
                                                            <div class = "col-xs-12"> 
                                                                <div class = "col-xs-6">
                                                                    <h5 class = "text-dark"> Start Date </h5>
                                                                </div>
                                                                <div class = "col-xs-6">
                                                                    <p class = "text-dark">  {!!  date('F d, Y', strtotime($offer->start_time))   !!}  </p>
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div> 
                                                    <div class = "pad-all clearfix"> 
                                                        <div class = "pad-all">
                                                            <div class = "col-xs-12 mar-btm">
                                                                <div class = "col-xs-6">
                                                                    <h5 class = "text-dark">  Contract ID </h5>
                                                                </div>
                                                                <div class = "col-xs-6">
                                                                    <p class = "text-dark mar-top-5"> {!! $offer->serial !!} </p>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class = "col-md-6 col-sm-6">
                                                    <div class = "pad-all clearfix bord-btm">
                                                        <div class = "pad-hor">
                                                            <h4 class = "text-dark"> Description of work </h4>
                                                        </div>
                                                        <div class = "pad-hor description_item_card">  
                                                            @if(strlen($offer->work_details) > 170 )
                                                            <div class = "description-shortlist description-part"> 
                                                                <span class="pt-14 text-dark ">
                                                                    {!! substr($offer->work_details, 0, 170) !!} ...
                                                                </span>
                                                                <a class="btn-link text-mint btn-description-action more" href="javascript:void(0)"> more </a> 
                                                            </div> 
                                                            <div class = "description-detaillist hidden description-part"><span class="pt-14 text-dark">{!! $offer->work_details !!}</span><a class="btn-link btn-description-action text-mint less" href="javascript:void(0)"> less </a> 
                                                            </div>
                                                            @else
                                                            <div class = "description-shortlist description-part"> 
                                                                <span class="pt-14 text-dark ">
                                                                    {{ $offer->work_details }}
                                                                </span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div> 
                                                    <div class = "pad-all clearfix bord-btm">
                                                        <ul class="ats-actions list-unstyled pad-hor">
                                                            <li class="m-sm-bottom">
                                                                <a  href = "{!! route('jobs_offer_details', $offer->serial) !!}" class="btn-link text-mint">
                                                                    <i class="fa fa-share-square-o vertical-align-middle m-0-left  mar-rgt pt-15"></i>View Offer
                                                                </a>
                                                            </li> 
                                                            @php
                                                                $proposal = $offer->getProposal();
                                                            @endphp 
                                                            @if(isset($proposal))
                                                            <li class="m-sm-bottom">
                                                                <a target = "_blank" href = "{!! route('jobs_proposal_details', $proposal->serial) !!}" class="btn-link text-mint">
                                                                    <i class="fa fa-share-square-o vertical-align-middle m-0-left  mar-rgt pt-15"></i>View original proposal
                                                                </a>
                                                            </li>
                                                            @endif 
                                                            @if(isset($job))
                                                            <li class="m-sm-bottom">
                                                                <a target = "_blank" href = "{!! route('jobs_details', $job->serial) !!}" class="btn-link text-mint text-capitalize">
                                                                    <i class="fa fa-share-square-o vertical-align-middle m-0-left  mar-rgt pt-15"></i>
                                                                    View original {{ $job->type }}  posting
                                                                </a> 
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="contract-details-feedback">                                             
                                            @if($offer->status == 10)
                                                <div class = "row mar-all pad-all">
                                                    <div class = "col-md-6 col-sm-6">
                                                        <h4 class = "text-dark">Clients Feedback to You</h4>
                                                        @if(isset($freelancer_feedback))
                                                            @if(isset($client_feedback))
                                                                <p  class = "total-rating" data-star = '{!! $freelancer_feedback->rate_total !!}'></p>
                                                                <p class = "text-dark">
                                                                    {{ $freelancer_feedback->message  }}
                                                                </p> 
                                                            @else
                                                                <p class = "text-dark">
                                                                    Employee's feedback is hidden until you provide feedback.
                                                                </p>
                                                            @endif
                                                        @else
                                                            <p class = "text-dark">
                                                                No Feedback Given
                                                            </p>
                                                        @endif  
                                                    </div>
                                                    <div class = "col-md-6 col-sm-6">
                                                        <h4 class = "text-dark">Your  Feedback to Client</h4>
                                                        @if(isset($client_feedback))
                                                            <p  class = "total-rating" data-star = '{!! $client_feedback->rate_total !!}'></p>
                                                            <p class = "text-dark">
                                                                {{ $client_feedback->message  }}
                                                            </p>
                                                        @else
                                                            <p class = "text-dark">
                                                                No Feedback Given
                                                            </p>
                                                            <a  href = "{!! route('employee.contract.leavefeedback', $offer->serial)  !!}" class="btn  btn-card-button  btn-mint">
                                                                <span class="text-bold   pt-15"> Give Feedback </span>
                                                            </a>
                                                        @endif 
                                                    </div> 
                                                </div>
                                            @else
                                                <div class = "row mar-all pad-all">
                                                    <div class="col-md-12 text-center pt-70">
                                                        <i class = "fa fa-file pad-rgt"> </i> 
                                                    </div> 
                                                    <div class="col-md-12 text-center">
                                                        <h4 class = "text-dark"> This contract is not yet eligible for feedback.</h4> 
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
            </div>
        </div> 
    </div>
</div> 
@endsection
@section('javascript') 
    <script src="{{ asset('plugins/bootstrap-timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{asset('plugins/simplerating/jquery.star-rating-svg.js')}}"></script>
    <script> 
        var init_val = 0;
        // selected_time_input 
        function showTimeSheet(timesheet_date){ 
            $.ajax({
                url:   "{!! route('employee.contract.get_time_sheet', $offer->serial) !!}",
                type: 'POST',
                data:  { start_date : timesheet_date },
                dataType: 'json',
                beforeSend: function (){
                },
                success: function(json){
                    if(json.status){
                        $(".timesheet_table").html(json.html); 
                        $(".timesheet_button.prev_week").attr('data-date' , json.prev_week);
                        $(".timesheet_button.next_week").attr('data-date' , json.next_week); 
                        if( json.prev_week == "")
                            $(".timesheet_button.prev_week").prop('disabled', true);
                        else
                            $(".timesheet_button.prev_week").prop('disabled', false);

                        if( json.next_week == "")
                            $(".timesheet_button.next_week").prop('disabled', true);
                        else
                            $(".timesheet_button.next_week").prop('disabled', false);

                        $('.selected_time_input').timepicker({
                            minuteStep: 5,
                            showInputs: false,
                            showMeridian: false,
                            format: 'hh:mm',
                            disableFocus: true
                        });
                        init_val = 1;
                    } 
                },
                complete: function () {
                },
                error: function() {
                }
            }); 
        }
        // timesheet_body
        $(".timesheet_button").click(function(){ 
            showTimeSheet( $(this).attr('data-date') ); 
        });
        showTimeSheet( $(".timesheet_button.this_week").attr('data-date') );  
        function updateTimeSheet(){
            var timesheet_vals = []; 
            $(".selected_time_input").each(function(){
                const timesheet_val =   {
                                        date: $(this).attr('data-date'),
                                        hour:  $(this).val()
                                    };
                timesheet_vals.push(timesheet_val);
            }); 
            $.ajax({
                url:   "{!! route('employee.contract.updatetimesheet', $offer->serial) !!}",
                type: 'POST',
                data:  { timesheet_vals : timesheet_vals},
                dataType: 'json',
                beforeSend: function (){
                },
                success: function(json){
                    if(json.status){
                        
                    }
                },
                complete: function () {
                },
                error: function() {
                }
            });
        } 
        $(document).on("change", ".selected_time_input", function(){
            if(init_val){
                var hour    =   0;
                var minute  =   0;
                var second  =   0;
                $(".selected_time_input").each(function(){
                    splitTime   =   $(this).val().split(':'); 
                    hour        +=  parseInt(splitTime[0]); 
                    minute      +=  parseInt(splitTime[1]);
                });
                var estimated_hour  = (hour + minute / 60).toFixed(2); 
                $(".hourly_total_paid").html( "$" + (estimated_hour *  $(".hourly_rate_amount").attr('data-amount')).toFixed(2) ); 
                
                hour    =   Math.floor( hour + minute / 60 );
                minute  =   minute  %   60;
                
                if(minute < 10) 
                    $(".hours_total_timesheet").html(hour +  ":0" + minute);
                else
                    $(".hours_total_timesheet").html(hour +  ":" + minute);
                updateTimeSheet()
            }
        });
            
        @if($offer->status == 10)
            $(".total-rating").each(function(){
                $(this).starRating({
                    initialRating: $(this).attr('data-star'),
                    starSize:   14,
                    totalStars: 5,  
                    disableAfterRate: false,
                    readOnly: true
                });
            }); 
        @endif
    </script>
@stop