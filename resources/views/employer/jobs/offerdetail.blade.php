@extends('layouts.app')
@section('title', "Contract Details") 
@section('css') 
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            <h2 class="text-main pad-btm mar-no pad-no  text-semibold text-left text-dark">
                Offer Details
            </h2>
        </div>
        <div class = "col-md-12">
            @php
                $freelancer = $offer->getFreelancer();
                $job        = $offer->getJob();
            @endphp
            <div class="card m-md-top">
                <div class="card-body  pad-no">
                    <div class="clearfix pad-all"> 
                        <div class="media">
                            <div class="pull-left mar-rgt">
                                <img src="{!! $freelancer->getImage() !!}" class="img-md img-circle  m-0 fs-exclude" alt="avatar">
                            </div>
                            <div class="media-body">
                                <p class="mb-0 qa-wm-mo-fl-name pt-17">
                                    <a href="{!! route('freelancers.detail', $freelancer->serial) !!}" class  = "text-mint btn-link" target="_blank">
                                        <span class="fs-exclude">{!! $freelancer->accounts->name !!}</span>
                                    </a>
                                </p>  
                                <p class="mar-top text-bold text-dark">
                                    <span> <i class="fa fa-map-marker"></i> {!! Mainhelper::getStateFromValue($freelancer->accounts->state)   !!}</span>
                                </p> 
                            </div>
                        </div> 

                    </div>
                </div>
            </div> 
        </div>
        <div class = "col-md-12">
            <div class="card m-md-top">
                <div class="card-body  pad-no">
                    <div class = "col-md-12">
                        <div class = "clearfix pad-all bord-btm">
                            <h3 class = "text-bold text-dark">  Terms  </h3>
                        </div>
                    </div>
                    <div class = "col-md-12"> 
                        <div class="form-group mar-top clearfix">
                            <label class="col-sm-3 mar-no  text-left h5 text-dark" for=""> Contract Title</label>  
                            <div class="col-sm-3"> 
                                <span class = "text-bold text-dark">   {!! $offer->contract_title !!} </span>
                            </div> 
                        </div>
                        @if($offer->payment_type == "fixed") 
                            <div class="form-group clearfix">
                                <label class="col-sm-3 mar-no  text-left h5 text-dark" for=""> Amount </label>  
                                <div class="col-sm-3"> 
                                    <span class = "text-bold text-dark">  ${!! $offer->amount !!} </span>
                                </div> 
                            </div>
                        @endif 
                        @if($offer->payment_type == "hourly") 
                            <div class="form-group clearfix">
                                <label class="col-sm-3 mar-no  text-left h5 text-dark" for=""> Rate</label>  
                                <div class="col-sm-3"> 
                                    <span class = "text-bold text-dark">  ${!! $offer->amount !!} /hr </span>
                                </div> 
                            </div> 
                            <div class="form-group clearfix">
                                <label class="col-sm-3 mar-no  text-left h5 text-dark" for=""> Weely Limit</label>  
                                <div class="col-sm-3"> 
                                    <span class = "text-bold text-dark">  ${!! $offer->weekly_limit !!} </span>
                                </div> 
                            </div>
                        @endif 
                        <div class="form-group clearfix">
                            <label class="col-sm-3 mar-no text-left h5 text-dark " for="">Related Gig Listing</label>  
                            <div class="col-sm-3"> 
                                <span class = "text-bold text-dark"> 
                                    <a href = "{!! route('jobs_details', $job->serial) !!}" class = "btn-link text-mint mar-top text-bold">{!! $job->headline !!}</a>
                                </span>
                            </div> 
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-sm-3 mar-no text-left h5 text-dark " for="">Work Details</label>  
                            <div class="col-sm-12"> 
                                <span class = "description-detaillist text-dark"> 
                                        {!! $offer->work_details !!}
                                </span>
                            </div> 
                        </div> 
                    </div>

                    <div class = "col-md-12 mar-btm"> 
                        @if($offer->status == 0) 
                            <button type="button" class="btn   btn-default btn-card-button">
                                <span class="text-mint"> Decline Offer </span>
                            </button> 
                            <a href = "{!! route('employer.jobs.editoffer', $offer->serial) !!}" class="btn btn-mint   btn-default btn-card-button">   Edif Offer     </a>  
                            <a href = "#" class="btn btn-danger">  Cancel  </a>  
                        @endif 
                    </div>
                </div> 
            </div>
        </div> 
    </div>
</div>
@endsection
@section('javascript')

@stop