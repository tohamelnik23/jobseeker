@extends('layouts.app')
@section('title', 'Search Gigs')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>     
@endsection
@section('content')
<div class="container">
    <div class = "row">
        @if($tab_type == "search")
        <div class = "col-lg-2"> 
            <form method = "GET"  action = "{{ route('search') }}"> 
                <div class = "clearfix">
                    <h4 class = "text-dark"> Filter By </h4>
                </div>

                <div class="category_stuff clearfix">
                    <div class="col-md-12">
                        <a href="javascript:void(0)" class="category-anchor">
                            <h5 class="text-dark mar-ver"> 
                                POST Type
                                <span class="pull-right text-bold text-dark anchor-down" >  
                                    <i class="fa fa-chevron-down"></i>
                                </span> 
                                <span class="pull-right text-bold text-dark anchor-up"  > 
                                    <i class="fa fa-chevron-up"></i>
                                </span>  
                            </h5> 
                        </a> 				
                    </div> 
                    <div class="col-md-12 category-items">
                        <div class = "row">
                            <div class = "col-md-12">
                                <div class="checkbox">
                                    <input id="sortby-type-fixed"  @if(in_array( "gig", $search_types))  checked @endif    class="magic-checkbox filter_action" type="checkbox" name="sortby_type[]" value = "gig">
                                    <label for="sortby-type-fixed"  class  = "text-dark"> GIG </label>
                                </div>
                            </div>
                            <div class = "col-md-12">
                                <div class="checkbox">
                                    <input id="sortby-type-hourly" @if(in_array( "shift", $search_types))  checked @endif  class="magic-checkbox filter_action" type="checkbox" name="sortby_type[]" value = "shift">
                                    <label for="sortby-type-hourly"  class  = "text-dark"> SHIFT </label>
                                </div>  
                            </div>
                        </div> 
                    </div>
                </div>

                <div class="category_stuff clearfix">
                    <div class="col-md-12">
                        <a href="javascript:void(0)" class="category-anchor">
                            <h5 class="text-dark mar-ver"> 
                                Location Type
                                <span class="pull-right text-bold text-dark anchor-down" >  
                                    <i class="fa fa-chevron-down"></i>
                                </span> 
                                <span class="pull-right text-bold text-dark anchor-up"  > 
                                    <i class="fa fa-chevron-up"></i>
                                </span>  
                            </h5> 
                        </a> 				
                    </div> 
                    <div class="col-md-12 category-items">
                        <div class = "row">
                            <div class = "col-md-12">
                                <div class="radio">
                                    <input id="sortby-jobtype-remote"  @if("local" == $search_locationtype))  checked @endif    class = "magic-radio filter_action" type="radio" name="sortby_locationtype" value = "local">
                                    <label for="sortby-jobtype-remote"  class  = "text-dark">Local Work</label>
                                </div> 
                            </div>
                            <div class = "col-md-12">
                                <div class="radio">
                                    <input id="sortby-jobtype-local" @if("remote" == $search_locationtype)  checked @endif  class = "magic-radio filter_action" type="radio" name="sortby_locationtype" value="remote">
                                    <label for="sortby-jobtype-local"  class  = "text-dark"> Remote Work </label>
                                </div>  
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="category_stuff clearfix">
                    <div class="col-md-12">
                        <a href="javascript:void(0)" class="category-anchor">
                            <h5 class="text-dark mar-ver"> 
                                Payment Type
                                <span class="pull-right text-bold text-dark anchor-down" >  
                                    <i class="fa fa-chevron-down"></i>
                                </span> 
                                <span class="pull-right text-bold text-dark anchor-up"  > 
                                    <i class="fa fa-chevron-up"></i>
                                </span>  
                            </h5> 
                        </a> 				
                    </div> 
                    <div class="col-md-12 category-items">
                        <div class = "row">
                            <div class = "col-md-12">
                                <div class="checkbox">
                                    <input id="sortby-jobtype-fixed"  @if(in_array( "fixed", $search_jobtypes))  checked @endif    class="magic-checkbox filter_action" type="checkbox" name="sortby_jobtype[]" value="fixed">
                                    <label for="sortby-jobtype-fixed"  class  = "text-dark">Fixed Price</label>
                                </div> 
                            </div>
                            <div class = "col-md-12">
                                <div class="checkbox">
                                    <input id="sortby-jobtype-hourly" @if(in_array( "hourly", $search_jobtypes))  checked @endif  class="magic-checkbox filter_action" type="checkbox" name="sortby_jobtype[]" value="hourly">
                                    <label for="sortby-jobtype-hourly"  class  = "text-dark"> Hourly </label>
                                </div>  
                            </div>
                        </div> 
                    </div>
                </div>
                @if(!Auth::check() || (Auth::user()->role != '1'))
                    @if("local" == $search_locationtype)
                        <div class="category_stuff clearfix"> 
                            <div class="col-md-12">
                                <a href="javascript:void(0)" class="category-anchor">
                                    <h5 class="text-dark mar-ver"> 
                                        Distance
                                        <span class="pull-right text-bold text-dark anchor-down" > 
                                            <i class="fa fa-chevron-down"></i>
                                        </span> 
                                        <span class="pull-right text-bold text-dark anchor-up" > 
                                            <i class="fa fa-chevron-up"></i>
                                        </span>  
                                    </h5> 
                                </a> 
                            </div> 
                            <div class="col-md-12 category-items">
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <div class="radio">
                                            <input id="sortby-distance-1" class="magic-radio filter_action" @if($travel_distance == 10) checked @endif type="radio" name="sortby_distance" value="10">
                                            <label for="sortby-distance-1"  class  = "text-dark"> Less than 10 miles</label>
                                        </div> 
                                    </div>
                                    <div class = "col-md-12">
                                        <div class="radio">
                                            <input id="sortby-distance-2" class="magic-radio filter_action" @if($travel_distance == 20) checked @endif type="radio" name="sortby_distance" value="20">
                                            <label for="sortby-distance-2"  class  = "text-dark oneline-eclipse">Less than 20 miles </label>
                                        </div>  
                                    </div>
                                    <div class = "col-md-12">
                                        <div class="radio">
                                            <input id="sortby-distance-3" class="magic-radio filter_action" @if($travel_distance == 30) checked @endif type="radio" name = "sortby_distance" value="30">
                                            <label for="sortby-distance-3" class  = "text-dark oneline-eclipse"> Less than 30 miles </label>
                                        </div>  
                                    </div> 
                                    <div class = "col-md-12">
                                        <div class="radio">
                                            <input id="sortby-distance-4" class="magic-radio filter_action" @if($travel_distance == 40) checked @endif type="radio" name = "sortby_distance" value="40">
                                            <label for="sortby-distance-4"  class  = "text-dark oneline-eclipse"> Less than  40 miles </label>
                                        </div>  
                                    </div> 
                                    <div class = "col-md-12">
                                        <div class="radio">
                                            <input id="sortby-distance-5" class="magic-radio filter_action" @if($travel_distance == 50) checked @endif type="radio" name = "sortby_distance" value="50">
                                            <label for="sortby-distance-5"  class  = "text-dark oneline-eclipse"> Less than  50 miles </label>
                                        </div>  
                                    </div> 
                                    <div class = "col-md-12">
                                        <div class="radio">
                                            <input id="sortby-distance-6" class="magic-radio filter_action" @if($travel_distance == -1) checked @endif type="radio" name = "sortby_distance" value="-1">
                                            <label for="sortby-distance-6"  class  = "text-dark oneline-eclipse"> Any Distance  </label>
                                        </div>  
                                    </div>  
                                </div> 
                            </div> 
                        </div> 
                    @endif
                    <input id = "query_filter_zipcode"  type = "hidden"  name = "zip_code" />  
                @endif
                <input id = "query_filter_category" type = "hidden"  name = "category" />  
                <button  class="hidden searchbutton" type="submit"> Search </button> 
            </form>
        </div>
        @endif 
        <div class = "@if($tab_type == 'search') col-lg-8 @else  col-lg-12  @endif"> 
        @if($tab_type == 'search')
            @if($total_shifts)
            <div class = "row">
                <div class = "col-lg-12">
                    <a href = "{{ route('search.shifts') }}?{{ $_SERVER['QUERY_STRING'] }}" class = "btn-link"   target="_blank">
                        <h4 class = "text-dark text-center"> Shift Available In Your Area : 
                            <span class = "text-mint"> {!! $total_shifts !!}  @if($total_shifts == 1) shfit  @else shfits @endif </span> 
                        </h4>
                    </a>
                </div>
            </div>
            @endif
        @endif 
            <div class = "card">
                <div class = "card-body pad-no">
                    <header class = "pad-all bord-btm clearfix">
                        @if(!Auth::check() || (Auth::user()->role != 1)) 
                            <div class = "col-md-12 d-flex">
                                <div class="input-group mar-btm flex-1">
                                    <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                    <input type="text" class="form-control search_input" name = "zipcode" placeholder="ZipCode" @if(isset($zip_code)) value = "{!! $zip_code !!}" @endif>
                                </div>
                                <div class="input-group mar-btm flex-2">
                                    <select class = "form-control search_input" name = "category">
                                        @foreach($categories as $category)
                                            @if($category->calculate_subcategories())
                                                <option value = "{!! $category->serial !!}"  @if($cur_category == $category->id) selected @endif >{!! $category->industry !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn btn-mint search_button" type="button"><i class = "fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div> 
                        @else 
                            <div class = "col-md-12"> 
                                <div class="input-group mar-btm">
                                    <select class = "form-control search_input" name = "category">
                                        @foreach($categories as $category)
                                            @if($category->calculate_subcategories())
                                                <option value = "{!! $category->serial !!}"  @if($cur_category == $category->id) selected @endif >{!! $category->industry !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn btn-mint search_button" type="button"><i class = "fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </header>
                    <div class = "job-result-page"> 
                        <div class = "row">
                            <div class = "col-md-12">
                                @forelse($jobs as $job)
                                <section class = "job-tile  freelancer_item_card description_item_card hover m-0-top ">
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @if(Auth::check() && ($job->checkApplied(Auth::user()->id) ))
                                                <p class = "text-mint mar-no"> <i class = "fa fa-check"></i> Applied </p> 
                                            @endif
                                        </div> 
                                        <div class = "col-md-12">
                                            <div class = "row">
                                                <div @if(Auth::check() && (Auth::user()->role == 1)) class = "col-md-10 col-sm-9" @else  class = "col-md-12"  @endif>
                                                    <a class = "btn-link" href = "{!! route('jobs_details', $job->serial) !!}">
                                                        <span class = "text-dark h4">
                                                            <span class = "text-mint text-uppercase"> [{{ $job->type }}] </span>  {{ $job->headline }}
                                                        </span>
                                                    </a>
                                                    @if($job->type == "shift")
                                                        <span class = "h5 text-mint">
                                                            {!! $job->getDisplayDate() !!}   {!! $job->getJobTime() !!}
                                                            @if($job->shift_end_date_time)
                                                                ~ {!! date('H:i A' , strtotime($job->shift_end_date_time))  !!}
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                                @if(Auth::check() && (Auth::user()->role == 1))
                                                    <div class = "col-md-2 col-sm-3">
                                                        <button class="btn savejob_action   btn-default btn-card-button btn-rounded" data-serial = "{!! $job->serial  !!}"  type="button">
                                                            <span class="text-mint">
                                                                @if($job->SavedJob( Auth::user()->id))
                                                                    <i class = "fa fa-heart"></i> 
                                                                @else  
                                                                    <i class = "fa fa-heart-o"></i>
                                                                @endif
                                                            </span>
                                                        </button>
                                                    </div>
                                                @endif 
                                            </div> 
                                        </div> 
                                        <div class = "col-md-12">
                                            <small class="text-muted display-inline-block text-dark m-sm-top pt-12"> 
                                                <span class="ng-isolate-scope">
                                                    <strong class="js-type text-capitalize">
                                                        {!! $job->payment_type !!} : {!! $job->getBudget() !!}
                                                    </strong> 
                                                </span> 
                                                <span> - </span> 
                                                @if($job->location_type == "remote")
                                                    <span class = "text-dark">Remote Work</span>
                                                @else
                                                    <span class = "text-dark">Local Work</span>
                                                @endif

                                                <span> - </span> 
                                                <span>Posted </span> 
                                                <time class="">in {!! $job->updated_at->diffForhumans() !!}</time>  
                                            </small>   
                                            <div class = "m-sm-bottom ng-isolate-scope mar-top">
                                                @if(strlen($job->description) > 340 )
                                                <div class = "description-shortlist description-part"> 
                                                    <span class="pt-14 text-dark ">
                                                        {!! substr($job->description, 0, 340) !!} ...
                                                    </span>
                                                    <a class="btn-link text-mint btn-description-action more" href="javascript:void(0)"> more </a> 
                                                </div> 
                                                <div class = "description-detaillist hidden description-part"><span class="pt-14 text-dark">{!! $job->description !!}</span><a class="btn-link btn-description-action text-mint less" href="javascript:void(0)"> less </a> 
                                                </div>
                                                @else
                                                <div class = "description-shortlist description-part"> 
                                                    <span class="pt-14 text-dark ">
                                                        {{ $job->description }}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>
                                            @php
                                                $skills = $job->getSkills();
                                            @endphp
                                            @if(count($skills))
                                            <p>
                                                @foreach($skills as $skill_index => $skill)
                                                    @if( $skill_index < 4)
                                                    <span class="badge pad-rgt">{!! $skill->skill !!}</span> 
                                                    @endif
                                                @endforeach
                                                @if( (count($skills) - 4) > 0)
                                                    <a href = "{!! route('jobs_details', $job->serial) !!}" class = "btn-link text-mint"> more </a>
                                                @endif
                                            </p> 
                                            @endif
                                            <p class="mar-top text-dark">
                                                @php
                                                    $client = $job->getClient();
                                                @endphp

                                                @if(isset($client))
                                                    @if($client->checkPaymentMethod())
                                                        <span class = "mar-btm pad-rgt"> 
                                                            <img class = "checkbox-img" src = "{!! asset('img/checkbox.png') !!}" /> Payment verified 
                                                        </span> 
                                                    @else
                                                        <span class = "mar-btm pad-rgt"> 
                                                            Payment unverified 
                                                        </span>
                                                    @endif
                                                @endif
                                                @php
                                                    $total_spent     =  $client->getTotalSpent(); 
                                                    $spent_money     =  Mainhelper::getAroundAmount($total_spent);
                                                    $feedback_point  =  $client->getFeedBackPoint();
                                                @endphp 
                                                <span  class = "total-rating pad-rgt" data-size = "14"  data-star = '{!! $feedback_point !!}'></span> 
                                                <span class = "mar-no pad-rgt"> ${!! $spent_money !!}  spent </span> 
                                                <span> <i class="fa fa-map-marker"></i> {!! $job->city !!}, {!! $job->state !!} </span>
                                            </p>
                                        </div> 
                                    </div>
                                </section>
                                @empty
                                    <div class = "clearfix  text-center">
                                        <img src = "{!! asset('img/background/no-search-result.png') !!}" width = "20%" />
                                        <h3 class = "text-dark"> There are no results that match your search </h3>
                                        <p class = "pt-15 mar-btm pad-btm"> Please try adjusting your search keywords or filters </p>
                                    </div>
                                @endforelse
                            </div> 
                            @if ($jobs->hasPages())
                                <div class = "col-md-12 mar-top text-right pad-hor">
                                    {!! $jobs->links() !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        @if($tab_type == "search") 
            @if(Auth::check() && (Auth::user()->role == '1'))
                <div class = "col-lg-2">
                    <div class = "clearfix">
                        <h4 class = "text-dark"> Availability </h4>
                    </div>
                    <div class  = "mar-top-5"> 
                        <a href = "javascript:void(0)" class = "text-dark pt-15 text-bold btn-link availability-button text-capitalize">
                            @if(Auth::user()->availability == "active")
                                <i class = "fa fa-eye pad-rgt"> </i> 
                            @else
                                <i class = "fa fa-eye-slash pad-rgt"> </i> 
                            @endif
                            {{ Auth::user()->availability }} 
                        </a>
                    </div>
                    <div class = "clearfix mar-top">
                        <h4 class = "text-dark"> Distance </h4>
                    </div>
                    <div class  = "mar-top-5"> 
                        <a href = "javascript:void(0)" class = "text-dark pt-15 text-bold btn-link disired_travel_distance-button text-capitalize">
                            <i class = "fa fa-map-marker pad-rgt"></i>  
                            @if( Auth::user()->accounts->travel_distance)
                                {{ Auth::user()->accounts->travel_distance  }} miles
                            @else
                                Any Disctance
                            @endif
                        </a>
                    </div>
 
                    <div class = "clearfix mar-top">
                        <h4 class = "text-dark"> Proposals </h4>
                    </div>  
                    @php
                        $total_offers = Auth::user()->getOffers(0);
                    @endphp
                    @if($total_offers)
                    <div class  = "mar-top"> 
                        <a href = "{!! route('employee.proposals') !!}" class = "btn-link text-mint text-bold pt-13">
                            {!! $total_offers !!} @if($total_offers == 1)  offer @else  offers  @endif
                        </a> 
                    </div> 
                    @endif 

                    @php
                        $total_invites = Auth::user()->getInvites(0);
                    @endphp
                    @if($total_invites)
                    <div class  = "mar-top"> 
                        <a href = "{!! route('employee.proposals') !!}" class = "btn-link text-mint text-bold pt-13">{!! $total_invites !!} 
                            @if($total_invites == 1)  invitation @else  invitations  @endif    
                            to interviews</a> 
                    </div>
                    @endif

                    @php
                        $total_proposals = Auth::user()->getProposals(1);
                    @endphp

                    @if($total_proposals)
                    <div class  = "mar-top"> 
                        <a href = "{!! route('employee.proposals') !!}" class = "btn-link text-mint text-bold pt-13">
                            {!! $total_proposals !!}   
                            @if($total_proposals == 1)  interview @else  interviews  @endif
                        </a>
                    </div>
                    @endif

                    @php
                        $total_proposals = Auth::user()->getProposals(2);
                    @endphp

                    @if($total_proposals)
                    <div class  = "mar-top"> 
                        <a href = "{!! route('employee.proposals') !!}" class = "btn-link text-mint text-bold pt-13">
                            {!! $total_proposals !!} active 
                            @if($total_proposals == 1)  candidate @else  candidates  @endif
                        </a>
                    </div>
                    @endif

                    @php
                        $total_proposals = Auth::user()->getProposals();
                    @endphp
                    <div class  = "mar-top"> 
                        <a href = "{!! route('employee.proposals') !!}" class = "btn-link text-mint text-bold pt-13">
                            @if($total_proposals)
                                {!! $total_proposals !!} submitted
                                @if($total_proposals == 1) proposal @else proposals  @endif
                            @else
                                No submitted proposals
                            @endif
                        </a>  
                    </div>
                </div>
            @endif 
        @endif
    </div>
</div>
@include('employee.profile.partial.availability_action')
@include('employee.profile.partial.distance_action')

@endsection
@section('javascript')
<script src="{{asset('plugins/simplerating/jquery.star-rating-svg.js')}}"></script>
<script> 
    $(document).on("click", ".filter_action", function(){
        $(".searchbutton").trigger("click");
    }); 
    @if(Auth::check() && (Auth::user()->role == 1))  
        $(document).on("click", ".savejob_action", function(){
            var job_id  =  $(this).attr('data-serial');
            $.ajax({
                url: "{{ route('employee.saveaction') }}", 
                type: 'POST',
                dataType: 'json',
                data: {job_id: job_id },     
                beforeSend: function () { 
                },                                        
                success: function(json) {
                    if(json.status == 1){
                        location.reload();
                    }
                    else{
                        swal({
                            title: "Error Occured",   
                            text:   json.msg, 
                            type: "error",   
                            confirmButtonText: "Close" 
                            }).then(function(isConfirm) {
                            if(isConfirm){ 
                            }
                        }); 
                    }
                },
                complete: function () { 
                },
                error: function() { 
                }
            }); 
        }); 
    @endif
    
    function search_action_input_val(){
        var search_zipcode =  $.trim($("input[ name = 'zipcode']").val());
        $("#query_filter_zipcode").val(search_zipcode); 
        $("#query_filter_category").val( $("select[name = 'category'] option:selected").val() );
    }

    function search_action_input(){
        search_action_input_val();
        $(".searchbutton").trigger("click");
    } 
    $('.search_input').keydown(function (e){
        if(e.keyCode == 13){
            search_action_input();
        }
    });

    $(".search_input").change(function(){
        search_action_input();
    });

    $(".search_button").click(function(){
        search_action_input();
    });
    search_action_input_val();
    $(".total-rating").each(function(){ 
        $(this).starRating({
            initialRating: $(this).attr('data-star'),
            starSize:   $(this).attr('data-size'),
            totalStars: 5,  
            disableAfterRate: false,
            readOnly: true
        });
    });
</script>
@stop