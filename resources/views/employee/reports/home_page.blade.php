@extends('layouts.app')
@section('title', 'Reports Home Page') 
@section('css') 
<style>
    .discovergig-reports-home-tabset{
        border: 1px solid #cfcfcf;
    }
    .discovergig-reports-home-tabset .nav-tabs{
        display:flex;
    }
    .discovergig-reports-home-tabset .nav-tabs>li{
        flex: 1;
    }
    .discovergig-reports-home-tabset .nav-tabs>li>a {
        margin-right: 0px;
        line-height: 1.42857143;
        border: 1px solid transparent;
        border-radius: 0;
        font-size: 18px;
    }
    .discovergig-reports-home-tabset .nav-tabs > li > a h4 {
        color: #343a40;
    }
    .discovergig-reports-home-tabset .nav-tabs > li.active{
        border:  1px solid  #f0f0f0;
        border-top: 3px solid #37a000;
        background-color: #fff;  
    }
    .discovergig-reports-home-tabset .nav-tabs > li.active > a h4 {
        color: #37a000;
    }
    .discovergig-reports-home-tabset.tab-base .nav-tabs>li >a{
        padding-left: 30px !important;
        padding-top: 30px !important;
        padding-bottom: 14px !important;
    }  
    .discovergig-reports-home-tabset.tab-base .nav-tabs>li:not(.active)>a {
        background-color: #f0f0f0; 
    } 
