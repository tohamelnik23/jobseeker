@extends('layouts.app')
@section('title', 'Advance History')
@section('css')
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center"> 
        <div class = "col-md-offse-2 col-md-8"> 
            @include('partial.alert')
        </div>
        <div class = "col-md-offse-1 col-md-10"> 
            <h2 class="text-main pad-btm mar-top   text-semibold text-left text-dark">  
                Advance History
            </h2>
        </div> 
        <div class = "col-md-offse-1 col-md-10">  
            <div class="card mar-btm"> 
                <div class="card-body"> 
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th class="text-dark text-bold">Date</th>
                                    <th class="text-dark text-bold">Offer</th>
                                    <th class="text-dark text-bold">Amount</th>
                                    <th class="text-dark text-bold">Total Paid</th>
                                    <th class="text-dark text-bold">Status</th>
                                    <th class="text-dark text-bold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loan_histories as $loan_history)
                                    <tr>
                                        <td class = "text-dark"> {!! date('M d, Y', strtotime($loan_history->updated_at))  !!} </td>
                                        @php
                                            $offer = $loan_history->getOffer();
                                        @endphp
                                        <td class = "text-dark">
                                            <a href = "{!! route('jobs_contract_details', $offer->serial) !!}" class = "btn-link text-mint">{!! $offer->contract_title  !!}</a>
                                        </td> 
                                        <td class="text-dark"> ${!! $loan_history->agreed_amount  !!} </td>
                                        <td class="text-dark">
                                            @if($loan_history->status  == "paid")
                                                ${!! $loan_history->total_paid  !!}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-dark text-capitalize">{!! $loan_history->status  !!}</td>
                                        <td>
                                            @if($loan_history->status  == "pending")
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{!! route('loans.reject', $loan_history->serial) !!}" class="btn btn-danger margin-5">Cancel</a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan = "3" class="text-dark"> There are no transactions on this criteria  </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> 

                    <div class = "text-right">   
                        {{$loan_histories->links()}} 
                    </div> 

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('javascript')


@stop