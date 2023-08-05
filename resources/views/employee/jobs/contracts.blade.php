@extends('layouts.app')
@section('title', 'Contracts') 
@section('css') 
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>
<style>
    .inline-form-control-select{
        height: 33px;
        font-size: 13px;
        border-radius: 3px;
        box-shadow: none;
        border: 1px solid rgba(0,0,0,0.07);
        transition-duration: .5s;
        padding : 3px 4px;
    }

    @media (min-width: 768px){
        .width-md{
            width : 300px !important;
        }
    }

</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            @include('partial.alert')
        </div>
        <div class = "col-md-12">
            <h2 class="text-main pad-btm mar-no  pad-lft text-semibold text-left text-dark">  
                Contracts
            </h2>
        </div>
        <div class="col-md-12">
			<div class="card mar-ver"> 
				<div class="card-body pad-no">  
                    <header class="pad-all bord-btm"> 
                        <form  method = "GET"  action = "{{ route('employee.contracts') }}">
                            <div class = "row clearfix bord-btm">
                                <div class = "col-md-6"> 
                                    <div class="input-group mar-btm">
                                        <input name = "q" type="text" placeholder="Search contract or client name" class="form-control" value = "{!! app('request')->input('q') !!}" >
                                        <span class="input-group-btn">
                                            <button class = "btn btn-mint search-filter-button" type = "submit"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class = "row clearfix pad-top">
                                <div class = "col-md-6"> 
                                    <label class = "h5 text-dark pad-rgt">Sort By</label> 
                                    <select class = "inline-form-control-select width-md filter-action" name = "filter_by">
                                        <option value = "start_date"     @if($filter_by == "start_date") selected @endif >Start Date</option>
                                        <option value = "end_date"       @if($filter_by == "end_date") selected @endif >End Date</option>
                                        <option value = "contract_title" @if($filter_by == "contract_title") selected @endif >Contract Titile</option>
                                    </select>
                                    <input id = "sort_by_action" name = "sort_by" type = "hidden" value = "{{ $sort_by }}" /> 
                                    <button class = "btn btn-default btn-circle  filter-action-button" data-type = "{{ $sort_by }}"  type = "button">
                                        @if($sort_by == "asc")
                                            <i class = "fa fa-sort-amount-asc"></i>
                                        @else
                                            <i class = "fa fa-sort-amount-desc"></i>
                                        @endif
                                    </button> 
                                </div> 
                                <div class = "col-md-6"> 
                                    <div class="checkbox">
                                        <input id   =   "closed-contracts-checkbox" class="magic-checkbox filter-action" type="checkbox"  name = "closed_contracts" @if($closed_contracts == "off") checked   @endif>
                                        <label for  =   "closed-contracts-checkbox"> Include closed contracts </label>
                                    </div>                                               
                                </div> 
                            </div>
                        </form> 
                    </header>
                    @forelse($contracts as $contract)
                        @php
                            $posted_job     =  $contract->getJob();
                            $client         =  $contract->getClient();
                            $messagelist    =  $contract->getMessageList();  
                        @endphp
                        <div class = "posting-item clearfix   pad-hor"> 
                            <div class = "row m-sm-top">
                                <div class = "col-md-4 col-sm-6">
                                    <div class="opening-counts-value">
                                        <a href="{!! route('jobs_contract_details', $contract->serial) !!}">
                                            <strong class="text-dark"> {!!  $contract->contract_title  !!}  </strong>  
                                        </a>
                                    </div>
                                    <p class="mar-top-5  text-dark">   {!! $client->accounts->name !!}  </p> 
                                    <p class="mar-top-5  text-muted">
                                        {!!  date('M d, Y', strtotime($contract->start_time))   !!}  
                                        @if($contract->end_time)
                                            - {!!  date('M d, Y', strtotime($contract->end_time))  !!}
                                        @else
                                            - Present
                                        @endif
                                    </p>
                                </div>
                                <div class = "col-md-6 col-sm-4">
                                    @if($contract->payment_type == "fixed")
                                        <div class="clearfix mar-btm-5">
                                            <span class = "text-bold text-dark"> ${!! $contract->amount !!} </span>
                                            <span class = "pad-lft pt-15"> Budget </span>  
                                        </div> 
                                    @endif
                                    @if($contract->payment_type == "hourly")
                                        <div class="clearfix mar-btm-5"> 
                                            <span class = "text-bold text-dark"> ${!! $contract->amount !!}/hr </span> 
                                        </div> 
                                        <p class="mar-top-5 mar-btm-5  text-dark">  {!! $contract->weekly_limit !!} max hrs/wk  </p> 
                                    @endif
                                    @if($contract->status == 10)
                                        @if($contract->end_time) 
                                            <p class="mar-top-5 mar-btm-5  text-dark">  Completed {!! date('M j, Y', strtotime($contract->end_time)) !!}  </p>
                                        @endif
                                        @php
                                            $freelancer_feedback    =  $contract->getFeedback(  Auth::user()->id );
                                            $client_feedback        =  $contract->getFeedback( $contract->employer_id  );
                                        @endphp 
                                        @if(isset($freelancer_feedback) && isset($client_feedback))
                                            <p  class = "total-rating" data-star = '{!! $freelancer_feedback->rate_total !!}'></p>
                                        @endif
                                    @endif 
                                </div>
                                <div class = "col-md-2 col-sm-2 js-stop-propagation text-right">
                                    <div class="dropdown">
                                        <button class="btn btn-default  btn-rounded dropdown-toggle" data-toggle="dropdown" type="button">
                                            <span class = "text-bold text-dark">  ... </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-lg"> 
                                            @if($contract->status == 1)
                                                <li><a href="#">Submit work / Request $</a></li> 
                                                <li class="divider"></li>
                                            @endif  
                                            <li><a href="{!!  route('messages', $messagelist->serial)  !!}?room={!! $client->serial !!}">Send a message</a></li>
                                            <li><a href="#">Propose new contract</a></li>
                                        </ul>
                                    </div> 
                                </div>
                            </div> 
                        </div> 
					@empty
						<div class = "row mar-all pad-all">
							<div class="col-md-12 text-center">
                                Contracts you're actively working on will appear here.
                                <a href = "{!! route('search') !!}" class = "text-mint btn-link"> Start searching for new projects now! </a>
							</div> 
						</div> 
					@endforelse 
                    <div class = "row clearfix">
                        <div class="col-md-12 text-right"> 
                            {{$contracts->links()}} 
                        </div> 
                    </div>                    
				</div>
			</div>
		</div> 
    </div>
</div>
@endsection
@section('javascript')
<script src="{{asset('plugins/simplerating/jquery.star-rating-svg.js')}}"></script> 
<script>
    $(".total-rating").each(function(){
        $(this).starRating({
            initialRating: $(this).attr('data-star'),
            starSize:   21,
            totalStars: 5,  
            disableAfterRate: false,
            readOnly: true
        });
    });  
    // Filter action
    $(".filter-action").change(function(){
        $(".search-filter-button").trigger("click");
    });
    $(".filter-action-button").click(function(){
        if($(this).attr('data-type') == "asc")
            $("input[name = 'sort_by']").val("desc");
        else
            $("input[name = 'sort_by']").val("asc");        
        $(".search-filter-button").trigger("click");
    });
</script>
@stop