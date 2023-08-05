@extends('layouts.app')
@section('title', 'Transaction History') 
@section('css')
@endsection
@section('content')
@php
    $balance = Auth::user()->checkTotalEscrow();
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            <h3 class="text-main pad-btm mar-no  pad-lft text-semibold text-left text-dark">  
                Transaction History
            </h3>
            <h4 class=" pad-btm mar-no  pad-lft text-thin text-left text-dark">  
                Balance:  <span class = "text-mint text-bold"> ${!! number_format($balance, 2) !!} </span>
            </h4>
        </div>
        <div class = "col-md-12">
            <div class = "card mar-top">
                <div class = "card-body  pad-no">  
                    <div class  = "pad-all">  
                        <div class="table-responsive">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th class="text-dark text-bold width-100 text-uppercase" >Date</th>
                                        <th class="text-dark text-bold width-100 text-uppercase"> Type </th>
                                        <th class="text-dark text-bold text-uppercase">Description</th>
                                        <th class="text-dark text-bold width-100 text-uppercase">Freelancer</th>
                                        <th class="text-dark text-bold width-100 text-uppercase">Amount / Balance</th>
                                        <th class="text-dark text-bold width-100 text-uppercase">REF ID</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @forelse($payment_reports as $payment_report_index => $payment_report)  
                                        @php
                                            $freelancer = $payment_report->getFreelancer();
                                        @endphp
                                        <tr>
                                            <td class="text-dark pad-hor vertical-align-middle">{!! date('M j , Y', strtotime( $payment_report->updated_at))  !!}  </td>
                                            <td class="text-dark vertical-align-middle">{!! $payment_report->type !!}</td>
                                            <td class="text-dark table-oneline-limit vertical-align-middle">{!! $payment_report->description !!}</td>
                                            <td class = "vertical-align-middle text-dark"> 
                                                @if(isset($freelancer))
                                                    {!! $freelancer->accounts->name !!}
                                                @endif
                                            </td>
                                            <td class="text-dark vertical-align-middle">
                                                @if($payment_report->direction == "in")
                                                    <p class = "mar-no">${!! $payment_report->amount !!}</p>
                                                @else
                                                    <p class = "mar-no">(${!! $payment_report->amount !!})</p>
                                                @endif 
                                                <p class = "mar-no">${!! number_format($balance, 2) !!}</p> 
                                            </td>
                                            <td class="text-dark vertical-align-middle">
                                                <a href = "#" class = "text-mint btn-link text-bold">{!! $payment_report->serial !!} </a>
                                            </td>
                                        </tr> 
                                        @php
                                            if($payment_report->status == "available"){
                                                if($payment_report->direction == "in")
                                                    $balance -= $payment_report->amount;
                                                else
                                                    $balance += $payment_report->amount; 
                                            }
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan = "5" class = "text-center"> 
                                                <h4>    No transactions meet your selected criteria  </h4>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>   
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('javascript')
@stop