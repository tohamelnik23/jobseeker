@extends('layouts.app')
@section('title', 'Gig Postings')
@section('css') 
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center"> 
        <div class = "col-md-12">
            <h4 class = "text-dark"> All Gig Posts </h4>
        </div>
        <div class="col-md-12 text-right demo-nifty-btn">
			<a  href = "{!! route('employer.job.add') !!}" class = "btn btn-mint btn-rounded"> Post a New Gig </a>  
			<a  href = "{!! route('employer.shift.add') !!}" class = "btn btn-mint btn-rounded"> Post a New Shift </a>  
		</div>
        <div class="col-md-12">
			<div class="card mar-ver">
				<div class="card-header bg-light">
                    <form  method = "GET"  action = "{{ route('employer.mypostings') }}">
                        <div class = "row">
                            <div class = "col-lg-6 col-md-10 col-sm-10 col-xs-10"> 
                                <div class="input-group mar-btm">
                                    <input type="text" placeholder = "Search Postings" class="form-control" name = "title" value = "{{ app('request')->input('title') }}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-mint" type = "submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div> 
                            </div>
                            <div class = "col-lg-6 col-md-2 col-sm-2 col-xs-2 demo-nifty-btn text-left">
                                <button type = "button" class = " filter_button btn btn-mint btn-rounded mar-no"> 
                                   <i class = "fa fa-sliders"></i>  Filters 
                                </button>
                            </div>
                        </div>  
                    </form>
				</div>
				<div class="card-body pad-no">   
                    <div class = "col-md-12 bord-top bg-gray-light filter-group hidden">
                        <form id = "filter-group-form"  method = "GET"  action = "{{ route('employer.mypostings') }}" >
                            <div class = "row pad-all">
                                <div class = "col-xs-4 text-dark">
                                    <h5 class="text-dark mar-ver"> 
                                        POST Type 
                                    </h5>  
                                    <div class="radio">
                                        <input id = "fiter-posttype-all-radio"   value = "all" class="magic-radio   filter_option" type="radio" name = "post_type"  @if($post_type == "all") checked="" @endif>
                                        <label for= "fiter-posttype-all-radio"> All </label>
                                    </div>
                                    <div class="radio">
                                        <input id  = "fiter-posttype-gig-radio"    value = "gig" class="magic-radio filter_option" type="radio"  name = "post_type" @if($post_type == "gig") checked="" @endif>
                                        <label for = "fiter-posttype-gig-radio">GIG</label>
                                    </div>
                                    <div class="radio">
                                        <input id  = "fiter-posttype-shift-radio"  value = "shift" class="magic-radio filter_option" type="radio" name = "post_type" @if($post_type == "shift") checked="" @endif>
                                        <label for = "fiter-posttype-shift-radio">SHIFT</label>
                                    </div>
                                </div>
                                <div class = "col-xs-4">
                                    <h5 class="text-dark mar-ver"> 
                                        STATUS 
                                    </h5>  
                                    <div class="checkbox">
                                        <input id  = "fiter-poststatus-drafts-checkbox" value = "drafts"  class="magic-checkbox filter_option" type="checkbox"  name = "post_status[]" @if(in_array( "drafts", $post_status))  checked @endif>
                                        <label for = "fiter-poststatus-drafts-checkbox">Drafts</label>
                                    </div> 
                                    <div class="checkbox">
                                        <input id  = "fiter-poststatus-open-checkbox" value = "open"     class="magic-checkbox filter_option" type="checkbox" name = "post_status[]" @if(in_array( "open", $post_status))  checked @endif>
                                        <label for = "fiter-poststatus-open-checkbox">Open</label>
                                    </div>
                                    <div class="checkbox">
                                        <input id  = "fiter-poststatus-closed-checkbox" value = "closed"   class="magic-checkbox filter_option" type="checkbox" name = "post_status[]" @if(in_array( "closed", $post_status))  checked @endif>
                                        <label for = "fiter-poststatus-closed-checkbox">Closed</label>
                                    </div>
                                </div>
                                <div class = "col-xs-4">
                                    <h5 class="text-dark mar-ver"> 
                                        Payment Type 
                                    </h5>  
                                    <div class="radio">
                                        <input id = "fiter-payment-all-radio"       value = "all" class="magic-radio  filter_option"  type="radio"    name = "payment"  @if($payment == "all") checked="" @endif >
                                        <label for= "fiter-payment-all-radio"> All </label>
                                    </div>
                                    <div class="radio">
                                        <input id  = "fiter-payment-fixed-radio"    value = "fixed" class="magic-radio filter_option" type="radio"   name = "payment"   @if($payment == "fixed") checked="" @endif>
                                        <label for = "fiter-payment-fixed-radio">Fixed</label>
                                    </div>
                                    <div class="radio">
                                        <input id  = "fiter-payment-hourly-radio"   value = "hourly" class="magic-radio filter_option" type="radio"    name = "payment" @if($payment == "hourly") checked="" @endif>
                                        <label for = "fiter-payment-hourly-radio">Hourly</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row clearfix mar-btm">
                                <div class = "col-xs-12 text-center">
                                    <button type="button" class=" filter_button btn btn-mint btn-rounded mar-no"> 
                                        Close Filters
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div> 
					@forelse($mypostings as $posted_job)
                        <div class = "posting-item clearfix  pad-hor"> 
                            <div class = "col-lg-5 col-md-6 col-sm-10 col-xs-9">
                                <h4 class="m-0-bottom m-md-right m-xs-top">
                                    <a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'applicants']) !!}" class="break btn-link">
                                        <span class = "text-dark">
                                            @if($posted_job->type == "gig")
                                                <span class = "text-mint"> [GIG] </span>
                                            @else
                                                <span class = "text-mint"> [SHIFT] </span>
                                            @endif
                                        </span>
                                        <span class = "text-dark"> {!! $posted_job->headline !!} </span> 
                                    </a>
                                </h4>      
                                <p class="m-0 text-muted m-sm-top">
                                    Created  {!! $posted_job->created_at->diffForhumans() !!}  
                                </p>
                                @if($posted_job->status == 1)
                                    <p class="m-0 text-dark text-capitalize">Public - {!! $posted_job->payment_type  !!} Price  </p>
                                @elseif($posted_job->status == 0)
                                    <p class="m-0 text-dark text-capitalize">Draft - Saved {!! date('M d, Y', strtotime($posted_job->updated_at)) !!} </p>
                                @else
                                    <p class="m-0 text-dark text-capitalize">Closed - Saved {!! date('M d, Y', strtotime($posted_job->updated_at)) !!} </p>
                                @endif
                            </div>
                            <div class = "d-block d-md-none col-sm-2 col-xs-3">
                                b
                            </div>
                            <div class = "col-lg-4 col-md-5 col-sm-12 p-0-top-md p-0-top-xl p-sm-top">
                                @if($posted_job->status !== 0)
                                    <div class = "row opening-counts-title mar-top">
                                        <div class = "col-xs-4 m-0-bottom p-0-right">
                                            <div class="opening-counts-value">
                                                <a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'applicants']) !!}">
                                                    @php
                                                        $posted_proposals = $posted_job->countActions('proposals');
                                                    @endphp
                                                    @if($posted_proposals)
                                                        <strong class="text-mint text-bold "> {!! $posted_proposals  !!}  </strong> 
                                                    @else
                                                        <strong class="text-dark text-bold "> 0  </strong> 
                                                    @endif
                                                </a>
                                            </div>  
                                            <p class="m-0 m-sm-top text-muted">  
                                                <a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'applicants']) !!}">
                                                    Proposals  
                                                </a>
                                            </p> 
                                        </div>  
                                        <div class="col-xs-4 m-0-bottom p-md-left"> 
                                            <div class="opening-counts-value">
                                                <a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'messaged']) !!}">
                                                    @php
                                                        $messaged_proposals = $posted_job->countActions('messaged');
                                                    @endphp
                                                    @if($messaged_proposals)
                                                        <strong class="text-mint text-bold "> {!! $messaged_proposals  !!}  </strong> 
                                                    @else
                                                        <strong class="text-dark text-bold "> 0  </strong> 
                                                    @endif
                                                </a> 
                                            </div>
                                            <p class="m-0 m-sm-top text-muted"> 
                                                <a href="{!! route('employer.jobs.mainaction', [$posted_job->serial, 'messaged']) !!}">
                                                    Messaged 
                                                </a> 
                                            </p>
                                        </div> 
                                        <div class="col-xs-4 m-0-bottom p-lg-left">
                                            <div class="opening-counts-value"> 
                                                <strong class = "text-bold text-dark"> 	0 </strong>
                                            </div>
                                            <p class="m-0 m-sm-top text-muted"> Hired 	</p>
                                        </div>  
                                    </div>
                                @endif
                            </div>
                            <div class = "col-lg-3 col-md-1 d-none d-md-block text-right"> 
                                <div class="dropdown">
                                    @if($posted_job->status == 1)
                                        <a href = "{!! route('employer.jobs.mainaction', [ $posted_job->serial  , 'applicants' ]) !!}"  class = "btn btn-mint"> View Proposals </a>
                                    @elseif($posted_job->status == 2)
                                        <a href = "{!! route('employer.jobs.repost', $posted_job->serial) !!}"  class = "btn btn-mint"> Reuse Posting </a>
                                    @else
                                        <a href = "{!! route('employer.jobs.edit', $posted_job->serial) !!}"  class = "btn btn-mint"> Edit Draft </a>
                                    @endif 
                                    <button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                        <i class = "demo-pli-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-lg"> 
                                        @if($posted_job->status !== 0)
                                            @if($posted_job->status == 1)
                                                <li><a href ="{!! route('employer.jobs.mainaction', [ $posted_job->serial  , 'applicants' ]) !!}">View Proposals</a></li> 
                                                <li class="divider"></li>
                                            @endif
                                                <li><a href="{!! route('employer.jobs.mainaction', [ $posted_job->serial ,'job-details']) !!}">View Gig Posting</a></li> 
                                            @if($posted_job->status == 1)
                                                <li><a href="{!! route('employer.jobs.edit', $posted_job->serial) !!}">Edit Gig Posting</a></li> 
                                            @endif 
                                            <li><a href="{!! route('employer.jobs.repost', $posted_job->serial) !!}">Reuse Gig Posting</a></li> 
                                            @if($posted_job->status == 1) 
                                                <li><a class = "remove_gig_posting" data-gig-serial = "{!! $posted_job->serial !!}" href = "javasript:void(0)" >Remove Gig Posting</a></li>
                                            @endif
                                        @else
                                            <li><a href="{!! route('employer.jobs.edit', $posted_job->serial) !!}">Edit Draft</a></li>   
                                            <li><a href="#">Remove Draft</a></li>
                                        @endif
                                    </ul>
                                </div> 
                            </div>
                        </div> 
					@empty
						<div class = "row mar-all pad-all">
							<div class="col-md-12 text-center pt-70">
                                <i class = "fa fa-file pad-rgt"> </i> 
							</div> 
                            <div class="col-md-12 text-center">
                                <h4> You haven't posted any jobs yet.</h4>
                                <h4>    
                                    Please <a href = "{!! route('employer.job.add') !!}" class = "text-mint btn-link text-bold"> post a gig</a>
                                </h4>
                            </div> 
						</div>
					@endforelse
                    
                    @if($mypostings->hasPages())
                        <div class = "col-md-12   text-right pad-hor">
                            {!! $mypostings->links() !!}
                        </div>
                    @endif
 
				</div>
			</div> 
		</div> 
    </div>
</div>
@include('employer.jobs.partial.closejob')
@endsection
@section('javascript') 
<script>
    var filter_group_option = window.localStorage.getItem('filter-group');
    if(filter_group_option == "show"){
        $(".filter-group").removeClass("hidden");
        window.localStorage.setItem('filter-group', "show");
    }

    function filter_group_loading(){
        if($(".filter-group").hasClass("hidden")){
            $(".filter-group").removeClass("hidden");
            window.localStorage.setItem('filter-group', "show");
        }
        else{
            $(".filter-group").addClass("hidden");
            window.localStorage.setItem('filter-group', "hide");
        }
    }
    
    //window.localStorage.getItem('user');
    $(".filter_button").click(function(){
        filter_group_loading();
    });
    $(".filter_option").click(function(){
        $("#filter-group-form").submit();
    });
</script>
@stop