@extends('layouts.app')
@section('title', 'Billing & Payments')
@section('css')
@endsection
@section('content') 
    @php
        $job_poster_fee         =  App\Model\MasterSetting::getValue('job_poster_fee'); 
    @endphp
    <div class="container">
        <div class="row justify-content-center">
            <div class = "col-md-3">
                @include('general.partial.settings_leftsidebar') 
            </div>
            <div class="col-md-9">
                <div class = "row">
                    <div class = "col-md-12">
                        @include('partial.alert')
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <h3 class="text-main pad-btm mar-no  pad-lft text-semibold text-left text-dark">  
                            Billing & payments
                        </h3>
                    </div>
                </div> 
                <div class="card mar-btm">
                    <div class="card-header bg-light"> 
                        <h4 class = "text-dark"> Balance due </h4>
                    </div>
                    <div class="card-body">
                        <div class = "col-md-7 text-dark"> 
                            Your balance due is <strong id="pay-now-due">$0.00</strong> 
                        </div>
                        <div class = "col-md-5 text-right">
                            <button class="btn btn-primary m-0-bottom btn-block-sm m-0-right"   disabled="disabled">
                                Pay Now
                            </button> 
                        </div>
                    </div>
                </div> 
                <div class="card mar-btm">
                    <div class="card-header bg-light"> 
                        <h4 class = "text-dark"> 
                            Billing Methods  
                            <button title="Edit address" type="button" class="btn btn-mint add-billing-button pull-right" >
                                Add Method
                            </button> 
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class = "clearfix pad-all text-dark">
                            @if(count($cards))
                                @php
                                    $additional_methods = 1;
                                @endphp
                                @foreach($cards as $card)
                                    @if($card->primary_method == 1)
                                        <div class = "primary-card row">
                                            <div class = "col-md-12">
                                                <h5 class = "text-dark"> Primary </h5>
                                                <hr class = "mar-no" />
                                            </div>
                                            <div class = "col-md-12">
                                                <div class = "row card-item pad-ver">
                                                    <div class = "col-md-10 col-sm-10">
                                                        <img src = "{!! asset('img/card/' . $card->card_type . '.png') !!}"  height = "30"  />
                                                        <span class = "pad-lft pt-14"> {!! $card->card_type !!}  ending in {!! $card->ext !!} </span>
                                                    </div>
                                                    <div class = "col-md-2 col-sm-2 text-right"> 
                                                        <div class="dropdown">
                                                            <button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                                                <span class = "text-bold text-mint"> <i class = "fa fa-ellipsis-h"></i> </span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-lg">
                                                                <li><a class = "deleteaction"   data-string = "Do you want to remove this card?"   data-url = "{!! route('employer.profile.settings.remove_card', $card->serial) !!}"   data-title="Delete Card" href="javascript:void(0)">Remove</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    @else
                                        @if($additional_methods)
                                        <div class = "secondary-card row">
                                            <div class = "col-md-12">
                                                <h5 class = "text-dark"> Additional </h5>
                                                <hr class = "mar-no" />
                                            </div>
                                            <div class = "col-md-12">
                                        @endif
                                                <div class = "row card-item pad-ver">
                                                    <div class = "col-md-10 col-sm-10">
                                                        <img src = "{!! asset('img/card/' . $card->card_type . '.png') !!}"  height = "30"  />
                                                        <span class = "pad-lft pt-14"> {!! $card->card_type !!}  ending in {!! $card->ext !!} </span>
                                                    </div>
                                                    <div class = "col-md-2 col-sm-2 text-right"> 
                                                        <div class="dropdown">
                                                            <button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                                                <span class = "text-bold text-mint"> <i class = "fa fa-ellipsis-h"></i> </span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-lg">
                                                                <li><a href="{!! route('employer.profile.settings.setpriamry_card', $card->serial) !!}">Set as primary</a></li>
                                                                <li><a class = "deleteaction"   data-string = "Do you want to remove this card?"   data-url = "{!! route('employer.profile.settings.remove_card', $card->serial) !!}"   data-title="Delete Card" href="javascript:void(0)">Remove</a></li>
                                                            </ul>
                                                        </div> 
                                                    </div>
                                                </div> 
                                        @if($additional_methods)
                                            </div>
                                        </div>
                                        @endif
                                        @php
                                            $additional_methods = 0;
                                        @endphp
                                    @endif 
                                @endforeach
                            @else
                                <h5 class = "text-bold text-dark"> You have not set up any billing methods yet.</h5>
                                <p class = "text-dark pt-14"> Set up your billing methods so you'll be able to hire right away when you are ready. </p>
                                <p> A {!! $job_poster_fee !!}% processing fee will be assessed on all payments.</p>
                            @endif
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@include('employer.jobs.partial.add_payment_modal')

@endsection
@section('javascript')
<script>

</script>
@stop