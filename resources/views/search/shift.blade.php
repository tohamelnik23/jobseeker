@extends('layouts.app')
@section('title', 'Search Shifts')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>
@endsection
@section('content')
<div class="container">
    <div class = "row">
        @if($tab_type == "search")
            <div class = "col-lg-2"> 
                <form method = "GET"  action = "{{ route('search.shifts') }}"> 
                    <div class = "clearfix">
                        <h4 class = "text-dark"> Filter By </h4>
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
                    <input      id = "query_filter_category" type = "hidden"  name = "category" />  
                    <button     class="hidden searchbutton"  type="submit">     Search </button> 
                </form>
            </div>
        @endif
        <div class = "@if($tab_type == 'search') col-lg-8 @else  col-lg-12  @endif">
            @if($tab_type == 'search')
                @if($total_shifts)
                <div class = "row">
                    <div class = "col-lg-12"> 
                        <h4 class = "text-dark text-center"> Shift Available In Your Area : 
                            <span class = "text-mint"> {!! $total_shifts !!}  @if($total_shifts == 1) shfit  @else shfits @endif </span> 
                        </h4>
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
                                <div class="table-responsive pad-all">
                                    <table class="table table-bordered text-dark" style = "border-top: 1px solid #ddd;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Payment     </th>
                                                <th class = "text-center"> Time   </th>
                                                <th>Headline    </th>
                                                <th>Location    </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($jobs as $job)
                                                <tr>
                                                    <td class="text-center align-middle text-capitalize"> {{ $job->payment_type }}</td>
                                                    <td class = "align-middle text-center">
                                                        <p class = "mar-no">
                                                            {!! $job->getDisplayDate() !!}   {!! $job->getJobTime() !!}
                                                        </p>
                                                        <p class = "mar-no text-center"> - </p>
                                                        <p class = "mar-no"> {{ date('H:i A', strtotime($job->shift_end_date_time))}}  </p>
                                                    </td>
                                                    <td class = "align-middle">
                                                        <a href = "{!! route('jobs_details', $job->serial) !!}" class = "btn-link text-mint">
                                                            {{ $job->headline }}
                                                        </a>
                                                    </td>
                                                    <td class = "align-middle">
                                                        {!! $job->city !!}, {!! $job->state !!} 
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class = "align-middle" colspan = 5>
                                                        <p class = "text-dark text-center mar-top"> There are no results that match your search </p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>  
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