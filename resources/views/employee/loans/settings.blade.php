@extends('layouts.app')
@section('title', 'Advances Information')
@section('css')
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class = "col-md-offse-2 col-md-8"> 
                <div class="card mar-btm">
                    <div class="card-header bg-trans"> 
                        <h4 class = "text-dark">Advances Information  </h4>
                    </div>
                    <div class="card-body">
                        <div class="bank_display_stuff "> 
                                <div class="form-group clearfix">
                                    <label class="col-md-12 h5  control-label text-dark text-left"> <strong> Factor Rate </strong> </label>
                                    <div class="col-md-6 text-dark pt-14"> 
                                        {!! number_format(1 + (Auth::user()->accounts->loan_fee / 100) , 2)  !!}
                                    </div>
                                </div>  
                                <div class="form-group clearfix">
                                    <label class="col-md-12 h5 control-label text-dark text-left"> <strong> Advance Limit </strong> </label>
                                    <div class="col-md-6 text-dark pt-14"> 
                                        ${!! number_format(Auth::user()->accounts->loan_amount,  2)  !!}
                                    </div>
                                </div>
                                @php
                                    $lending_power = Auth::user()->accounts->loan_amount - Auth::user()->getLoanValue('total_loans_pending');
                                @endphp
                                <div class="form-group clearfix">
                                    <label class="col-md-12 h5 control-label text-dark text-left"> <strong> Advance Power </strong> </label>
                                    <div class="col-md-6 text-dark pt-14"> 
                                        ${!! number_format($lending_power, 2)  !!}
                                    </div>
                                </div> 
                                <div class="form-group clearfix">
                                    <label class="col-md-12 h5 control-label text-dark text-left"> <strong>  Current Advances </strong> </label>
                                    <div class="col-md-6 text-dark pt-14"> 
                                        ${!! number_format(Auth::user()->getLoanValue('total_loans_finished'), 2)  !!}
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
 
@stop