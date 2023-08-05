@php
    $profile_freelancer     =  $freelancer->getRoles()[0];
    $cards                  =  Auth::user()->getCards();
@endphp
<div class="card m-md-top">
    <div class="card-body  pad-no">
        <div class = "col-md-12">
            <div class = "clearfix pad-all bord-btm">
                <h3 class = "text-bold text-dark">  Terms  </h3>
            </div>          
        </div>
        <div class = "col-md-12"> 
            @if(isset($job))
                @if($job->payment_type == "fixed")
                    <div class="form-group clearfix">
                        <label class="col-sm-12 control-label text-left h5 text-dark" for=""> Pay a fixed price for your project </label>  
                        <div class="col-sm-4 input-group"> 
                            <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                            <input type="text" class="form-control decimal-input edit" id = "offer_amount" name="offer_amount"   value = "{!! $job->getPropoerBudget() !!}">
                        </div>
                        <div class="col-sm-12">
                            <span class="help-block">
                                <strong></strong>
                            </span>
                        </div> 
                    </div> 
                @endif
                @if($job->payment_type == "hourly")
                    <div class="form-group clearfix">
                        <label class="col-sm-12 control-label text-left h5 text-dark" for=""> Pay by the hour </label>  
                        <div class="col-sm-4 input-group"> 
                            <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                            <input type="text" class="form-control decimal-input edit" id="offer_amount" name="offer_amount"   value = "{!! $job->getPropoerBudget()  !!}"  >
                            <span class="input-group-addon text-dark">/hour</span> 
                        </div>
                        <div class="col-sm-12 mar-top-5"> <em class = "mar-top"> {!! $freelancer->accounts->name !!}'s profile rate is {!! $profile_freelancer->getHourlyDescription() !!}</em>
                        </div>
                    </div> 
                    <div class="form-group clearfix">
                        <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Weekly Limit </label>  
                        <div class = "col-sm-12">
                            <p class = ""> Setting a weekly limit is a great way to help ensure you stay on budget. </p>
                        </div> 
                        <div class = "col-sm-4 weekly_limit_select_part">
                            <select name  = "weekly_limit" class = "form-control weekly_limit_select">
                                    <option value = "-1">No Limit</option>
                                    @for($i = 5; $i <= 40; $i += 5)
                                    <option value = "{!! $i !!}"   @if($i == 40 ) selected @endif > {!! $i !!} hrs/week  </option>
                                    @endfor
                                    <option value = ""> Other </option>
                            </select>
                        </div> 
                        <div class = "col-sm-3 hidden weekly_limit_input_part">
                            <input  name = "weekly_limit" class="form-control edit decimal-input weekly_limit_input"  disabled placeholder = "Enter the weekly limit"  />
                        </div> 
                    </div> 
                    <div class="form-group clearfix">
                        <label class="col-sm-12 control-label text-left h5 text-dark" for = "">  Minimum Hours </label>   
                        <div class = "col-sm-4 ">
                            <input  name = "minimum_hours" class="form-control edit decimal-input"  />
                        </div>  
                    </div> 
                @endif
            @else 
                <div class="form-group clearfix">
                    <label class="col-sm-12 control-label text-left h5 text-dark" for=""> Pay by the hour </label>  
                    <div class="col-sm-4 input-group"> 
                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>                                              
                        <input type="text" class="form-control decimal-input edit" id="offer_amount" name="offer_amount"   value = "{!! $profile_freelancer->hourly_rate_to  !!}"  >
                        <span class="input-group-addon text-dark">/hour</span> 
                    </div>
                    <div class="col-sm-12 mar-top-5"> <em class = "mar-top"> {!! $freelancer->accounts->name !!}'s profile rate is {!! $profile_freelancer->getHourlyDescription() !!}</em>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Weekly Limit </label>  
                    <div class = "col-sm-12">
                        <p class = ""> Setting a weekly limit is a great way to help ensure you stay on budget. </p>
                    </div>
                    <div class = "col-sm-4 weekly_limit_select_part">
                        <select name  = "weekly_limit" class = "form-control weekly_limit_select">
                            <option value = "-1"> No Limit </option>
                            @for($i = 5; $i <= 40; $i += 5)
                                <option value = "{!! $i !!}" @if( $i == 40 ) selected @endif > {!! $i !!} hrs/week  </option>
                            @endfor
                            <option   value = ""> Other </option>
                        </select>
                    </div> 
                    <div class = "col-sm-3 hidden weekly_limit_input_part">
                        <input  name = "weekly_limit" class="form-control edit decimal-input weekly_limit_input"  disabled placeholder = "Enter the weekly limit" />
                    </div> 
                </div> 
                <div class="form-group clearfix">
                    <label class="col-sm-12 control-label text-left h5 text-dark" for = "">  Minimum Hours </label>   
                    <div class = "col-sm-4 ">
                        <input  name = "minimum_hours" class="form-control edit decimal-input"  />
                    </div>  
                </div> 
            @endif           
        </div>
    </div>
</div>
 
@if( !count($cards) ||  (isset($job) && $job->payment_type == "fixed"))
    <div class="card m-md-top">
        <div class="card-body  pad-no">
            <div class = "col-md-12">
                <div class = "clearfix pad-all bord-btm">
                    <h3 class = "text-bold text-dark">  Billing Method  </h3>
                </div> 
            </div>
            <div class = "col-md-12 pad-ver ">
                @if(count($cards))
                    <div class="form-group clearfix">
                        <label class="col-sm-12 control-label text-left h5 text-dark" for = ""> Card </label>   
                        <div class="col-sm-6"> 
                            <select class = "form-control billing-card-selection" name = "card">
                                @foreach($cards as $card)
                                    <option value = "{!! $card->serial !!}">{!! $card->card_type !!} ending in {!! $card->ext !!}</option>
                                @endforeach
                            </select>
                            <span class="help-block text-left"> 
                                <strong></strong>
                            </span>
                        </div>
                    </div> 
                @else 
                    <div class="form-group clearfix">
                        <label class="col-sm-12 control-label text-left pt-15  text-dark" for=""> 
                            Adding a billing method is required to show the freelancer you can pay once work is delivered. There is a {!! $job_poster_fee !!}% processing fee for all payments.  
                        </label>  
                    </div> 
                    <div class="form-group clearfix">
                        <div class = "col-md-12">
                            <button type = "button" class = "btn btn-mint pt-17 add-billing-button">
                                <i class  = "fa fa-plus mar-rgt"></i> Add Billing Now 
                            </button>
                        </div>
                        <div class = "col-md-12 has-error mar-top">
                            <span class="help-block text-left"> 
                                <strong>Please add Billing Method</strong>
                            </span>
                        </div>
                    </div> 
                @endif  
            </div> 
        </div>
    </div> 
@endif 

<div class="card m-md-top">
    <div class="card-body  pad-no">
        <div class = "col-md-12">
            <div class = "clearfix pad-all bord-btm">
                <h3 class = "text-bold text-dark">  Work Description  </h3>
            </div>
        </div>
        <div class = "col-md-12">              
            <div class="form-group clearfix">
                <label class="col-sm-12 control-label text-left h5 text-dark" for="work_details"> Work Details </label>
                <div class="col-sm-12  ">
                    <textarea name = "work_details" maxlength = "5000" class="form-control edit" rows = "8">@if(isset($job)){!! $job->description !!}@endif</textarea>
                    <span class = "help-block">
                        <strong></strong>
                    </span>
                </div>
            </div> 
        </div> 
    </div>
</div>