</style>
@endsection
@section('content') 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">  
            <div class="tab-base discovergig-reports-home-tabset"> 
                <ul class="nav nav-tabs">
                    <li @if($main_request == "in-progress") class="active" @endif>
                        <a   href="{!! route('employee.reports.main_action', 'in-progress') !!}">
                            <h4> Work in Progress</h4>                            
                            <h1 class="m-xs-top-bottom nowrap text-dark mar-no"><strong class="lead-lg">${!! number_format(Auth::user()->checkTotalReports('in_progress'), 2) !!}</strong></h1>
                        </a>
                    </li>
                    <li @if($main_request == "in-review") class="active" @endif>
                        <a   href="{!! route('employee.reports.main_action', 'in-review') !!}"> 
                            <h4>  In Review</h4>
                            <h1 class="m-xs-top-bottom nowrap text-dark mar-no"><strong class="lead-lg">${!! number_format(Auth::user()->checkTotalReports('in_review'), 2) !!}</strong></h1>
                        </a>
                    </li>
                    <li @if($main_request == "pending") class="active" @endif>
                        <a  href="{!! route('employee.reports.main_action', 'pending') !!}">
                            <h4> Pending </h4>
                            <h1 class="m-xs-top-bottom nowrap text-dark mar-no"><strong class="lead-lg">${!! number_format(Auth::user()->checkTotalReports('pending'), 2) !!}</strong></h1>
                        </a>
                    </li>
                    <li @if($main_request == "available") class="active" @endif>
                        <a   href="{!! route('employee.reports.main_action', 'available') !!}">
                            <h4> Available </h4>
                            <h1 class="m-xs-top-bottom nowrap text-dark mar-no"><strong class="lead-lg">${!! number_format(Auth::user()->checkTotalReports('available'), 2)  !!}</strong></h1>
                            <p class = "pt-13 mar-no"> Last Payment: $0.00 </p>
                        </a>
                    </li>
                </ul> 
                <div class="tab-content"> 
                    <div class = "clearfxi pad-hor">
                        @if($main_request == 'in-progress')
                            @if(count($hourly_offers))
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <h3 class = "text-dark">Timesheet for {!! $timelist !!} (This Week) in progress </h3>
                                    </div>
                                    <div class = "col-md-12">
                                        <div class="table-responsive">
                                            <table class="table   text-dark timesheet_table" style = "table-layout: fixed;"> 
                                                <thead>
                                                    <tr> 
                                                        <th class = "text-center vertical-align-middle" colspan = 3>
                                                            <p class = "mar-no"> Gig </p>
                                                        </th>
                                                        @foreach($weeksheets as $weeksheet)
                                                        <th class = "text-center vertical-align-middle">
                                                            <p class = "mar-no"> {!! $weeksheet['week'] !!} </p>
                                                            <p> {!! $weeksheet['date'] !!} </p>
                                                        </th>
                                                        @endforeach
                                                        <th class = "text-center vertical-align-middle">
                                                            <p class = "mar-no"> HOURS </p>
                                                        </th>
                                                        <th class = "text-center vertical-align-middle">
                                                            <p class = "mar-no"> RATE </p> 
                                                        </th>
                                                        <th class = "text-center vertical-align-middle">
                                                            <p class = "mar-no"> AMOUNT </p> 
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($hourly_offers as $hourly_offer)
                                                    <tr>
                                                        <td colspan = 3> 
                                                            <a href = "{!! route('jobs_contract_details', $hourly_offer->serial) !!}" class = "text-mint btn-link">{!! $hourly_offer->contract_title !!}</a>
                                                        </td>
                                                        @php
                                                            $timesheet_results 	=	$hourly_offer->getTimeSheets($curr_date); 
                                                            $minutes    = 0;
                                                            $total_paid = 0; 
                                                        @endphp 
                                                        @foreach($timesheet_results as $timesheet_result)
                                                            @php 
                                                                if(isset($timesheet_result['time_sheet'])){
                                                                    list($hour, $minute)    =   explode(':', $timesheet_result['time_sheet']->timesheets_time );
                                                                    $minutes                +=  $hour * 60;
                                                                    $minutes                +=  $minute;
                                                                   
                                                                }
                                                            @endphp

                                                            @if(isset($timesheet_result['time_sheet']))
                                                                <td  class = "text-center vertical-align-middle">
                                                                    <p class = "mar-no text-mint">
                                                                        {!! date('H:i', strtotime( $timesheet_result['time_sheet']->timesheets_time))  !!}
                                                                    </p>
                                                                </td>
                                                            @else
                                                                <td  class = "text-center vertical-align-middle">
                                                                    <p class = "mar-no text-mint"> - </p>
                                                                </td>
                                                            @endif
                                                        @endforeach
                                                            
                                                            @php
                                                                $hours       =  floor($minutes / 60);
                                                                $hours_val   =  $minutes / 60;
                                                                $hours_val   =  round($hours_val, 2);
                                                                $total_paid  =  $hours_val * $hourly_offer->amount;
                                                                $minutes    -=  $hours * 60;
                                                            @endphp
                                                        
                                                        <td  class = "text-center vertical-align-middle">
                                                            <p class = "mar-no text-mint">{!! sprintf('%02d:%02d', $hours, $minutes) !!}</p>
                                                        </td>

                                                        <td  class = "text-center vertical-align-middle">
                                                            <p class = "mar-no text-dark"> ${!! $hourly_offer->amount !!} /hr</p>
                                                        </td>
                                                        <td  class = "text-center vertical-align-middle">
                                                            <p class = "mar-no text-dark">${!! number_format($total_paid, 2) !!}</p>
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class = "row">
                                    <div class = "col-xs-12">
                                        <h3 class="text-center text-dark text-muted null-state-message">You have no work in progress</h3>
                                    </div>
                                </div>
                            @endif

                        @endif

                        @if($main_request == 'in-review')
                            @if(count($hourly_offers) || count($submit_works)) 
                                @if(count($hourly_offers))
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <h3 class = "text-dark">Timesheet for {!! $timelist !!} (Last Week) in review </h3>
                                    </div>
                                    <div class = "col-md-12">
                                        <div class="table-responsive">
                                            <table class="table   text-dark timesheet_table" style = "table-layout: fixed;"> 
                                                <thead>
                                                    <tr> 
                                                        <th class = "text-center vertical-align-middle" colspan = 3>
                                                            <p class = "mar-no"> Gig </p>
                                                        </th>
                                                        @foreach($weeksheets as $weeksheet)
                                                        <th class = "text-center vertical-align-middle">
                                                            <p class = "mar-no"> {!! $weeksheet['week'] !!} </p>
                                                            <p> {!! $weeksheet['date'] !!} </p>
                                                        </th>
                                                        @endforeach
                                                        <th class = "text-center vertical-align-middle">
                                                            <p class = "mar-no"> HOURS </p>
                                                        </th>
                                                        <th class = "text-center vertical-align-middle">
                                                            <p class = "mar-no"> RATE </p> 
                                                        </th>
                                                        <th class = "text-center vertical-align-middle text-right">
                                                            <p class = "mar-no"> AMOUNT </p> 
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($hourly_offers as $hourly_offer)
                                                    <tr>
                                                        <td colspan = 3> 
                                                            <a href = "{!! route('jobs_contract_details', $hourly_offer->serial) !!}" class = "text-mint btn-link">{!! $hourly_offer->contract_title !!}</a>
                                                        </td>
                                                        @php
                                                            $timesheet_results 	= $hourly_offer->getTimeSheets($curr_date); 
                                                            $minutes            = 0;
                                                            $total_paid         = 0;
                                                        @endphp 
                                                        @foreach($timesheet_results as $timesheet_result)
                                                            @php 
                                                                if(isset($timesheet_result['time_sheet'])){
                                                                    list($hour, $minute)    =   explode(':', $timesheet_result['time_sheet']->timesheets_time );
                                                                    $minutes                +=  $hour * 60;
                                                                    $minutes                +=  $minute;
                                                                }
                                                            @endphp
                                                            @if(isset($timesheet_result['time_sheet']))
                                                                <td  class = "text-center vertical-align-middle">
                                                                    <p class = "mar-no text-mint">
                                                                        @if( $timesheet_result['time_sheet']->timesheets_time == "00:00:00")
                                                                            -
                                                                        @else
                                                                            {!! date('H:i', strtotime( $timesheet_result['time_sheet']->timesheets_time))  !!}
                                                                        @endif
                                                                    </p>
                                                                </td>
                                                            @else
                                                                <td  class = "text-center vertical-align-middle">
                                                                    <p class = "mar-no text-mint"> - </p>
                                                                </td>
                                                            @endif
                                                        @endforeach                                                        
                                                            @php
                                                                $hours       =  floor($minutes / 60);
                                                                $hours_val   =  $minutes / 60;
                                                                $hours_val   =  round($hours_val, 2);
                                                                $total_paid  =  $hours_val * $hourly_offer->amount;
                                                                $minutes    -=  $hours * 60;
                                                            @endphp                                                    
                                                        <td  class = "text-center vertical-align-middle">
                                                            <p class = "mar-no text-mint">{!! sprintf('%02d:%02d', $hours, $minutes) !!}</p>
                                                        </td>

                                                        <td  class = "text-center vertical-align-middle">
                                                            <p class = "mar-no text-dark"> ${!! $hourly_offer->amount !!} /hr</p>
                                                        </td>
                                                        <td  class = "text-center vertical-align-middle">
                                                            <p class = "mar-no text-dark">${!! number_format($total_paid, 2) !!}</p>
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(count($submit_works))
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <h3 class = "text-dark">Fixed Price Milestones in review </h3>
                                    </div>
                                    <div class = "col-md-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-dark text-bold width-100"  >Date</th>
                                                        <th class="text-dark text-bold">Gig / Milestone</th>
                                                        <th class="text-dark text-bold width-100 text-right"  >Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($submit_works as $submit_work)
                                                    <tr>
                                                        <td class="text-dark"> {!! date('M d, Y', strtotime($submit_work->updated_at)) !!}   </td>
                                                        <td class="text-dark table-oneline-limit">
                                                            <a   href = "{!! route('jobs_contract_details', $submit_work->offer_serial) !!}"  class = "text-mint btn-link text-bold"> 
                                                            {!! $submit_work->job_title !!} / {!! $submit_work->headline !!}
                                                            </a>
                                                        </td>
                                                        <td class="text-dark text-right">${!! $submit_work->amount  !!}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @else
                            <div class = "row">
                                <div class = "col-xs-12">
                                    <h3 class="text-center text-dark text-muted null-state-message">You have no gigs in review</h3>
                                </div>
                            </div>
                            @endif
                        @endif
    
                        @if($main_request == 'pending')
                            @if( count($fixed_prices) || count($other_payments) || count($hourly_prices) )
                                @if(count($fixed_prices))
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h3 class = "text-dark">Fixed Price milestones approved</h3>
                                        </div>
                                        <div class = "col-md-12 mar-top"> 
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-dark text-bold width-100"  >Date</th>
                                                            <th class="text-dark text-bold">Gig / Milestone</th>
                                                            <th class="text-dark text-bold width-100 text-right"  >Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($fixed_prices as $fixed_price)
                                                        @php
                                                            $offer = $fixed_price->getOffer();
                                                        @endphp
                                                        <tr>
                                                            <td class="text-dark">{!! date('n/j/Y', strtotime( $fixed_price->created_at))  !!}  </td>
                                                            <td class="text-dark table-oneline-limit">
                                                                <a @if(isset($offer)) href = "{!! route('jobs_contract_details', $offer->serial) !!}" @else href = "#" @endif class = "text-mint btn-link text-bold">
                                                                    {!! $fixed_price->getMilestoneName() !!} 
                                                                </a>
                                                            </td>
                                                            <td class="text-dark text-right">${!! $fixed_price->amount !!}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div> 
                                        </div>
                                    </div>
                                @endif

                                @if(count($hourly_prices))
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h3 class = "text-dark">Hourly  approved</h3>
                                        </div>
                                        <div class = "col-md-12 mar-top"> 
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-dark text-bold width-100"  >Date</th>
                                                            <th class="text-dark text-bold"> Description </th>
                                                            <th class="text-dark text-bold width-100 text-right">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($hourly_prices as $hourly_price)
                                                        @php
                                                            $offer = $hourly_price->getOffer(); 
                                                        @endphp
                                                        <tr>
                                                            <td class="text-dark">{!! date('n/j/Y', strtotime( $hourly_price->created_at))  !!}  </td>
                                                            <td class="text-dark table-oneline-limit">
                                                                <a @if(isset($offer)) href = "{!! route('jobs_contract_details', $offer->serial) !!}" @else href = "#" @endif class = "text-mint btn-link text-bold">
                                                                    {!! $hourly_price->description !!} 
                                                                </a>
                                                            </td>
                                                            <td class="text-dark text-right">${!! $hourly_price->amount !!}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div> 
                                        </div>
                                    </div>
                                @endif 
                                 
                                @if(count($other_payments))
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <h3 class = "text-dark">Other payments and adjustments</h3>
                                    </div>
                                    <div class = "col-md-12 mar-top"> 
                                        <div class="table-responsive">
                                            <table class="table ">
                                                <thead>
                                                    <tr>
                                                        <th class="text-dark text-bold width-100 pt-14"  >Date</th>
                                                        <th class="text-dark text-bold pt-14">Job / Milestone</th>
                                                        <th class="text-dark text-bold width-100 pt-14 text-right"  >Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($other_payments as $other_payment)
                                                        @php
                                                            $offer = $other_payment->getOffer();
                                                        @endphp
                                                    <tr>
                                                        <td class="text-dark vertical-align-middle pt-14">{!! date('n/j/Y', strtotime( $other_payment->created_at))  !!} </td>
                                                        <td class="text-dark table-oneline-limit">
                                                            <p class = "mar-no pt-14"> {!! $other_payment->type !!} </p> 
                                                            <a @if(isset($offer)) href = "{!! route('jobs_contract_details', $offer->serial) !!}" @else href = "#" @endif class = "text-mint btn-link text-bold"> 
                                                                {!! $other_payment->getMilestoneName() !!}  
                                                            </a>
                                                        </td>
                                                        <td class="text-dark vertical-align-middle pt-14 text-right">${!! $other_payment->amount !!}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div> 
                                    </div>
                                </div>  
                                @endif
                            @else
                            <div class = "row">
                                <div class = "col-xs-12">
                                    <h3 class="text-center text-dark text-muted null-state-message">You have no pending payments</h3>
                                </div>
                            </div>
                            @endif
                        @endif

                        @if($main_request == 'available')
                            @if(count($payment_reports))
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <h3 class = "text-dark">Recent transactions (last 30 days)</h3>
                                    </div>
                                    <div class = "col-md-12 mar-top"> 
                                        <div class="table-responsive">
                                            <table class="table ">
                                                <thead>
                                                    <tr>
                                                        <th class="text-dark text-bold width-100" >Date</th>
                                                        <th class="text-dark text-bold width-100"> Type </th>
                                                        <th class="text-dark text-bold">Description</th>
                                                        <th class="text-dark text-bold width-100 ">Amount</th>
                                                        <th class="text-dark text-bold width-100">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($payment_reports as $payment_report_index => $payment_report) 
                                                        @php
                                                            $offer = $payment_report->getOffer();

                                                            if(!$payment_report_index)
                                                                $balance =  Auth::user()->checkTotalReports('available'); 
                                                        @endphp
                                                    <tr>
                                                        <td class="text-dark">{!! date('n/j/Y', strtotime( $payment_report->updated_at))  !!}  </td>
                                                        <td class="text-dark">{!! $payment_report->type !!}</td>
                                                        <td class="text-dark table-oneline-limit">
                                                            @if(isset($offer))
                                                            <a @if(isset($offer)) href = "{!! route('jobs_contract_details', $offer->serial) !!}" @else href = "#" @endif class = "text-mint btn-link text-bold"> 
                                                                {!! $payment_report->description !!}  
                                                            </a>
                                                            @else
                                                                {!! $payment_report->description !!}
                                                            @endif
                                                        </td>
                                                        <td class="text-dark">
                                                            @if($payment_report->direction == "in")
                                                                ${!! $payment_report->amount !!}
                                                            @else
                                                                (${!! $payment_report->amount !!})
                                                            @endif
                                                        </td>
                                                        <td class="text-dark">${!! number_format($balance, 2) !!}</td>
                                                    </tr>

                                                        @php 
                                                            if($payment_report->direction == "in")
                                                                $balance -= $payment_report->amount;
                                                            else
                                                                $balance += $payment_report->amount; 
                                                        @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div> 
                                    </div>
                                </div> 
                            @else
                                <div class = "row">
                                    <div class = "col-xs-12">
                                        <h3 class="text-center text-dark text-muted null-state-message">
                                            No transactions in the last 30 days. <a href = "{!! route('employee.reports.earnings_history') !!}" class = "text-mint">View all transactions ›</a>
                                        </h3>
                                    </div>
                                </div>
                            @endif
                        @endif 
                        <p class = "text-dark"> Note: this report is updated every hour.</p> 
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
@endsection
@section('javascript')

@stop